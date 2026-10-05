# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

- `composer phpstan` - Run static analysis at maximum level
- `composer test` - Run PHPUnit tests
- `composer compile` - Generate mappers (required after adding new payload classes)

## FMP API Documentation

- Complete FMP API documentation in Markdown: https://site.financialmodelingprep.com/api-docs.md
- Use it to look up endpoint paths, their parameters and whether an endpoint has a bulk variant.
- HTML documentation with the regional availability of every endpoint: https://site.financialmodelingprep.com/developer/docs
- The site answers HTTP 403 to requests without a browser `User-Agent` (WebFetch and plain `curl` included), so download both with `curl -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) Chrome/129.0 Safari/537.36'`.

### Regional availability (US only)

Whether an endpoint is US only must be looked up on the HTML documentation page, the Markdown documentation does not contain it. In the HTML each endpoint heading (`<a ... href="/developer/docs/stable/...">`) is followed by a flag image whose `aria-label` reads either `This endpoint is only available for US-based companies.` or `This endpoint is available for companies worldwide.`.

- A path documented in several sections (`quote`, `historical-price-eod/*`, `historical-chart/*`) carries one flag per section, use the flag of the stock section.
- Record the result in the docblock of the `FmpClient` method as the sentence `Only available for US-based companies.` or `Available worldwide.`, and in OVERVIEW.md as the `**Availability:**` line (`US-based companies only` or `Worldwide`).
- An endpoint without a flag gets no sentence in the docblock; in OVERVIEW.md use `Not company specific` (indexes, forex, crypto, macro data) or `Not stated in the FMP docs`. Never guess the availability.

## Architecture Overview

This is a PHP 8.4+ client library for the Financial Modeling Prep (FMP) API, designed to handle large financial datasets efficiently.

### Core Components

- **FmpClient** (`src/FmpClient.php`) - Interface defining the API contract
- **SymfonyFmpClient** (`src/SymfonyFmpClient.php`) - Primary implementation using Symfony HTTP Client, handles API calls and response processing
- **CacheFmpClient** (`src/CacheFmpClient.php`) - Caching decorator wrapping any `FmpClient` implementation via `PSR-16 SimpleCache`. Caches per-symbol and list endpoints; delegates bulk, calendar, and streaming endpoints directly to the inner client
- **FmpPromise** - Async operations using PHP Fibers for concurrent API calls
- **LargeResponseParser** - Memory-efficient streaming parser using JsonMachine and League CSV

### Key Design Principles

1. **Memory Efficiency**: Large JSON/CSV responses are streamed, not loaded entirely into memory
2. **Async Support**: Uses PHP Fibers for non-blocking concurrent API requests
3. **Strong Typing**: All data structures use readonly classes with comprehensive type hints
4. **Immutability**: Payload objects are immutable with readonly properties
5. **Validation**: Strict mode available for enhanced data validation

### Adding New Endpoints

When implementing new FMP API endpoints:

1. Fetch response to determine structure, save it to the `tests/Unit/fixtures/` directory for future testing.
2. Create payload class in `src/Payload/` (extend from existing patterns)
3. Run `composer compile` to automatically generate mappers. This command scans all payload classes with `#[CompileObjectMapper]` attribute and generates corresponding mapper classes in `src/Mapper/`. Mappers are generated automatically - do not create them manually.
4. Add endpoint method signature to `FmpClient` interface with `@see` annotation containing the endpoint URL without an API key (query parameter `apikey`) and with the regional availability sentence (see "Regional availability (US only)"). Implement the method in `SymfonyFmpClient`. Add corresponding cached/delegated method to `CacheFmpClient` (cache per-symbol and list endpoints, delegate bulk and streaming endpoints).
5. Create test fixtures in `tests/Unit/fixtures/`, save **full** response body from the API to the `tests/Unit/fixtures`.
6. Write comprehensive tests covering both success and error cases
7. Update README.md with the new endpoint documentation
8. Update OVERVIEW.md with the new endpoint:
   - Add method to the appropriate category section
   - Include method name, purpose, parameters, and **complete** list of all return values
   - Add the `**Availability:**` line right below the purpose (see "Regional availability (US only)")
   - List all return value fields with their descriptions (do not use "and many others" or similar shortcuts)
   - Add API endpoint URL without the API key parameter

### Payload Classes

All payload classes in `src/Payload/` must include:

- **CompileObjectMapper attribute**: Add `#[CompileObjectMapper]` attribute to the class
  - Most payload classes use `identifier` parameter (e.g., `#[CompileObjectMapper(identifier: 'symbol')]`)
  - Common identifiers: `'symbol'`, `'exchange'`, `'oldSymbol'`
  - Some payloads don't specify an identifier (e.g., HistoricalChart, ExchangeMarketHours)
- **Constructor**: All properties as readonly constructor parameters
- **toArray() method**: Returns all properties as associative array with complete `@return array{ ... }` type annotation
  - Example: `@return array{symbol: non-empty-string, name: string, price: float|null}`
  - Include all properties with their exact types (including `|null` for nullable properties)
  - Use proper type annotations: `string`, `int`, `float`, `bool`, `string|null`, `int|null`, etc.
  - **Important**: If constructor parameters are typed as `non-empty-string`, the `toArray()` return type should also use `non-empty-string` (not just `string`)
- **Cache compatibility**: Adding, removing or renaming a property of a payload cached by `CacheFmpClient` requires bumping `CacheFmpClient::CacheKeyVersion`, otherwise entries serialized with the old shape are unserialized with uninitialized properties
- **Money amounts**: Integer money amounts computed by FMP (market cap, enterprise value) use `#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]`, because they occasionally arrive with a floating-point tail

When creating tests for payload classes:
- Use `assertSame()` with `toArray()` methods for payload comparisons
- Example: `$this->assertSame($expectedPayload->toArray(), $actualPayload->toArray())`
- Never use `assertEquals()` for payload object comparisons

### Testing Notes

- Uses MockHttpClient for API mocking
- Test fixtures contain real API response samples
- Base TestCase provides helper methods for client setup


### Bulk endpoints
- Use `yield` for bulk responses to avoid memory issues
