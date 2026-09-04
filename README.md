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
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\HttpClient;

// Initialize the client
$httpClient = HttpClient::create();
$fmpClient = new SymfonyFmpClient($httpClient, 'your-api-key-here');

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
use Shredio\FmpClient\Enum\PeriodQuery;

// Get available exchanges
foreach ($fmpClient->availableExchanges() as $exchange) {
    echo "{$exchange->name} ({$exchange->code})\n";
}

// Get company financials
foreach ($fmpClient->balanceSheetStatement('AAPL') as $statement) {
    echo "Date: {$statement->date}\n";
    echo "Total Assets: {$statement->totalAssets}\n";
}

// Get financial statement growth metrics
foreach ($fmpClient->incomeStatementGrowth('AAPL', limit: 5, period: PeriodQuery::Quarter) as $growth) {
    echo "Date: {$growth->date}\n";
    echo "Revenue Growth: {$growth->growthRevenue}\n";
    echo "Net Income Growth: {$growth->growthNetIncome}\n";
}

// Get revenue breakdown by product line
foreach ($fmpClient->revenueProductSegmentation('AAPL') as $segmentation) {
    echo "Fiscal Year: {$segmentation->fiscalYear}\n";

    foreach ($segmentation->data as $product => $revenue) {
        echo "  {$product}: {$revenue} {$segmentation->reportedCurrency}\n";
    }
}

// Get dividend history
foreach ($fmpClient->dividends('AAPL') as $dividend) {
    echo "Date: {$dividend->date}, Amount: {$dividend->dividend}\n";
}

// Get a quote for a single symbol
$quote = $fmpClient->quote('AAPL');
if ($quote !== null) {
    echo "Price: {$quote->price} ({$quote->changePercentage}%)\n";
    echo "Market Cap: {$quote->marketCap}\n";
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

// Detailed earnings calendar with report time (bmo/amc), period ending and confirmation flag (automatically paginated)
foreach ($fmpClient->detailedEarningsCalendar($from, $to, $logger) as $earning) {
    echo "{$earning->symbol} - {$earning->date} [{$earning->time}]\n";
    echo "  EPS: {$earning->epsActual} (est. {$earning->epsEstimated}), confirmed: {$earning->confirmed}\n";
}

// Dividends calendar
foreach ($fmpClient->dividendsCalendar($from, $to, $logger) as $dividend) {
    echo "{$dividend->symbol}: {$dividend->dividend} on {$dividend->date}\n";
}

// Historical stock splits for a single symbol
foreach ($fmpClient->splits('AAPL') as $split) {
    echo "{$split->symbol}: {$split->numerator}:{$split->denominator} on {$split->date}\n";
}

// Stock splits calendar
foreach ($fmpClient->splitsCalendar($from, $to, $logger) as $split) {
    echo "{$split->symbol}: {$split->numerator}:{$split->denominator} on {$split->date}\n";
}

// Economic calendar (macroeconomic events; automatically paginated for large date ranges)
foreach ($fmpClient->economicCalendar($from, $to, $logger) as $event) {
    echo "{$event->date} [{$event->country}] {$event->event} (impact: {$event->impact})\n";
    echo "  Previous: {$event->previous}, Estimate: {$event->estimate}, Actual: {$event->actual}\n";
}
```

### Historical Data

```php
use DateTimeImmutable;
use Shredio\FmpClient\Enum\TimeInterval;

$from = new DateTimeImmutable('2024-01-01');
$to = new DateTimeImmutable('2024-01-31');

// Historical end-of-day prices (also works with forex pairs, e.g. 'EURUSD')
// Ranges exceeding the API limit of 5000 records per request are fetched in multiple requests automatically
foreach ($fmpClient->historicalPriceEod('AAPL', $from, $to) as $price) {
    echo "Date: {$price->date}, Close: ${$price->close}\n";
}

// Historical end-of-day prices, lightweight (only date, price and volume)
foreach ($fmpClient->historicalPriceEodLight('AAPL', $from, $to) as $price) {
    echo "Date: {$price->date}, Price: ${$price->price}\n";
}

// Historical end-of-day prices without split adjustments
foreach ($fmpClient->historicalPriceEodNonSplitAdjusted('AAPL', $from, $to) as $price) {
    echo "Date: {$price->date}, Close: ${$price->adjClose}\n";
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

// Market risk premium by country
foreach ($fmpClient->marketRiskPremium() as $premium) {
    echo "Country: {$premium->country} ({$premium->continent})\n";
    echo "Country Risk Premium: {$premium->countryRiskPremium}%\n";
    echo "Total Equity Risk Premium: {$premium->totalEquityRiskPremium}%\n";
}

// Trailing twelve months income statements
foreach ($fmpClient->incomeStatementTtm('AAPL', limit: 4) as $statement) {
    echo "Date: {$statement->date} ({$statement->period->value})\n";
    echo "TTM Revenue: {$statement->revenue}\n";
}

// Valuation and analyst sentiment
$priceTarget = $fmpClient->priceTargetConsensus('AAPL');
if ($priceTarget !== null) {
    echo "Consensus Target: {$priceTarget->targetConsensus}\n";
}

$dcf = $fmpClient->discountedCashFlow('AAPL');
if ($dcf !== null) {
    echo "DCF: {$dcf->dcf}, Stock Price: {$dcf->stockPrice}\n";
}

$grades = $fmpClient->gradesConsensus('AAPL');
if ($grades !== null) {
    echo "Consensus: {$grades->consensus} (buy: {$grades->buy}, hold: {$grades->hold}, sell: {$grades->sell})\n";
}

// Rating changes
foreach ($fmpClient->grades('AAPL', limit: 10) as $grade) {
    echo "{$grade->date} {$grade->gradingCompany}: {$grade->previousGrade} -> {$grade->newGrade} ({$grade->action})\n";
}

// US Treasury rates
foreach ($fmpClient->treasuryRates() as $rate) {
    echo "Date: {$rate->date}\n";
    echo "1 Month: {$rate->month1}%, 3 Month: {$rate->month3}%\n";
    echo "1 Year: {$rate->year1}%, 10 Year: {$rate->year10}%\n";
    echo "30 Year: {$rate->year30}%\n";
}
```

### Ownership, Congress Trades and Transcripts

```php
// Insider transactions
foreach ($fmpClient->insiderTrades('AAPL', limit: 20) as $trade) {
    echo "{$trade->transactionDate} {$trade->reportingName} ({$trade->typeOfOwner})\n";
    echo "  {$trade->transactionType}: {$trade->securitiesTransacted} @ {$trade->price}\n";
}

// Trades disclosed by U.S. senators
foreach ($fmpClient->senateTrades('AAPL', limit: 20) as $trade) {
    echo "{$trade->transactionDate} {$trade->firstName} {$trade->lastName} ({$trade->district})\n";
    echo "  {$trade->type}: {$trade->amount}\n";
}

// Upcoming and historical earnings reports for a single symbol
foreach ($fmpClient->earnings('AAPL', limit: 8) as $report) {
    echo "{$report->date}: actual {$report->epsActual}, estimated {$report->epsEstimated}\n";
}

// The latest earning call transcript
foreach ($fmpClient->earningCallTranscriptDates('AAPL') as $date) {
    $transcript = $fmpClient->earningCallTranscript('AAPL', $date->fiscalYear, $date->quarter);
    if ($transcript !== null) {
        echo "{$transcript->date} {$transcript->period->value} {$transcript->year}\n";
        echo substr($transcript->content, 0, 200) . "\n";
    }

    break;
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
- `peersBulk()` - Bulk stock peers (companies grouped by industry/sector similarity)
- `sharesFloat(string $symbol)` - Shares float information (free float, float shares, outstanding shares)
- `availableExchanges()` - Available stock exchanges
- `allExchangeMarketHours()` - Market hours for all exchanges
- `holidaysByExchange(string $exchange)` - Market holidays and early-close days for an exchange

### Financial Statements
- `balanceSheetStatement(string $symbol)` - Balance sheet data
- `balanceSheetStatementBulk(string $year, Period $period)` - Bulk balance sheets
- `balanceSheetStatementGrowth(string $symbol, int|null $limit, PeriodQuery|null $period)` - Balance sheet growth metrics
- `balanceSheetStatementGrowthBulk(int $year, Period $period)` - Bulk balance sheet growth metrics
- `incomeStatement(string $symbol)` - Income statement data
- `incomeStatementBulk(string $year, Period $period)` - Bulk income statements
- `incomeStatementTtm(string $symbol, int|null $limit)` - Historical series of trailing twelve months income statements
- `incomeStatementGrowth(string $symbol, int|null $limit, PeriodQuery|null $period)` - Income statement growth metrics
- `incomeStatementGrowthBulk(int $year, Period $period)` - Bulk income statement growth metrics
- `cashFlowStatement(string $symbol)` - Cash flow data
- `cashFlowStatementBulk(string $year, Period $period)` - Bulk cash flow statements
- `cashFlowStatementGrowth(string $symbol, int|null $limit, PeriodQuery|null $period)` - Cash flow growth metrics
- `cashFlowStatementGrowthBulk(int $year, Period $period)` - Bulk cash flow growth metrics
- `revenueProductSegmentation(string $symbol, PeriodQuery $period)` - Revenue breakdown by product line
- `revenueGeographicSegmentation(string $symbol, PeriodQuery $period)` - Revenue breakdown by geographic region
- `latestFinancialStatements(int $page, int $limit)` - Latest financial statements

### Market Data & Quotes
- `quote(string $symbol)` - Full quote for a single symbol (stocks, ETFs, indexes, forex, crypto)
- `eodBulkQuotes(DateTimeImmutable $date)` - End of day bulk quotes
- `batchExchangeQuote(string $exchange)` - Exchange quotes
- `batchExchangeQuoteDetailed(string $exchange)` - Detailed exchange quotes
- `batchForexQuotes()` - Forex currency quotes

### Historical Data
- `historicalPriceEod(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to)` - Historical prices (also works with forex pairs)
- `historicalPriceEodLight(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to)` - Historical prices, lightweight (date, price, volume only)
- `historicalPriceEodNonSplitAdjusted(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to)` - Historical prices without split adjustments
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
- `analystEstimates(string $symbol, string $period, int $page, int $limit)` - Analyst estimates
- `priceTargetConsensus(string $symbol)` - Consensus analyst price target (high, low, consensus, median)
- `discountedCashFlow(string $symbol)` - DCF valuation together with the current stock price
- `gradesConsensus(string $symbol)` - Analyst rating distribution and the resulting consensus rating
- `grades(string $symbol, int|null $limit)` - Individual analyst rating actions (upgrade, downgrade, maintain)
- `marketRiskPremium()` - Market risk premium by country
- `treasuryRates()` - US Treasury rates for various maturities

### Calendar Events
- `earningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Earnings calendar
- `detailedEarningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Earnings calendar with report time (bmo/amc), period ending, fiscal period/year and confirmation flag
- `dividendsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Dividends calendar
- `splitsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Stock splits calendar
- `economicCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger)` - Economic data releases calendar
- `dividends(string $symbol)` - Company dividend history
- `earnings(string $symbol, int|null $limit)` - Upcoming and historical earnings reports for a single symbol
- `splits(string $symbol)` - Company stock split history

### Ownership & Insider Activity
- `insiderTrades(string $symbol, int $page, int|null $limit)` - Insider transactions reported on SEC forms 3, 4 and 5
- `senateTrades(string $symbol, int|null $limit)` - Trades disclosed by U.S. senators

### Earning Call Transcripts
- `earningCallTranscriptDates(string $symbol)` - Quarters with an available transcript
- `earningCallTranscript(string $symbol, int $year, int $quarter)` - Full transcript of a single earning call

### Search
- `searchIsin(string $isin)` - Search for stocks by ISIN code

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
