# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

- `composer phpstan` - Run static analysis at maximum level
- `composer test` - Run PHPUnit tests

## Architecture Overview

This is a PHP 8.3+ client library for the Financial Modeling Prep (FMP) API, designed to handle large financial datasets efficiently.

### Core Components

- **FmpClient** (`src/FmpClient.php`) - Main entry point, orchestrates API calls and response processing
- **FmpRequest/FmpResponse** - HTTP layer using Symfony HTTP Client
- **FmpPromise** - Async operations using PHP Fibers for concurrent API calls
- **LargeResponseParser** - Memory-efficient streaming parser using JsonMachine and League CSV
- **FmpPayloadMapper** - Maps API responses to strongly-typed payload objects
- **FmpValidator** - Validation layer using Webmozart Assert

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
3. Add mapping logic in `FmpPayloadMapper` 
4. Add endpoint method to `FmpClient`, add `@see` annotation to the method docblock containing the endpoint URL without an API key (query parameter `apikey`).
5. Create test fixtures in `tests/Unit/fixtures/`, save **full** response body from the API to the `tests/Unit/fixtures`.
6. Write comprehensive tests covering both success and error cases

### Payload Classes

All payload classes in `src/Payload/` must include:

- **Constructor**: All properties as readonly constructor parameters
- **toArray() method**: Returns all properties as associative array with complete `@return array{ ... }` type annotation
  - Example: `@return array{symbol: string, name: string, price: float|null}`
  - Include all properties with their exact types (including `|null` for nullable properties)
  - Use proper type annotations: `string`, `int`, `float`, `bool`, `string|null`, `int|null`, etc.

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
