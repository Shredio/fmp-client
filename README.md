# FMP Client

A high-performance PHP client library for the [Financial Modeling Prep (FMP) API](https://financialmodelingprep.com/), designed to handle large financial datasets efficiently with memory-conscious streaming and asynchronous processing.

## Key Features

- **Memory Efficient**: Large JSON/CSV responses are streamed using JsonMachine and League CSV, not loaded entirely into memory
- **Async Support**: Non-blocking concurrent API requests using PHP Fibers via FmpPromise
- **Strong Typing**: All data structures use readonly classes with comprehensive type hints
- **Immutable Payloads**: All payload objects are immutable with readonly properties
- **Validation**: Optional strict mode for enhanced data validation
- **Bulk Operations**: Efficient handling of bulk data endpoints
- **Error Handling**: Comprehensive exception handling with request URL context

## Requirements

- PHP 8.3 or higher
- Symfony HTTP Client
- JsonMachine for efficient JSON parsing
- League CSV for CSV processing  
- Webmozart Assert for validation

## Installation

Install via Composer:

```bash
composer require shredio/fmp-client
```

## Quick Start

```php
use Shredio\FmpClient\FmpClient;
use Symfony\Component\HttpClient\HttpClient;

// Initialize the client
$httpClient = HttpClient::create();
$fmpClient = new FmpClient($httpClient, 'your-api-key-here');

// Get company profile
foreach ($fmpClient->companyProfile('AAPL') as $profile) {
    echo "Company: {$profile->companyName}\n";
    echo "Price: ${$profile->price}\n";
    echo "Market Cap: {$profile->marketCap}\n";
}

// Get stock list (streamed)
foreach ($fmpClient->stockList() as $stock) {
    echo "{$stock->symbol}: {$stock->name}\n";
}
```

## Configuration Options

### Strict Mode

Enable strict mode for enhanced validation that throws exceptions on invalid data:

```php
$strictClient = $fmpClient->withStrictMode(true);

try {
    foreach ($strictClient->companyProfile('INVALID') as $profile) {
        // Process profile
    }
} catch (UnexpectedResponseContentException $e) {
    echo "Validation error: {$e->getMessage()}\n";
    echo "Request URL: {$e->getRequestUrl()}\n";
}
```

### Custom Error Handling

Implement custom error handling for non-strict mode (useful for logging):

```php
use Shredio\FmpClient\Exception\UnexpectedResponseContentExceptionHandler;

$handler = new class implements UnexpectedResponseContentExceptionHandler {
    public function handle(UnexpectedResponseContentException $exception): void {
        error_log("FMP Client Error: {$exception->getMessage()}");
    }
};

$fmpClient = new FmpClient($httpClient, 'api-key', $handler);
```

## Usage Examples

### Basic Stock Data

```php
// Get available exchanges
foreach ($fmpClient->availableExchanges() as $exchange) {
    echo "{$exchange->name} ({$exchange->code})\n";
}

// Get company financials
foreach ($fmpClient->balanceSheetStatement('AAPL') as $statement) {
    echo "Date: {$statement->date}\n";
    echo "Total Assets: {$statement->totalAssets}\n";
}

// Get dividend history
foreach ($fmpClient->dividends('AAPL') as $dividend) {
    echo "Date: {$dividend->date}, Amount: {$dividend->dividend}\n";
}

// Get shares float information
$sharesFloat = $fmpClient->getSharesFloat('AAPL');
if ($sharesFloat !== null) {
    echo "Free Float: {$sharesFloat->freeFloat}%\n";
    echo "Float Shares: {$sharesFloat->floatShares}\n";
    echo "Outstanding Shares: {$sharesFloat->outstandingShares}\n";
}
```

### Bulk Operations

```php
use DateTimeImmutable;

// Bulk company profiles (memory efficient streaming)
foreach ($fmpClient->companyProfileBulk() as $profile) {
    echo "{$profile->symbol}: {$profile->companyName}\n";
}

// End of day bulk quotes
$date = new DateTimeImmutable('2024-01-15');
foreach ($fmpClient->eodBulkQuotes($date) as $quote) {
    echo "{$quote->symbol}: Close ${$quote->close}\n";
}

// Bulk financial statements
foreach ($fmpClient->incomeStatementBulk('2023') as $statement) {
    echo "{$statement->symbol}: Revenue {$statement->revenue}\n";
}
```

### Calendar Data

```php
use DateTimeImmutable;
use Psr\Log\NullLogger;

$from = new DateTimeImmutable('2024-01-01');
$to = new DateTimeImmutable('2024-01-31');
$logger = new NullLogger();

// Earnings calendar (automatically paginated)
foreach ($fmpClient->earningsCalendar($from, $to, $logger) as $earning) {
    echo "Date: {$earning->date}\n";
    echo "Symbol: {$earning->symbol}\n";
    echo "EPS Estimate: {$earning->epsEstimate}\n";
}

// Dividends calendar
foreach ($fmpClient->dividendsCalendar($from, $to, $logger) as $dividend) {
    echo "{$dividend->symbol}: {$dividend->dividend} on {$dividend->date}\n";
}

// Stock splits calendar
foreach ($fmpClient->splitsCalendar($from, $to, $logger) as $split) {
    echo "{$split->symbol}: {$split->numerator}:{$split->denominator} on {$split->date}\n";
}
```

### Historical Data

```php
use DateTimeImmutable;
use Shredio\FmpClient\Enum\TimeInterval;

$from = new DateTimeImmutable('2024-01-01');
$to = new DateTimeImmutable('2024-01-31');

// Historical end-of-day prices
foreach ($fmpClient->historicalPriceEod('AAPL', $from, $to) as $price) {
    echo "Date: {$price->date}, Close: ${$price->close}\n";
}

// Historical chart data with intervals
foreach ($fmpClient->historicalChart('AAPL', TimeInterval::FiveMin, $from, $to) as $data) {
    echo "Time: {$data->date}, Price: ${$data->close}\n";
}
```

### Financial Metrics and Ratios

```php
use Shredio\FmpClient\Enum\PeriodQuery;

// Key metrics
foreach ($fmpClient->keyMetrics('AAPL', 10, PeriodQuery::Annual) as $metrics) {
    echo "Date: {$metrics->date}\n";
    echo "P/E Ratio: {$metrics->peRatio}\n";
    echo "ROE: {$metrics->roe}\n";
}

// TTM (Trailing Twelve Months) metrics
foreach ($fmpClient->keyMetricsTtm('AAPL') as $metrics) {
    echo "Market Cap: {$metrics->marketCapTtm}\n";
    echo "Revenue TTM: {$metrics->revenueTtm}\n";
}

// Financial ratios
foreach ($fmpClient->ratios('AAPL', 5, PeriodQuery::Quarterly) as $ratios) {
    echo "Current Ratio: {$ratios->currentRatio}\n";
    echo "Debt to Equity: {$ratios->debtEquityRatio}\n";
}
```

### Asynchronous Operations

```php
// Execute multiple requests concurrently
$companyProfilePromise = $fmpClient->promise(fn() => 
    iterator_to_array($fmpClient->companyProfile('AAPL'))
);

$dividendsPromise = $fmpClient->promise(fn() => 
    iterator_to_array($fmpClient->dividends('AAPL'))
);

$keyMetricsPromise = $fmpClient->promise(fn() => 
    iterator_to_array($fmpClient->keyMetrics('AAPL'))
);

// Wait for all promises to complete
$profiles = $companyProfilePromise->wait();
$dividends = $dividendsPromise->wait();  
$metrics = $keyMetricsPromise->wait();

echo "Loaded data for: " . $profiles[0]->companyName . "\n";
echo "Dividend count: " . count($dividends) . "\n";
echo "Metrics count: " . count($metrics) . "\n";
```

## Available Endpoints

### Company & Stock Data
- `stockList()` - List all available stocks
- `companyProfile(string $symbols)` - Company profile information
- `companyProfileBulk()` - Bulk company profiles (streaming)
- `getSharesFloat(string $symbol)` - Shares float information (free float, float shares, outstanding shares)
- `availableExchanges()` - Available stock exchanges
- `getAllExchangeMarketHours()` - Market hours for all exchanges

### Financial Statements  
- `balanceSheetStatement(string $symbol)` - Balance sheet data
- `balanceSheetStatementBulk(string $year, Period $period)` - Bulk balance sheets
- `incomeStatement(string $symbol)` - Income statement data
- `incomeStatementBulk(string $year, Period $period)` - Bulk income statements
- `cashFlowStatement(string $symbol)` - Cash flow data
- `cashFlowStatementBulk(string $year, Period $period)` - Bulk cash flow statements
- `latestFinancialStatements(int $page, int $limit)` - Latest financial statements

### Market Data & Quotes
- `eodBulkQuotes(DateTimeImmutable $date)` - End of day bulk quotes
- `batchExchangeQuote(string $exchange)` - Exchange quotes
- `batchExchangeQuoteDetailed(string $exchange)` - Detailed exchange quotes
- `batchForexQuotes()` - Forex currency quotes

### Historical Data
- `historicalPriceEod(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to)` - Historical prices
- `historicalChart(string $symbol, TimeInterval $interval, DateTimeImmutable $from, DateTimeImmutable $to)` - Historical chart data

### Analytics & Metrics
- `keyMetrics(string $symbol, int $limit, PeriodQuery $period)` - Key financial metrics
- `keyMetricsTtm(string $symbol)` - TTM key metrics  
- `keyMetricsTtmBulk()` - Bulk TTM key metrics
- `ratios(string $symbol, int $limit, PeriodQuery $period)` - Financial ratios
- `ratiosTtm(string $symbol)` - TTM ratios
- `ratiosTtmBulk()` - Bulk TTM ratios
- `financialScores(string $symbol)` - Financial scores
- `scoresBulk()` - Bulk financial scores
- `getAnalystEstimates(string $symbol, string $period, int $page, int $limit)` - Analyst estimates

### Calendar Events
- `earningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Earnings calendar
- `dividendsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Dividends calendar
- `splitsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Stock splits calendar
- `dividends(string $symbol)` - Company dividend history

### Other Data
- `cryptocurrencyList()` - Available cryptocurrencies
- `indexList()` - Available market indices
- `financialStatementSymbolList()` - Symbols with financial statements

## Data Types

All API responses are mapped to strongly-typed readonly payload classes with comprehensive type annotations. Each payload class includes a `toArray()` method that returns all properties as an associative array.

Example payload structure:
```php
// CompanyProfile payload
readonly class CompanyProfile {
    public function __construct(
        public string $symbol,
        public ?float $price = null,
        public ?int $marketCap = null,
        public ?string $companyName = null,
        // ... more properties
    ) {}
    
    /**
     * @return array{symbol: string, price: float|null, marketCap: int|null, companyName: string|null, ...}
     */
    public function toArray(): array {
        // Returns all properties as associative array
    }
}
```

## Testing

Run the test suite:

```bash
composer test
```

Run static analysis:

```bash  
composer phpstan
```

The library includes comprehensive tests with real API response fixtures and uses MockHttpClient for reliable testing without API dependencies.

## Architecture

The library follows these key design principles:

- **Memory Efficiency**: Uses streaming parsers (JsonMachine, League CSV) to handle large responses without loading entire datasets into memory
- **Async Processing**: Leverages PHP Fibers through FmpPromise for concurrent request handling
- **Immutable Data**: All payload objects are readonly and immutable
- **Strong Typing**: Comprehensive type hints throughout the codebase
- **Error Resilience**: Graceful handling of malformed API responses with optional strict validation

### Core Components

- **FmpClient** - Main entry point with all API methods
- **FmpPromise** - Async operations using PHP Fibers
- **LargeResponseParser** - Memory-efficient streaming parser
- **FmpPayloadMapper** - Maps API responses to typed objects
- **FmpValidator** - Data validation layer

## Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for new functionality
4. Ensure all tests pass: `composer test`
5. Run static analysis: `composer phpstan`
6. Submit a pull request

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Financial Modeling Prep API

This library provides a PHP interface to the [Financial Modeling Prep API](https://financialmodelingprep.com/). You'll need an API key from FMP to use this library. Visit their website to sign up and get your API key.

## Links

- [Financial Modeling Prep API Documentation](https://financialmodelingprep.com/developer/docs)
- [PHP 8.3 Documentation](https://www.php.net/releases/8.3/en.php)
- [Symfony HTTP Client](https://symfony.com/doc/current/http_client.html)
