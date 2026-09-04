<?php declare(strict_types = 1);

namespace Shredio\FmpClient;

use DateInterval;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
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
use Shredio\FmpClient\Payload\DiscountedCashFlow;
use Shredio\FmpClient\Payload\DetailedEarningsCalendarItem;
use Shredio\FmpClient\Payload\Dividend;
use Shredio\FmpClient\Payload\EarningCallTranscript;
use Shredio\FmpClient\Payload\EarningCallTranscriptDate;
use Shredio\FmpClient\Payload\EarningsCalendarItem;
use Shredio\FmpClient\Payload\EconomicCalendarItem;
use Shredio\FmpClient\Payload\EodQuote;
use Shredio\FmpClient\Payload\ExchangeMarketHours;
use Shredio\FmpClient\Payload\FinancialStatementSymbol;
use Shredio\FmpClient\Payload\Grade;
use Shredio\FmpClient\Payload\GradesConsensus;
use Shredio\FmpClient\Payload\HistoricalChart;
use Shredio\FmpClient\Payload\HistoricalPriceEod;
use Shredio\FmpClient\Payload\HistoricalPriceEodLight;
use Shredio\FmpClient\Payload\HistoricalPriceEodNonSplitAdjusted;
use Shredio\FmpClient\Payload\HolidayByExchange;
use Shredio\FmpClient\Payload\IncomeStatement;
use Shredio\FmpClient\Payload\IncomeStatementGrowth;
use Shredio\FmpClient\Payload\IncomeStatementGrowthBulk;
use Shredio\FmpClient\Payload\Index;
use Shredio\FmpClient\Payload\InsiderTrade;
use Shredio\FmpClient\Payload\IsinSearchResult;
use Shredio\FmpClient\Payload\KeyMetrics;
use Shredio\FmpClient\Payload\KeyMetricsTtm;
use Shredio\FmpClient\Payload\LatestFinancialStatement;
use Shredio\FmpClient\Payload\MarketRiskPremium;
use Shredio\FmpClient\Payload\PeersBulk;
use Shredio\FmpClient\Payload\PressRelease;
use Shredio\FmpClient\Payload\PriceTargetConsensus;
use Shredio\FmpClient\Payload\Quote;
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

final readonly class CacheFmpClient implements FmpClient
{

	/**
	 * @param int<1, max>|DateInterval|null $ttl Time to live for cached items in seconds
	 */
	public function __construct(
		private FmpClient $client,
		private CacheInterface $cache,
		private int|DateInterval|null $ttl,
	)
	{
	}

	public function withStrictMode(bool $strictMode): FmpClient
	{
		return new self(
			$this->client->withStrictMode($strictMode),
			$this->cache,
			$this->ttl,
		);
	}

	public function withRetryConfiguration(HttpClientRetryConfiguration $config): FmpClient
	{
		return new self(
			$this->client->withRetryConfiguration($config),
			$this->cache,
			$this->ttl,
		);
	}

	public function forBackgroundProcessing(): FmpClient
	{
		return new self(
			$this->client->forBackgroundProcessing(),
			$this->cache,
			$this->ttl,
		);
	}

	/**
	 * @template TReturn
	 * @param callable(): TReturn $fn
	 * @return FmpPromise<TReturn>
	 */
	public function promise(callable $fn): FmpPromise
	{
		return $this->client->promise($fn);
	}

	/**
	 * @return iterable<int, AvailableExchange>
	 */
	public function availableExchanges(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->availableExchanges());
	}

	/**
	 * @return iterable<int, ExchangeMarketHours>
	 */
	public function allExchangeMarketHours(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->allExchangeMarketHours());
	}

	/**
	 * @return iterable<int, HolidayByExchange>
	 */
	public function holidaysByExchange(string $exchange): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->holidaysByExchange($exchange), $exchange);
	}

	/**
	 * @return iterable<int, MarketRiskPremium>
	 */
	public function marketRiskPremium(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->marketRiskPremium());
	}

	/**
	 * @return iterable<int, TreasuryRate>
	 */
	public function treasuryRates(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->treasuryRates());
	}

	/**
	 * @return iterable<int, Index>
	 */
	public function indexList(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->indexList());
	}

	/**
	 * @return iterable<int, Cryptocurrency>
	 */
	public function cryptocurrencyList(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->cryptocurrencyList());
	}

	/**
	 * @return iterable<int, Stock>
	 */
	public function stockList(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->stockList());
	}

	/**
	 * @return iterable<int, ActivelyTrading>
	 */
	public function activelyTradingList(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->activelyTradingList());
	}

	/**
	 * @return iterable<int, SymbolChange>
	 */
	public function symbolChangeList(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->symbolChangeList());
	}

	/**
	 * @return iterable<int, IsinSearchResult>
	 */
	public function searchIsin(string $isin): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->searchIsin($isin), $isin);
	}

	/**
	 * @return iterable<int, DelistedCompany>
	 */
	public function delistedCompanies(int $limit = 100, int $page = 0): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->delistedCompanies($limit, $page), sprintf('%d.%d', $limit, $page));
	}

	/**
	 * @return iterable<int, PressRelease>
	 */
	public function pressReleasesLatest(int $limit, int $page = 0): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->pressReleasesLatest($limit, $page), sprintf('%d.%d', $limit, $page));
	}

	/**
	 * @param int<1, 250> $limit
	 * @return iterable<int, StockNews>
	 */
	public function stockNewsLatest(int $limit, int $page = 0): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->stockNewsLatest($limit, $page), sprintf('%d.%d', $limit, $page));
	}

	public function companyProfile(string $symbol): ?CompanyProfile
	{
		return $this->cachedNullable(__FUNCTION__, fn () => $this->client->companyProfile($symbol), $symbol);
	}

	/**
	 * @return iterable<int, CompanyProfile>
	 */
	public function companyProfileBulk(): iterable
	{
		return $this->client->companyProfileBulk();
	}

	public function sharesFloat(string $symbol): ?SharesFloat
	{
		return $this->cachedNullable(__FUNCTION__, fn () => $this->client->sharesFloat($symbol), $symbol);
	}

	/**
	 * @param int<1, 5000> $limit
	 * @return iterable<int, SharesFloat>
	 */
	public function sharesFloatAll(int $limit = 1000, int $page = 0): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->sharesFloatAll($limit, $page), sprintf('%d.%d', $limit, $page));
	}

	/**
	 * @param int<1, 1000> $limit
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->balanceSheetStatement($symbol, $limit, $period),
			sprintf('%s.%d.%s', $symbol, $limit, $period->value),
		);
	}

	/**
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatementBulk(int $year, Period $period = Period::FY): iterable
	{
		return $this->client->balanceSheetStatementBulk($year, $period);
	}

	/**
	 * @return iterable<int, AnalystEstimate>
	 */
	public function analystEstimates(string $symbol, string $period = 'annual', int $page = 0, int $limit = 6): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->analystEstimates($symbol, $period, $page, $limit),
			sprintf('%s.%s.%d.%d', $symbol, $period, $page, $limit),
		);
	}

	/**
	 * @return iterable<int, Dividend>
	 */
	public function dividendsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		return $this->client->dividendsCalendar($from, $to, $logger);
	}

	/**
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, Dividend>
	 */
	public function dividends(string $symbol, ?int $limit = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->dividends($symbol, $limit),
			sprintf('%s.%s', $symbol, $limit ?? 'all'),
		);
	}

	/**
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		return $this->client->earningsCalendar($from, $to, $logger);
	}

	/**
	 * @return iterable<int, DetailedEarningsCalendarItem>
	 */
	public function detailedEarningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		return $this->client->detailedEarningsCalendar($from, $to, $logger);
	}

	/**
	 * @return iterable<int, StockSplit>
	 */
	public function splits(string $symbol): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->splits($symbol),
			$symbol,
		);
	}

	/**
	 * @return iterable<int, StockSplit>
	 */
	public function splitsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		return $this->client->splitsCalendar($from, $to, $logger);
	}

	/**
	 * @return iterable<int, EconomicCalendarItem>
	 */
	public function economicCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		return $this->client->economicCalendar($from, $to, $logger);
	}

	/**
	 * @param int<0, 100> $page
	 * @param int<1, 250> $limit
	 * @return iterable<int, LatestFinancialStatement>
	 */
	public function latestFinancialStatements(int $page = 0, int $limit = 250): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->latestFinancialStatements($page, $limit), sprintf('%d.%d', $page, $limit));
	}

	/**
	 * @param int<1, 1000> $limit
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->incomeStatement($symbol, $limit, $period),
			sprintf('%s.%d.%s', $symbol, $limit, $period->value),
		);
	}

	/**
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatementBulk(int $year, Period $period = Period::FY): iterable
	{
		return $this->client->incomeStatementBulk($year, $period);
	}

	/**
	 * @param int<1, 1000> $limit
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->cashFlowStatement($symbol, $limit, $period),
			sprintf('%s.%d.%s', $symbol, $limit, $period->value),
		);
	}

	/**
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatementBulk(int $year, Period $period = Period::FY): iterable
	{
		return $this->client->cashFlowStatementBulk($year, $period);
	}

	/**
	 * @return iterable<int, IncomeStatementGrowth>
	 */
	public function incomeStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->incomeStatementGrowth($symbol, $limit, $period),
			sprintf('%s.%s.%s', $symbol, $limit ?? 'all', $period !== null ? $period->value : 'all'),
		);
	}

	/**
	 * @return iterable<int, BalanceSheetStatementGrowth>
	 */
	public function balanceSheetStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->balanceSheetStatementGrowth($symbol, $limit, $period),
			sprintf('%s.%s.%s', $symbol, $limit ?? 'all', $period !== null ? $period->value : 'all'),
		);
	}

	/**
	 * @return iterable<int, CashFlowStatementGrowth>
	 */
	public function cashFlowStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->cashFlowStatementGrowth($symbol, $limit, $period),
			sprintf('%s.%s.%s', $symbol, $limit ?? 'all', $period !== null ? $period->value : 'all'),
		);
	}

	/**
	 * @return iterable<int, IncomeStatementGrowthBulk>
	 */
	public function incomeStatementGrowthBulk(int $year, Period $period): iterable
	{
		return $this->client->incomeStatementGrowthBulk($year, $period);
	}

	/**
	 * @return iterable<int, BalanceSheetStatementGrowthBulk>
	 */
	public function balanceSheetStatementGrowthBulk(int $year, Period $period): iterable
	{
		return $this->client->balanceSheetStatementGrowthBulk($year, $period);
	}

	/**
	 * @return iterable<int, CashFlowStatementGrowthBulk>
	 */
	public function cashFlowStatementGrowthBulk(int $year, Period $period): iterable
	{
		return $this->client->cashFlowStatementGrowthBulk($year, $period);
	}

	/**
	 * @return iterable<int, RevenueProductSegmentation>
	 */
	public function revenueProductSegmentation(string $symbol, PeriodQuery $period = PeriodQuery::Annual): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->revenueProductSegmentation($symbol, $period),
			sprintf('%s.%s', $symbol, $period->value),
		);
	}

	/**
	 * @return iterable<int, RevenueGeographicSegmentation>
	 */
	public function revenueGeographicSegmentation(string $symbol, PeriodQuery $period = PeriodQuery::Annual): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->revenueGeographicSegmentation($symbol, $period),
			sprintf('%s.%s', $symbol, $period->value),
		);
	}

	/**
	 * @return iterable<int, EodQuote>
	 */
	public function eodBulkQuotes(DateTimeImmutable $date): iterable
	{
		return $this->client->eodBulkQuotes($date);
	}

	/**
	 * @return iterable<int, FinancialStatementSymbol>
	 */
	public function financialStatementSymbolList(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->financialStatementSymbolList());
	}

	/**
	 * @return iterable<int, BatchExchangeQuote>
	 */
	public function batchExchangeQuote(string $exchange): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->batchExchangeQuote($exchange), $exchange);
	}

	/**
	 * @return iterable<int, BatchExchangeDetailedQuote>
	 */
	public function batchExchangeQuoteDetailed(string $exchange): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->batchExchangeQuoteDetailed($exchange), $exchange);
	}

	/**
	 * @return iterable<int, BatchForexQuote>
	 */
	public function batchForexQuotes(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->batchForexQuotes());
	}

	/**
	 * @return iterable<int, HistoricalPriceEod>
	 */
	public function historicalPriceEod(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->historicalPriceEod($symbol, $from, $to),
			sprintf('%s.%s.%s', $symbol, $from->format('Y-m-d'), $to->format('Y-m-d')),
		);
	}

	/**
	 * @return iterable<int, HistoricalPriceEodNonSplitAdjusted>
	 */
	public function historicalPriceEodNonSplitAdjusted(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->historicalPriceEodNonSplitAdjusted($symbol, $from, $to),
			sprintf('%s.%s.%s', $symbol, $from->format('Y-m-d'), $to->format('Y-m-d')),
		);
	}

	/**
	 * @return iterable<int, HistoricalPriceEodLight>
	 */
	public function historicalPriceEodLight(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->historicalPriceEodLight($symbol, $from, $to),
			sprintf('%s.%s.%s', $symbol, $from->format('Y-m-d'), $to->format('Y-m-d')),
		);
	}

	/**
	 * @return iterable<int, HistoricalChart>
	 */
	public function historicalChart(string $symbol, TimeInterval $interval, DateTimeImmutable $from, DateTimeImmutable $to): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->historicalChart($symbol, $interval, $from, $to),
			sprintf('%s.%s.%s.%s', $symbol, $interval->value, $from->format('Y-m-d'), $to->format('Y-m-d')),
		);
	}

	/**
	 * @return iterable<int, KeyMetrics>
	 */
	public function keyMetrics(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->keyMetrics($symbol, $limit, $period),
			sprintf('%s.%d.%s', $symbol, $limit, $period->value),
		);
	}

	/**
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtm(string $symbol): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->keyMetricsTtm($symbol), $symbol);
	}

	/**
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtmBulk(): iterable
	{
		return $this->client->keyMetricsTtmBulk();
	}

	/**
	 * @return iterable<int, Ratios>
	 */
	public function ratios(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->ratios($symbol, $limit, $period),
			sprintf('%s.%d.%s', $symbol, $limit, $period->value),
		);
	}

	/**
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtm(string $symbol): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->ratiosTtm($symbol), $symbol);
	}

	/**
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtmBulk(): iterable
	{
		return $this->client->ratiosTtmBulk();
	}

	/**
	 * @return iterable<int, Scores>
	 */
	public function financialScores(string $symbol): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->financialScores($symbol), $symbol);
	}

	/**
	 * @return iterable<int, Scores>
	 */
	public function scoresBulk(): iterable
	{
		return $this->client->scoresBulk();
	}

	/**
	 * @return iterable<int, PeersBulk>
	 */
	public function peersBulk(): iterable
	{
		return $this->client->peersBulk();
	}

	public function quote(string $symbol): ?Quote
	{
		return $this->cachedNullable(__FUNCTION__, fn () => $this->client->quote($symbol), $symbol);
	}

	/**
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatementTtm(string $symbol, ?int $limit = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->incomeStatementTtm($symbol, $limit),
			sprintf('%s.%s', $symbol, $limit ?? 'all'),
		);
	}

	/**
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earnings(string $symbol, ?int $limit = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->earnings($symbol, $limit),
			sprintf('%s.%s', $symbol, $limit ?? 'all'),
		);
	}

	public function priceTargetConsensus(string $symbol): ?PriceTargetConsensus
	{
		return $this->cachedNullable(__FUNCTION__, fn () => $this->client->priceTargetConsensus($symbol), $symbol);
	}

	public function discountedCashFlow(string $symbol): ?DiscountedCashFlow
	{
		return $this->cachedNullable(__FUNCTION__, fn () => $this->client->discountedCashFlow($symbol), $symbol);
	}

	public function gradesConsensus(string $symbol): ?GradesConsensus
	{
		return $this->cachedNullable(__FUNCTION__, fn () => $this->client->gradesConsensus($symbol), $symbol);
	}

	/**
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, Grade>
	 */
	public function grades(string $symbol, ?int $limit = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->grades($symbol, $limit),
			sprintf('%s.%s', $symbol, $limit ?? 'all'),
		);
	}

	/**
	 * @param int<0, max> $page
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, InsiderTrade>
	 */
	public function insiderTrades(string $symbol, int $page = 0, ?int $limit = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->insiderTrades($symbol, $page, $limit),
			sprintf('%s.%d.%s', $symbol, $page, $limit ?? 'all'),
		);
	}

	/**
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, SenateTrade>
	 */
	public function senateTrades(string $symbol, ?int $limit = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->senateTrades($symbol, $limit),
			sprintf('%s.%s', $symbol, $limit ?? 'all'),
		);
	}

	/**
	 * @return iterable<int, EarningCallTranscriptDate>
	 */
	public function earningCallTranscriptDates(string $symbol): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->earningCallTranscriptDates($symbol), $symbol);
	}

	/**
	 * @param int<1, 4> $quarter
	 */
	public function earningCallTranscript(string $symbol, int $year, int $quarter): ?EarningCallTranscript
	{
		return $this->cachedNullable(
			__FUNCTION__,
			fn () => $this->client->earningCallTranscript($symbol, $year, $quarter),
			sprintf('%s.%d.%d', $symbol, $year, $quarter),
		);
	}

	/**
	 * @template T
	 * @param callable(int<0, max> $page): iterable<T> $callback
	 * @param int<0, max> $initialPage
	 * @param int<0, max>|null $maxPage
	 * @param int<1, max>|null $maxPageGuard
	 * @return iterable<int, T>
	 */
	public function iteratePages(callable $callback, int $initialPage = 0, ?int $maxPage = null, ?int $maxPageGuard = null): iterable
	{
		return $this->client->iteratePages($callback, $initialPage, $maxPage, $maxPageGuard);
	}

	/**
	 * @param array<string, scalar|null> $query
	 */
	public function request(string $path, array $query = []): ResponseInterface
	{
		return $this->client->request($path, $query);
	}

	/**
	 * @template T
	 * @param non-empty-string $method
	 * @param callable(): iterable<int, T> $factory
	 * @return list<T>
	 */
	private function cached(string $method, callable $factory, ?string $suffix = null): array
	{
		$cacheKey = $this->key($method, $suffix);
		/** @var list<T>|null $value */
		$value = $this->cache->get($cacheKey);

		if ($value === null) {
			$value = iterator_to_array($factory(), false);

			$this->cache->set($cacheKey, $value, $this->ttl);
		}

		return $value;
	}

	/**
	 * @template T of object
	 * @param non-empty-string $method
	 * @param callable(): (T|null) $factory
	 * @return T|null
	 */
	private function cachedNullable(string $method, callable $factory, ?string $suffix = null): ?object
	{
		$cacheKey = $this->key($method, $suffix);
		/** @var array{hit: true, value: T|null}|null $cached */
		$cached = $this->cache->get($cacheKey);

		if ($cached === null) {
			$value = $factory();

			$this->cache->set($cacheKey, ['hit' => true, 'value' => $value], $this->ttl);

			return $value;
		}

		return $cached['value'];
	}

	/**
	 * @param non-empty-string $method
	 */
	private function key(string $method, ?string $suffix = null): string
	{
		$key = sprintf('fmp-client.%s', $method);
		if ($suffix !== null && $suffix !== '') {
			$key .= sprintf('.%s', $suffix);
		}

		return $key;
	}

}
