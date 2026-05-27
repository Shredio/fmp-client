<?php declare(strict_types = 1);

namespace Shredio\FmpClient;

use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Shredio\FmpClient\Config\HttpClientRetryConfiguration;
use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
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
use Shredio\FmpClient\Payload\Dividend;
use Shredio\FmpClient\Payload\EarningsCalendarItem;
use Shredio\FmpClient\Payload\EconomicCalendarItem;
use Shredio\FmpClient\Payload\EodQuote;
use Shredio\FmpClient\Payload\ExchangeMarketHours;
use Shredio\FmpClient\Payload\FinancialStatementSymbol;
use Shredio\FmpClient\Payload\HistoricalChart;
use Shredio\FmpClient\Payload\HistoricalPriceEod;
use Shredio\FmpClient\Payload\IncomeStatement;
use Shredio\FmpClient\Payload\IncomeStatementGrowth;
use Shredio\FmpClient\Payload\IncomeStatementGrowthBulk;
use Shredio\FmpClient\Payload\Index;
use Shredio\FmpClient\Payload\IsinSearchResult;
use Shredio\FmpClient\Payload\KeyMetrics;
use Shredio\FmpClient\Payload\KeyMetricsTtm;
use Shredio\FmpClient\Payload\LatestFinancialStatement;
use Shredio\FmpClient\Payload\LegacyEarningsCalendar;
use Shredio\FmpClient\Payload\MarketRiskPremium;
use Shredio\FmpClient\Payload\PeersBulk;
use Shredio\FmpClient\Payload\PressRelease;
use Shredio\FmpClient\Payload\Ratios;
use Shredio\FmpClient\Payload\RatiosTtm;
use Shredio\FmpClient\Payload\Scores;
use Shredio\FmpClient\Payload\SharesFloat;
use Shredio\FmpClient\Payload\SplitsCalendarItem;
use Shredio\FmpClient\Payload\Stock;
use Shredio\FmpClient\Payload\StockNews;
use Shredio\FmpClient\Payload\SymbolChange;
use Shredio\FmpClient\Payload\TreasuryRate;
use Shredio\FmpClient\Promise\FmpPromise;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface FmpClient
{

	public const int MaxDividendsLimit = 1000;
	public const int MaxStockNewsLimit = 250;
	public const int MaxLegacyEarningsCalendarDaysWindow = 90;

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
	 * @see https://financialmodelingprep.com/stable/available-exchanges
	 * @return iterable<int, AvailableExchange>
	 */
	public function availableExchanges(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/all-exchange-market-hours
	 * @return iterable<int, ExchangeMarketHours>
	 */
	public function allExchangeMarketHours(): iterable;

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
	 * @see https://financialmodelingprep.com/stable/cryptocurrency-list
	 * @return iterable<int, Cryptocurrency>
	 */
	public function cryptocurrencyList(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/stock-list
	 * @return iterable<int, Stock>
	 */
	public function stockList(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/actively-trading-list
	 * @return iterable<int, ActivelyTrading>
	 */
	public function activelyTradingList(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/symbol-change
	 * @return iterable<int, SymbolChange>
	 */
	public function symbolChangeList(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/search-isin
	 * @return iterable<int, IsinSearchResult>
	 */
	public function searchIsin(string $isin): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/delisted-companies
	 * @return iterable<int, DelistedCompany>
	 */
	public function delistedCompanies(int $limit = 100, int $page = 0): iterable;

	/**
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
	 * @see https://financialmodelingprep.com/stable/profile
	 */
	public function companyProfile(string $symbol): ?CompanyProfile;

	/**
	 * @see https://financialmodelingprep.com/stable/profile-bulk
	 * @return iterable<int, CompanyProfile>
	 */
	public function companyProfileBulk(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/shares-float
	 */
	public function sharesFloat(string $symbol): ?SharesFloat;

	/**
	 * @see https://financialmodelingprep.com/stable/shares-float-all
	 * @param int<1, 5000> $limit
	 * @return iterable<int, SharesFloat>
	 */
	public function sharesFloatAll(int $limit = 1000, int $page = 0): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-bulk
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatementBulk(int $year, Period $period = Period::FY): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/analyst-estimates
	 * @return iterable<int, AnalystEstimate>
	 */
	public function analystEstimates(string $symbol, string $period = 'annual', int $page = 0, int $limit = 6): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/dividends-calendar
	 * @return iterable<int, Dividend>
	 */
	public function dividendsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/dividends
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, Dividend>
	 */
	public function dividends(string $symbol, ?int $limit = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/earnings-calendar
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/api/v3/earning_calendar
	 * @return iterable<int, LegacyEarningsCalendar>
	 */
	public function legacyEarningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/splits-calendar
	 * @return iterable<int, SplitsCalendarItem>
	 */
	public function splitsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/economic-calendar
	 * @return iterable<int, EconomicCalendarItem>
	 */
	public function economicCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/latest-financial-statements
	 * @param int<0, 100> $page
	 * @param int<1, 250> $limit
	 * @return iterable<int, LatestFinancialStatement>
	 */
	public function latestFinancialStatements(int $page = 0, int $limit = 250): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement-bulk
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatementBulk(int $year, Period $period = Period::FY): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-bulk
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatementBulk(int $year, Period $period = Period::FY): iterable;

	/**
	 * @see https://financialmodelingprep.com/api/v3/income-statement-growth/{symbol}
	 * @return iterable<int, IncomeStatementGrowth>
	 */
	public function incomeStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/api/v3/balance-sheet-statement-growth/{symbol}
	 * @return iterable<int, BalanceSheetStatementGrowth>
	 */
	public function balanceSheetStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/api/v3/cash-flow-statement-growth/{symbol}
	 * @return iterable<int, CashFlowStatementGrowth>
	 */
	public function cashFlowStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement-growth-bulk
	 * @return iterable<int, IncomeStatementGrowthBulk>
	 */
	public function incomeStatementGrowthBulk(int $year, Period $period): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-growth-bulk
	 * @return iterable<int, BalanceSheetStatementGrowthBulk>
	 */
	public function balanceSheetStatementGrowthBulk(int $year, Period $period): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-growth-bulk
	 * @return iterable<int, CashFlowStatementGrowthBulk>
	 */
	public function cashFlowStatementGrowthBulk(int $year, Period $period): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/eod-bulk
	 * @return iterable<int, EodQuote>
	 */
	public function eodBulkQuotes(DateTimeImmutable $date): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/financial-statement-symbol-list
	 * @return iterable<int, FinancialStatementSymbol>
	 */
	public function financialStatementSymbolList(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/batch-exchange-quote
	 * @return iterable<int, BatchExchangeQuote>
	 */
	public function batchExchangeQuote(string $exchange): iterable;

	/**
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
	 * @see https://financialmodelingprep.com/stable/historical-price-eod/full
	 * @return iterable<int, HistoricalPriceEod>
	 */
	public function historicalPriceEod(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/historical-chart
	 * @return iterable<int, HistoricalChart>
	 */
	public function historicalChart(string $symbol, TimeInterval $interval, DateTimeImmutable $from, DateTimeImmutable $to): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/key-metrics
	 * @return iterable<int, KeyMetrics>
	 */
	public function keyMetrics(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/key-metrics-ttm
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtm(string $symbol): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/key-metrics-ttm-bulk
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtmBulk(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/ratios
	 * @return iterable<int, Ratios>
	 */
	public function ratios(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/ratios-ttm
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtm(string $symbol): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/ratios-ttm-bulk
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtmBulk(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/financial-scores
	 * @return iterable<int, Scores>
	 */
	public function financialScores(string $symbol): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/scores-bulk
	 * @return iterable<int, Scores>
	 */
	public function scoresBulk(): iterable;

	/**
	 * @see https://financialmodelingprep.com/stable/peers-bulk
	 * @return iterable<int, PeersBulk>
	 */
	public function peersBulk(): iterable;

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
