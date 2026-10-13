<?php declare(strict_types = 1);

namespace Shredio\FmpClient;

use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Shredio\FmpClient\Config\HttpClientRetryConfiguration;
use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
use Shredio\FmpClient\Enum\StockIndex;
use Shredio\FmpClient\Enum\TimeInterval;
use Shredio\FmpClient\Payload\ActivelyTrading;
use Shredio\FmpClient\Payload\AnalystEstimate;
use Shredio\FmpClient\Payload\AvailableExchange;
use Shredio\FmpClient\Payload\BalanceSheetStatement;
use Shredio\FmpClient\Payload\BalanceSheetStatementGrowth;
use Shredio\FmpClient\Payload\BalanceSheetStatementGrowthBulk;
use Shredio\FmpClient\Payload\BatchExchangeDetailedQuote;
use Shredio\FmpClient\Payload\BatchExchangeQuote;
use Shredio\FmpClient\Payload\BatchForexQuote;
use Shredio\FmpClient\Payload\CashFlowStatement;
use Shredio\FmpClient\Payload\CashFlowStatementGrowth;
use Shredio\FmpClient\Payload\CashFlowStatementGrowthBulk;
use Shredio\FmpClient\Payload\CompanyProfile;
use Shredio\FmpClient\Payload\Cryptocurrency;
use Shredio\FmpClient\Payload\DelistedCompany;
use Shredio\FmpClient\Payload\DiscountedCashFlow;
use Shredio\FmpClient\Payload\DetailedEarningsCalendarItem;
use Shredio\FmpClient\Payload\Dividend;
use Shredio\FmpClient\Payload\EarningCallTranscript;
use Shredio\FmpClient\Payload\EarningCallTranscriptDate;
use Shredio\FmpClient\Payload\EarningsCalendarItem;
use Shredio\FmpClient\Payload\EarningsSurprise;
use Shredio\FmpClient\Payload\EconomicCalendarItem;
use Shredio\FmpClient\Payload\EodQuote;
use Shredio\FmpClient\Payload\Etf;
use Shredio\FmpClient\Payload\EtfCountryWeighting;
use Shredio\FmpClient\Payload\EtfHolding;
use Shredio\FmpClient\Payload\EtfHoldingBulk;
use Shredio\FmpClient\Payload\EtfInfo;
use Shredio\FmpClient\Payload\EtfSectorWeighting;
use Shredio\FmpClient\Payload\ExchangeMarketHours;
use Shredio\FmpClient\Payload\FinancialGrowth;
use Shredio\FmpClient\Payload\FinancialStatementSymbol;
use Shredio\FmpClient\Payload\Grade;
use Shredio\FmpClient\Payload\GradeNews;
use Shredio\FmpClient\Payload\GradesConsensus;
use Shredio\FmpClient\Payload\GradesHistorical;
use Shredio\FmpClient\Payload\HistoricalChart;
use Shredio\FmpClient\Payload\HistoricalIndexConstituent;
use Shredio\FmpClient\Payload\HistoricalPriceEod;
use Shredio\FmpClient\Payload\HistoricalPriceEodLight;
use Shredio\FmpClient\Payload\HistoricalPriceEodNonSplitAdjusted;
use Shredio\FmpClient\Payload\HolidayByExchange;
use Shredio\FmpClient\Payload\HouseTrade;
use Shredio\FmpClient\Payload\IncomeStatement;
use Shredio\FmpClient\Payload\IncomeStatementGrowth;
use Shredio\FmpClient\Payload\IncomeStatementGrowthBulk;
use Shredio\FmpClient\Payload\Index;
use Shredio\FmpClient\Payload\IndexConstituent;
use Shredio\FmpClient\Payload\InsiderTrade;
use Shredio\FmpClient\Payload\InsiderTradeStatistics;
use Shredio\FmpClient\Payload\InstitutionalHolder;
use Shredio\FmpClient\Payload\InstitutionalOwnershipFiling;
use Shredio\FmpClient\Payload\InstitutionalPositionsSummary;
use Shredio\FmpClient\Payload\IpoCalendarItem;
use Shredio\FmpClient\Payload\IsinSearchResult;
use Shredio\FmpClient\Payload\KeyExecutive;
use Shredio\FmpClient\Payload\KeyMetrics;
use Shredio\FmpClient\Payload\KeyMetricsTtm;
use Shredio\FmpClient\Payload\LatestEarningCallTranscript;
use Shredio\FmpClient\Payload\LatestFinancialStatement;
use Shredio\FmpClient\Payload\LatestHouseTrade;
use Shredio\FmpClient\Payload\LatestSenateTrade;
use Shredio\FmpClient\Payload\MarketCapitalization;
use Shredio\FmpClient\Payload\MarketRiskPremium;
use Shredio\FmpClient\Payload\PeersBulk;
use Shredio\FmpClient\Payload\PressRelease;
use Shredio\FmpClient\Payload\PriceTargetConsensus;
use Shredio\FmpClient\Payload\PriceTargetNews;
use Shredio\FmpClient\Payload\PriceTargetSummary;
use Shredio\FmpClient\Payload\Quote;
use Shredio\FmpClient\Payload\Rating;
use Shredio\FmpClient\Payload\Ratios;
use Shredio\FmpClient\Payload\RatiosTtm;
use Shredio\FmpClient\Payload\RevenueGeographicSegmentation;
use Shredio\FmpClient\Payload\RevenueProductSegmentation;
use Shredio\FmpClient\Payload\Scores;
use Shredio\FmpClient\Payload\SenateTrade;
use Shredio\FmpClient\Payload\SharesFloat;
use Shredio\FmpClient\Payload\Stock;
use Shredio\FmpClient\Payload\StockSplit;
use Shredio\FmpClient\Payload\StockNews;
use Shredio\FmpClient\Payload\SymbolChange;
use Shredio\FmpClient\Payload\TreasuryRate;
use Shredio\FmpClient\Promise\FmpPromise;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface FmpClient
{

	public const int MaxDividendsLimit = 1000;
	public const int MaxStockNewsLimit = 250;
	public const int MaxHistoricalPriceEodRecordsPerRequest = 5000;
	public const int MaxHistoricalMarketCapitalizationRecordsPerRequest = 5000;
	public const int MaxBatchSymbolsPerRequest = 1000;

	public function withStrictMode(bool $strictMode): FmpClient;

	public function withRetryConfiguration(HttpClientRetryConfiguration $config): FmpClient;

	/**
	 * Returns a client configured for background tasks (cron jobs, queues) with extended retry settings and more host connections.
	 */
	public function forBackgroundProcessing(): FmpClient;

	/**
	 * @template TReturn
	 * @param callable(): TReturn $fn
	 * @return FmpPromise<TReturn>
	 */
	public function promise(callable $fn): FmpPromise;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/available-exchanges
	 * @return iterable<int, AvailableExchange>
	 */
	public function availableExchanges(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/all-exchange-market-hours
	 * @return iterable<int, ExchangeMarketHours>
	 */
	public function allExchangeMarketHours(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/holidays-by-exchange
	 * @return iterable<int, HolidayByExchange>
	 */
	public function holidaysByExchange(string $exchange): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/market-risk-premium
	 * @return iterable<int, MarketRiskPremium>
	 */
	public function marketRiskPremium(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/treasury-rates
	 * @return iterable<int, TreasuryRate>
	 */
	public function treasuryRates(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/index-list
	 * @return iterable<int, Index>
	 */
	public function indexList(): iterable;

	/**
	 * Current constituents of the S&P 500, the Nasdaq 100 or the Dow Jones Industrial Average.
	 *
	 * @see https://financialmodelingprep.com/stable/sp500-constituent
	 * @see https://financialmodelingprep.com/stable/nasdaq-constituent
	 * @see https://financialmodelingprep.com/stable/dowjones-constituent
	 * @return iterable<int, IndexConstituent>
	 */
	public function indexConstituents(StockIndex $index): iterable;

	/**
	 * Companies added to and removed from the S&P 500, the Nasdaq 100 or the Dow Jones Industrial Average, ordered
	 * from the newest to the oldest change.
	 *
	 * @see https://financialmodelingprep.com/stable/historical-sp500-constituent
	 * @see https://financialmodelingprep.com/stable/historical-nasdaq-constituent
	 * @see https://financialmodelingprep.com/stable/historical-dowjones-constituent
	 * @return iterable<int, HistoricalIndexConstituent>
	 */
	public function historicalIndexConstituents(StockIndex $index): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/cryptocurrency-list
	 * @return iterable<int, Cryptocurrency>
	 */
	public function cryptocurrencyList(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/stock-list
	 * @return iterable<int, Stock>
	 */
	public function stockList(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/actively-trading-list
	 * @return iterable<int, ActivelyTrading>
	 */
	public function activelyTradingList(): iterable;

	/**
	 * Covers US-listed symbols, foreign listings appear only exceptionally.
	 *
	 * @see https://financialmodelingprep.com/stable/symbol-change
	 * @return iterable<int, SymbolChange>
	 */
	public function symbolChangeList(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/search-isin
	 * @return iterable<int, IsinSearchResult>
	 */
	public function searchIsin(string $isin): iterable;

	/**
	 * Covers exchanges worldwide, although the FMP docs flag the endpoint as US only.
	 *
	 * @see https://financialmodelingprep.com/stable/delisted-companies
	 * @return iterable<int, DelistedCompany>
	 */
	public function delistedCompanies(int $limit = 100, int $page = 0): iterable;

	/**
	 * Covers almost exclusively US-listed symbols.
	 *
	 * @see https://financialmodelingprep.com/stable/news/press-releases-latest
	 * @return iterable<int, PressRelease>
	 */
	public function pressReleasesLatest(int $limit, int $page = 0): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/news/stock-latest
	 * @param int<1, 250> $limit
	 * @return iterable<int, StockNews>
	 */
	public function stockNewsLatest(int $limit, int $page = 0): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/profile
	 */
	public function companyProfile(string $symbol): ?CompanyProfile;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/profile-bulk
	 * @return iterable<int, CompanyProfile>
	 */
	public function companyProfileBulk(): iterable;

	/**
	 * Executives of a company with their title, compensation, gender and year of birth.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/key-executives
	 * @return iterable<int, KeyExecutive>
	 */
	public function keyExecutives(string $symbol): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/shares-float
	 */
	public function sharesFloat(string $symbol): ?SharesFloat;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/shares-float-all
	 * @param int<1, 5000> $limit
	 * @return iterable<int, SharesFloat>
	 */
	public function sharesFloatAll(int $limit = 1000, int $page = 0): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-bulk
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatementBulk(int $year, Period $period = Period::FY): iterable;

	/**
	 * Returns the balance sheets as of the end of every quarter, ordered from the newest to the oldest. A balance sheet
	 * is a snapshot, so every record equals the quarterly balance sheet of the same date.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-ttm
	 * @param int<1, 1000>|null $limit Without a limit the API returns the 5 newest records
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatementTtm(string $symbol, ?int $limit = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/analyst-estimates
	 * @return iterable<int, AnalystEstimate>
	 */
	public function analystEstimates(string $symbol, string $period = 'annual', int $page = 0, int $limit = 6): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/dividends-calendar
	 * @return iterable<int, Dividend>
	 */
	public function dividendsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/dividends
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, Dividend>
	 */
	public function dividends(string $symbol, ?int $limit = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/earnings-calendar
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/earnings-calendar?includeReportTimes=true
	 * @return iterable<int, DetailedEarningsCalendarItem>
	 */
	public function detailedEarningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * Returns both upcoming and historical earnings reports for a single symbol, ordered from the newest to the oldest.
	 * Upcoming reports have null actual values.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/earnings
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earnings(string $symbol, ?int $limit = null): iterable;

	/**
	 * Reported and estimated earnings per share of every earnings report dated in the given year.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/earnings-surprises-bulk
	 * @return iterable<int, EarningsSurprise>
	 */
	public function earningsSurprisesBulk(int $year): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/splits
	 * @return iterable<int, StockSplit>
	 */
	public function splits(string $symbol): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/splits-calendar
	 * @return iterable<int, StockSplit>
	 */
	public function splitsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * Upcoming and past initial public offerings.
	 * Covers exchanges worldwide for the past, as the FMP docs flag it, but the records dated April 2026 or later are
	 * IPOs on NYSE and NASDAQ only (observed on 2026-10-13).
	 *
	 * @see https://financialmodelingprep.com/stable/ipos-calendar
	 * @return iterable<int, IpoCalendarItem>
	 */
	public function iposCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/economic-calendar
	 * @return iterable<int, EconomicCalendarItem>
	 */
	public function economicCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/latest-financial-statements
	 * @param int<0, 100> $page
	 * @param int<1, 250> $limit
	 * @return iterable<int, LatestFinancialStatement>
	 */
	public function latestFinancialStatements(int $page = 0, int $limit = 250): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/income-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/income-statement-bulk
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatementBulk(int $year, Period $period = Period::FY): iterable;

	/**
	 * Returns a historical series of trailing twelve months income statements, ordered from the newest to the oldest.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/income-statement-ttm
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatementTtm(string $symbol, ?int $limit = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-bulk
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatementBulk(int $year, Period $period = Period::FY): iterable;

	/**
	 * Returns a historical series of trailing twelve months cash flow statements, ordered from the newest to the
	 * oldest.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-ttm
	 * @param int<1, 1000>|null $limit Without a limit the API returns the 5 newest records
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatementTtm(string $symbol, ?int $limit = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/api/v3/income-statement-growth/{symbol}
	 * @return iterable<int, IncomeStatementGrowth>
	 */
	public function incomeStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/api/v3/balance-sheet-statement-growth/{symbol}
	 * @return iterable<int, BalanceSheetStatementGrowth>
	 */
	public function balanceSheetStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/api/v3/cash-flow-statement-growth/{symbol}
	 * @return iterable<int, CashFlowStatementGrowth>
	 */
	public function cashFlowStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/income-statement-growth-bulk
	 * @return iterable<int, IncomeStatementGrowthBulk>
	 */
	public function incomeStatementGrowthBulk(int $year, Period $period): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-growth-bulk
	 * @return iterable<int, BalanceSheetStatementGrowthBulk>
	 */
	public function balanceSheetStatementGrowthBulk(int $year, Period $period): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-growth-bulk
	 * @return iterable<int, CashFlowStatementGrowthBulk>
	 */
	public function cashFlowStatementGrowthBulk(int $year, Period $period): iterable;

	/**
	 * Growth of the key figures of the financial statements together with the growth per share over three, five and
	 * ten years, ordered from the newest to the oldest period.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/financial-growth
	 * @param int<1, 1000>|null $limit Without a limit the API returns the 5 newest records
	 * @param PeriodQuery|null $period Without a period the API returns fiscal years
	 * @return iterable<int, FinancialGrowth>
	 */
	public function financialGrowth(string $symbol, ?int $limit = null, ?PeriodQuery $period = null): iterable;

	/**
	 * Complete only for US-listed symbols (with gaps for foreign issuers). A foreign listing of a US company returns
	 * nothing or an incomplete, mostly outdated subset, so request the US listing of the company instead. Foreign
	 * listings of foreign companies return next to no data.
	 *
	 * @see https://financialmodelingprep.com/stable/revenue-product-segmentation
	 * @return iterable<int, RevenueProductSegmentation>
	 */
	public function revenueProductSegmentation(string $symbol, PeriodQuery $period = PeriodQuery::Annual): iterable;

	/**
	 * Complete only for US-listed symbols (with gaps for foreign issuers). A foreign listing of a US company returns
	 * nothing or an incomplete, mostly outdated subset, so request the US listing of the company instead. Foreign
	 * listings of foreign companies return next to no data.
	 *
	 * @see https://financialmodelingprep.com/stable/revenue-geographic-segmentation
	 * @return iterable<int, RevenueGeographicSegmentation>
	 */
	public function revenueGeographicSegmentation(string $symbol, PeriodQuery $period = PeriodQuery::Annual): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/eod-bulk
	 * @return iterable<int, EodQuote>
	 */
	public function eodBulkQuotes(DateTimeImmutable $date): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/financial-statement-symbol-list
	 * @return iterable<int, FinancialStatementSymbol>
	 */
	public function financialStatementSymbolList(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/batch-exchange-quote
	 * @return iterable<int, BatchExchangeQuote>
	 */
	public function batchExchangeQuote(string $exchange): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/batch-exchange-quote
	 * @return iterable<int, BatchExchangeDetailedQuote>
	 */
	public function batchExchangeQuoteDetailed(string $exchange): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/batch-forex-quotes
	 * @return iterable<int, BatchForexQuote>
	 */
	public function batchForexQuotes(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/quote
	 */
	public function quote(string $symbol): ?Quote;

	/**
	 * Quotes of several symbols at once, stocks, ETFs, indexes, cryptocurrencies, forex pairs and commodities can be
	 * mixed. A symbol unknown to the API is left out of the result. The API answers at most 1000 symbols per request,
	 * a longer list is split into several requests automatically.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/batch-quote
	 * @param list<non-empty-string> $symbols
	 * @return iterable<int, Quote>
	 */
	public function batchQuote(array $symbols): iterable;

	/**
	 * Ranges exceeding the API limit of 5000 records per request are fetched in multiple requests automatically.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/historical-price-eod/full
	 * @return iterable<int, HistoricalPriceEod>
	 */
	public function historicalPriceEod(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable;

	/**
	 * Ranges exceeding the API limit of 5000 records per request are fetched in multiple requests automatically.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/historical-price-eod/non-split-adjusted
	 * @return iterable<int, HistoricalPriceEodNonSplitAdjusted>
	 */
	public function historicalPriceEodNonSplitAdjusted(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable;

	/**
	 * Ranges exceeding the API limit of 5000 records per request are fetched in multiple requests automatically.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/historical-price-eod/light
	 * @return iterable<int, HistoricalPriceEodLight>
	 */
	public function historicalPriceEodLight(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/historical-chart
	 * @return iterable<int, HistoricalChart>
	 */
	public function historicalChart(string $symbol, TimeInterval $interval, DateTimeImmutable $from, DateTimeImmutable $to): iterable;

	/**
	 * Daily market capitalization, ordered from the newest to the oldest day. Ranges exceeding the API limit of 5000
	 * records per request are fetched in multiple requests automatically.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/historical-market-capitalization
	 * @return iterable<int, MarketCapitalization>
	 */
	public function historicalMarketCapitalization(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable;

	/**
	 * Latest market capitalization of several symbols at once. A symbol unknown to the API is left out of the result,
	 * a long list is split into several requests automatically.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/market-capitalization-batch
	 * @param list<non-empty-string> $symbols
	 * @return iterable<int, MarketCapitalization>
	 */
	public function marketCapitalizationBatch(array $symbols): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/key-metrics
	 * @return iterable<int, KeyMetrics>
	 */
	public function keyMetrics(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/key-metrics-ttm
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtm(string $symbol): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/key-metrics-ttm-bulk
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtmBulk(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/ratios
	 * @return iterable<int, Ratios>
	 */
	public function ratios(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/ratios-ttm
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtm(string $symbol): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/ratios-ttm-bulk
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtmBulk(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/financial-scores
	 * @return iterable<int, Scores>
	 */
	public function financialScores(string $symbol): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/scores-bulk
	 * @return iterable<int, Scores>
	 */
	public function scoresBulk(): iterable;

	/**
	 * Rating and scores FMP computes from the fundamentals of every company, it is not an analyst rating.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/rating-bulk
	 * @return iterable<int, Rating>
	 */
	public function ratingBulk(): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/peers-bulk
	 * @return iterable<int, PeersBulk>
	 */
	public function peersBulk(): iterable;

	/**
	 * Analyst price targets aggregated across all covering analysts.
	 * Only available for US-listed symbols with analyst coverage, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/price-target-consensus
	 */
	public function priceTargetConsensus(string $symbol): ?PriceTargetConsensus;

	/**
	 * Number and average of the analyst price targets published in the last month, quarter, year and overall.
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/price-target-summary
	 */
	public function priceTargetSummary(string $symbol): ?PriceTargetSummary;

	/**
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/price-target-summary-bulk
	 * @return iterable<int, PriceTargetSummary>
	 */
	public function priceTargetSummaryBulk(): iterable;

	/**
	 * Individual analyst price targets of a symbol together with the news they were published in, ordered from the
	 * newest to the oldest.
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/price-target-news
	 * @param int<0, max> $page
	 * @param int<1, 100>|null $limit
	 * @return iterable<int, PriceTargetNews>
	 */
	public function priceTargetNews(string $symbol, int $page = 0, ?int $limit = null): iterable;

	/**
	 * Individual analyst price targets across all symbols together with the news they were published in, ordered from
	 * the newest to the oldest.
	 * Covers US-listed symbols only.
	 *
	 * @see https://financialmodelingprep.com/stable/price-target-latest-news
	 * @param int<0, 100> $page
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, PriceTargetNews>
	 */
	public function priceTargetLatestNews(int $page = 0, ?int $limit = null): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/discounted-cash-flow
	 */
	public function discountedCashFlow(string $symbol): ?DiscountedCashFlow;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/dcf-bulk
	 * @return iterable<int, DiscountedCashFlow>
	 */
	public function discountedCashFlowBulk(): iterable;

	/**
	 * Number of analysts per rating bucket together with the resulting consensus rating.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/grades-consensus
	 */
	public function gradesConsensus(string $symbol): ?GradesConsensus;

	/**
	 * Bulk variant of the grades consensus, the API serves it under the upgrades-downgrades name.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/upgrades-downgrades-consensus-bulk
	 * @return iterable<int, GradesConsensus>
	 */
	public function gradesConsensusBulk(): iterable;

	/**
	 * Individual rating actions (upgrade, downgrade, maintain) ordered from the newest to the oldest.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/grades
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, Grade>
	 */
	public function grades(string $symbol, ?int $limit = null): iterable;

	/**
	 * Monthly snapshots of the number of analysts per rating bucket, ordered from the newest to the oldest.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/grades-historical
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, GradesHistorical>
	 */
	public function gradesHistorical(string $symbol, ?int $limit = null): iterable;

	/**
	 * Rating actions of a symbol together with the news they were published in, ordered from the newest to the oldest.
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/grades-news
	 * @param int<0, max> $page
	 * @param int<1, 100>|null $limit Without a limit the API returns a single record
	 * @return iterable<int, GradeNews>
	 */
	public function gradesNews(string $symbol, int $page = 0, ?int $limit = null): iterable;

	/**
	 * Rating actions across all symbols together with the news they were published in, ordered from the newest to the
	 * oldest.
	 * Covers US-listed symbols only.
	 *
	 * @see https://financialmodelingprep.com/stable/grades-latest-news
	 * @param int<0, 100> $page
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, GradeNews>
	 */
	public function gradesLatestNews(int $page = 0, ?int $limit = null): iterable;

	/**
	 * Insider transactions reported to the SEC on forms 3, 4 and 5, ordered from the newest to the oldest.
	 * Only available for US-listed symbols whose insiders file with the SEC, foreign issuers listed in the US return
	 * data from 2026 on at best.
	 *
	 * @see https://financialmodelingprep.com/stable/insider-trading/search
	 * @param int<0, max> $page
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, InsiderTrade>
	 */
	public function insiderTrades(string $symbol, int $page = 0, ?int $limit = null): iterable;

	/**
	 * Insider transactions aggregated per quarter, ordered from the newest to the oldest quarter.
	 * Only available for US-listed symbols whose insiders file with the SEC, foreign issuers listed in the US return
	 * data from 2026 on at best.
	 *
	 * @see https://financialmodelingprep.com/stable/insider-trading/statistics
	 * @return iterable<int, InsiderTradeStatistics>
	 */
	public function insiderTradeStatistics(string $symbol): iterable;

	/**
	 * Insider transactions across all symbols, ordered from the newest to the oldest filing.
	 * Covers US-listed symbols only.
	 *
	 * @see https://financialmodelingprep.com/stable/insider-trading/latest
	 * @param int<0, 100> $page
	 * @param int<1, 1000>|null $limit
	 * @param DateTimeImmutable|null $from Return only the transactions filed on this date or later
	 * @return iterable<int, InsiderTrade>
	 */
	public function insiderTradesLatest(int $page = 0, ?int $limit = null, ?DateTimeImmutable $from = null): iterable;

	/**
	 * Trades disclosed by U.S. senators, ordered from the newest to the oldest disclosure.
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/senate-trades
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, SenateTrade>
	 */
	public function senateTrades(string $symbol, ?int $limit = null): iterable;

	/**
	 * Trades disclosed by U.S. senators across all assets, ordered from the newest to the oldest disclosure.
	 * Covers US-listed symbols only, an asset without a ticker symbol has an empty symbol.
	 *
	 * @see https://financialmodelingprep.com/stable/senate-latest
	 * @param int<0, 100> $page
	 * @param int<1, 250>|null $limit
	 * @return iterable<int, LatestSenateTrade>
	 */
	public function senateTradesLatest(int $page = 0, ?int $limit = null): iterable;

	/**
	 * Trades disclosed by members of the U.S. House of Representatives, ordered from the newest to the oldest
	 * disclosure.
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/house-trades
	 * @param int<0, 100> $page
	 * @param int<1, 250>|null $limit Without a limit the API returns 100 records
	 * @return iterable<int, HouseTrade>
	 */
	public function houseTrades(string $symbol, int $page = 0, ?int $limit = null): iterable;

	/**
	 * Trades disclosed by members of the U.S. House of Representatives across all assets, ordered from the newest to
	 * the oldest disclosure.
	 * Covers US-listed symbols only, an asset without a ticker symbol has an empty symbol.
	 *
	 * @see https://financialmodelingprep.com/stable/house-latest
	 * @param int<0, 100> $page
	 * @param int<1, 250>|null $limit Without a limit the API returns 100 records
	 * @return iterable<int, LatestHouseTrade>
	 */
	public function houseTradesLatest(int $page = 0, ?int $limit = null): iterable;

	/**
	 * Institutional ownership of a symbol aggregated from the 13F filings of a single calendar quarter.
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/institutional-ownership/symbol-positions-summary
	 * @param int<1, 4> $quarter
	 */
	public function institutionalPositionsSummary(string $symbol, int $year, int $quarter): ?InstitutionalPositionsSummary;

	/**
	 * Institutional holders of a symbol from the 13F filings of a single calendar quarter, ordered from the largest
	 * number of shares held to the smallest.
	 * Only available for US-listed symbols, including ADRs of foreign companies.
	 *
	 * @see https://financialmodelingprep.com/stable/institutional-ownership/extract-analytics/holder
	 * @param int<1, 4> $quarter
	 * @param int<0, max> $page
	 * @param int<1, 100>|null $limit
	 * @return iterable<int, InstitutionalHolder>
	 */
	public function institutionalHolders(string $symbol, int $year, int $quarter, int $page = 0, ?int $limit = null): iterable;

	/**
	 * 13F filings of institutional investors filed with the SEC, ordered from the newest to the oldest. The filings
	 * are not specific to a symbol.
	 *
	 * @see https://financialmodelingprep.com/stable/institutional-ownership/latest
	 * @param int<0, 100> $page
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, InstitutionalOwnershipFiling>
	 */
	public function institutionalOwnershipLatest(int $page = 0, ?int $limit = null): iterable;

	/**
	 * Quarters for which an earning call transcript is available, ordered from the newest to the oldest.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/earning-call-transcript-dates
	 * @return iterable<int, EarningCallTranscriptDate>
	 */
	public function earningCallTranscriptDates(string $symbol): iterable;

	/**
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/earning-call-transcript
	 * @param int<1, 4> $quarter
	 */
	public function earningCallTranscript(string $symbol, int $year, int $quarter): ?EarningCallTranscript;

	/**
	 * Earning calls with a transcript across all symbols, ordered from the newest to the oldest call.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/earning-call-transcript-latest
	 * @param int<0, 100> $page
	 * @param int<1, 100>|null $limit
	 * @return iterable<int, LatestEarningCallTranscript>
	 */
	public function earningCallTranscriptLatest(int $page = 0, ?int $limit = null): iterable;

	/**
	 * Symbols and names of the ETFs covered by the API.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/etf-list
	 * @return iterable<int, Etf>
	 */
	public function etfList(): iterable;

	/**
	 * Profile of an ETF or a mutual fund: issuer, expense ratio, assets under management, net asset value and sector
	 * breakdown.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/etf/info
	 */
	public function etfInfo(string $symbol): ?EtfInfo;

	/**
	 * Assets held by an ETF or a mutual fund, ordered from the largest to the smallest position.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/etf/holdings
	 * @return iterable<int, EtfHolding>
	 */
	public function etfHoldings(string $symbol): iterable;

	/**
	 * Assets held by every ETF and mutual fund at once, the bulk variant of the ETF holdings. The API splits the data
	 * into parts of several hundred megabytes, which are requested one after another until the API rejects a part.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/etf-holder-bulk
	 * @return iterable<int, EtfHoldingBulk>
	 */
	public function etfHoldingsBulk(): iterable;

	/**
	 * Share of every sector in an ETF or a mutual fund.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/etf/sector-weightings
	 * @return iterable<int, EtfSectorWeighting>
	 */
	public function etfSectorWeightings(string $symbol): iterable;

	/**
	 * Share of every country in an ETF or a mutual fund.
	 * Available worldwide.
	 *
	 * @see https://financialmodelingprep.com/stable/etf/country-weightings
	 * @return iterable<int, EtfCountryWeighting>
	 */
	public function etfCountryWeightings(string $symbol): iterable;

	/**
	 * @template T
	 * @param callable(int<0, max> $page): iterable<T> $callback
	 * @param int<0, max> $initialPage
	 * @param int<0, max>|null $maxPage Inclusive
	 * @param int<1, max>|null $maxPageGuard Safety guard to prevent infinite loops
	 * @param-immediately-invoked-callable $callback
	 * @return iterable<int, T>
	 */
	public function iteratePages(callable $callback, int $initialPage = 0, ?int $maxPage = null, ?int $maxPageGuard = null): iterable;

	/**
	 * @param array<string, scalar|null> $query
	 */
	public function request(string $path, array $query = []): ResponseInterface;

}
