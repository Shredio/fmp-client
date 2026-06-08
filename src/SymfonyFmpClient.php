<?php declare(strict_types = 1);

namespace Shredio\FmpClient;

use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;
use Psr\Log\LoggerInterface;
use SensitiveParameter;
use Shredio\FmpClient\Calendar\FmpCalendarPaginator;
use Shredio\FmpClient\Config\HttpClientRetryConfiguration;
use Shredio\FmpClient\Converter\LenientAndEmptyNumberConverter;
use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
use Shredio\FmpClient\Enum\TimeInterval;
use Shredio\FmpClient\Exception\UnexpectedResponseContentExceptionHandler;
use Shredio\FmpClient\Mapper\ActivelyTradingMapper;
use Shredio\FmpClient\Mapper\AnalystEstimateMapper;
use Shredio\FmpClient\Mapper\AvailableExchangeMapper;
use Shredio\FmpClient\Mapper\BalanceSheetStatementGrowthBulkMapper;
use Shredio\FmpClient\Mapper\BalanceSheetStatementGrowthMapper;
use Shredio\FmpClient\Mapper\BalanceSheetStatementMapper;
use Shredio\FmpClient\Mapper\BatchExchangeDetailedQuoteMapper;
use Shredio\FmpClient\Mapper\BatchExchangeQuoteMapper;
use Shredio\FmpClient\Mapper\BatchForexQuoteMapper;
use Shredio\FmpClient\Mapper\CashFlowStatementGrowthBulkMapper;
use Shredio\FmpClient\Mapper\CashFlowStatementGrowthMapper;
use Shredio\FmpClient\Mapper\CashFlowStatementMapper;
use Shredio\FmpClient\Mapper\CompanyProfileMapper;
use Shredio\FmpClient\Mapper\CryptocurrencyMapper;
use Shredio\FmpClient\Mapper\DelistedCompanyMapper;
use Shredio\FmpClient\Mapper\DividendMapper;
use Shredio\FmpClient\Mapper\EarningsCalendarItemMapper;
use Shredio\FmpClient\Mapper\EconomicCalendarItemMapper;
use Shredio\FmpClient\Mapper\EodQuoteMapper;
use Shredio\FmpClient\Mapper\ExchangeMarketHoursMapper;
use Shredio\FmpClient\Mapper\FinancialStatementSymbolMapper;
use Shredio\FmpClient\Mapper\HistoricalChartMapper;
use Shredio\FmpClient\Mapper\HistoricalPriceEodMapper;
use Shredio\FmpClient\Mapper\HolidayByExchangeMapper;
use Shredio\FmpClient\Mapper\IncomeStatementGrowthBulkMapper;
use Shredio\FmpClient\Mapper\IncomeStatementGrowthMapper;
use Shredio\FmpClient\Mapper\IncomeStatementMapper;
use Shredio\FmpClient\Mapper\IndexMapper;
use Shredio\FmpClient\Mapper\IsinSearchResultMapper;
use Shredio\FmpClient\Mapper\KeyMetricsMapper;
use Shredio\FmpClient\Mapper\KeyMetricsTtmMapper;
use Shredio\FmpClient\Mapper\LatestFinancialStatementMapper;
use Shredio\FmpClient\Mapper\LegacyEarningsCalendarMapper;
use Shredio\FmpClient\Mapper\MarketRiskPremiumMapper;
use Shredio\FmpClient\Mapper\PressReleaseMapper;
use Shredio\FmpClient\Mapper\RatiosMapper;
use Shredio\FmpClient\Mapper\RatiosTtmMapper;
use Shredio\FmpClient\Mapper\PeersBulkMapper;
use Shredio\FmpClient\Mapper\ScoresMapper;
use Shredio\FmpClient\Mapper\SharesFloatMapper;
use Shredio\FmpClient\Mapper\SplitsCalendarItemMapper;
use Shredio\FmpClient\Mapper\StockMapper;
use Shredio\FmpClient\Mapper\StockNewsMapper;
use Shredio\FmpClient\Mapper\SymbolChangeMapper;
use Shredio\FmpClient\Mapper\TreasuryRateMapper;
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
use Shredio\FmpClient\Payload\HolidayByExchange;
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
use Shredio\TypeSchema\Config\TypeConfig;
use Shredio\TypeSchema\Context\SourceFormat;
use Shredio\TypeSchema\Conversion\ConfigurableConversionStrategy;
use Shredio\TypeSchema\Conversion\ConversionStrategyFactory;
use Shredio\TypeSchema\Conversion\Converter\Array\LenientArrayConverter;
use Shredio\TypeSchema\Conversion\Converter\Bool\LenientBoolConverter;
use Shredio\TypeSchema\Conversion\Converter\Bool\StrictBoolConverter;
use Shredio\TypeSchema\Conversion\Converter\Null\LenientNullConverter;
use Shredio\TypeSchema\Conversion\Converter\Number\JsonNumberConverter;
use Shredio\TypeSchema\Conversion\Converter\Number\LenientNumberConverter;
use Shredio\TypeSchema\Conversion\Converter\String\LenientStringConverter;
use Shredio\TypeSchema\Conversion\Converter\String\StrictStringConverter;
use Shredio\TypeSchema\Conversion\Object\LenientObjectSupervisor;
use Shredio\TypeSchema\Error\ErrorElement;
use Shredio\TypeSchema\Error\TypeSchemaErrorFormatter;
use Shredio\TypeSchema\Types\Type;
use Shredio\TypeSchema\TypeSchemaProcessor;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class SymfonyFmpClient implements FmpClient
{

	private Parser\LargeResponseParser $largeResponseParser;

	private TypeSchemaProcessor $schemaProcessor;

	private TypeConfig $csvTypeConfig;

	private TypeConfig $jsonTypeConfig;

	private HttpClientInterface $httpClient;

	public function __construct(
		HttpClientInterface $httpClient,
		#[SensitiveParameter]
		private string $secret,
		private ?UnexpectedResponseContentExceptionHandler $invalidArgumentHandler = null,
		private bool $strictMode = false,
		private bool $builtInJsonParser = false,
		?HttpClientRetryConfiguration $retryConfiguration = new HttpClientRetryConfiguration(),
	)
	{
		if ($retryConfiguration === null) {
			$this->httpClient = $httpClient;
		} else {
			$this->httpClient = new RetryableHttpClient(
				$httpClient,
				new GenericRetryStrategy(delayMs: $retryConfiguration->delayMs, multiplier: $retryConfiguration->multiplier),
				$retryConfiguration->maxRetries,
			);
		}

		$this->largeResponseParser = new Parser\LargeResponseParser();
		$this->schemaProcessor = TypeSchemaProcessor::createDefault();
		$this->csvTypeConfig = new TypeConfig(ConversionStrategyFactory::lenient(), options: [
			SourceFormat::class => new SourceFormat('csv'),
		]);
		$this->jsonTypeConfig = new TypeConfig(new ConfigurableConversionStrategy(
			new StrictStringConverter(),
			new JsonNumberConverter(),
			new StrictBoolConverter(),
			new LenientNullConverter(),
			new LenientArrayConverter(),
			new LenientObjectSupervisor(),
		), options: [
			SourceFormat::class => new SourceFormat('json'),
		]);
	}

	public function withStrictMode(bool $strictMode): self
	{
		return new self(
			$this->httpClient,
			$this->secret,
			$this->invalidArgumentHandler,
			$strictMode,
			$this->builtInJsonParser,
			retryConfiguration: null,
		);
	}

	public function withRetryConfiguration(HttpClientRetryConfiguration $config): self
	{
		return new self(
			$this->httpClient,
			$this->secret,
			$this->invalidArgumentHandler,
			$this->strictMode,
			$this->builtInJsonParser,
			retryConfiguration: $config,
		);
	}

	/**
	 * Returns a client configured for background tasks (cron jobs, queues) with extended retry settings and more host connections.
	 */
	public function forBackgroundProcessing(): self
	{
		return new self(
			HttpClient::create(maxHostConnections: 100),
			$this->secret,
			$this->invalidArgumentHandler,
			$this->strictMode,
			$this->builtInJsonParser,
			retryConfiguration: new HttpClientRetryConfiguration(1000, 1.5, 4),
		);
	}

	/**
	 * @template TReturn
	 * @param callable(): TReturn $fn
	 * @return FmpPromise<TReturn>
	 */
	public function promise(callable $fn): FmpPromise
	{
		return FmpPromise::run($fn);
	}

	/**
	 * @see https://financialmodelingprep.com/stable/available-exchanges
	 * @return iterable<int, AvailableExchange>
	 */
	public function availableExchanges(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/available-exchanges');

		foreach ($this->requestJson('stable/available-exchanges') as $item) {
			$object = $this->map(AvailableExchange::class, new AvailableExchangeMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/all-exchange-market-hours
	 * @return iterable<int, ExchangeMarketHours>
	 */
	public function allExchangeMarketHours(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/all-exchange-market-hours');

		foreach ($this->requestJson('stable/all-exchange-market-hours') as $item) {
			$object = $this->map(ExchangeMarketHours::class, new ExchangeMarketHoursMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/holidays-by-exchange
	 * @return iterable<int, HolidayByExchange>
	 */
	public function holidaysByExchange(string $exchange): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/holidays-by-exchange', ['exchange' => $exchange]);

		foreach ($this->requestJson('stable/holidays-by-exchange', ['exchange' => $exchange]) as $item) {
			$object = $this->map(HolidayByExchange::class, new HolidayByExchangeMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/market-risk-premium
	 * @return iterable<int, MarketRiskPremium>
	 */
	public function marketRiskPremium(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/market-risk-premium');

		foreach ($this->requestJson('stable/market-risk-premium') as $item) {
			$object = $this->map(MarketRiskPremium::class, new MarketRiskPremiumMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/treasury-rates
	 * @return iterable<int, TreasuryRate>
	 */
	public function treasuryRates(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/treasury-rates');

		foreach ($this->requestJson('stable/treasury-rates') as $item) {
			$object = $this->map(TreasuryRate::class, new TreasuryRateMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/index-list
	 * @return iterable<int, Index>
	 */
	public function indexList(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/index-list');

		foreach ($this->requestJson('stable/index-list') as $item) {
			$object = $this->map(Index::class, new IndexMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/cryptocurrency-list
	 * @return iterable<int, Cryptocurrency>
	 */
	public function cryptocurrencyList(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/cryptocurrency-list');

		foreach ($this->requestJson('stable/cryptocurrency-list') as $item) {
			$object = $this->map(Cryptocurrency::class, new CryptocurrencyMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/stock-list
	 * @return iterable<int, Stock>
	 */
	public function stockList(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/stock-list');

		foreach ($this->requestJson('stable/stock-list') as $item) {
			$object = $this->map(Stock::class, new StockMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/actively-trading-list
	 * @return iterable<int, ActivelyTrading>
	 */
	public function activelyTradingList(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/actively-trading-list');

		foreach ($this->requestJson('stable/actively-trading-list') as $item) {
			$object = $this->map(ActivelyTrading::class, new ActivelyTradingMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/symbol-change
	 * @return iterable<int, SymbolChange>
	 */
	public function symbolChangeList(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/symbol-change');

		foreach ($this->requestJson('stable/symbol-change') as $item) {
			$object = $this->map(SymbolChange::class, new SymbolChangeMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/search-isin
	 * @return iterable<int, IsinSearchResult>
	 */
	public function searchIsin(string $isin): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/search-isin', ['isin' => $isin]);

		foreach ($this->requestJson('stable/search-isin', ['isin' => $isin]) as $item) {
			$object = $this->map(IsinSearchResult::class, new IsinSearchResultMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/delisted-companies
	 * @return iterable<int, DelistedCompany>
	 */
	public function delistedCompanies(int $limit = 100, int $page = 0): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/delisted-companies', ['page' => $page, 'limit' => $limit]);

		foreach ($this->requestJson('stable/delisted-companies', ['page' => $page, 'limit' => $limit]) as $item) {
			$object = $this->map(DelistedCompany::class, new DelistedCompanyMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/news/press-releases-latest
	 * @return iterable<int, PressRelease>
	 */
	public function pressReleasesLatest(int $limit, int $page = 0): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/news/press-releases-latest', ['page' => $page, 'limit' => $limit]);

		foreach ($this->requestJson('stable/news/press-releases-latest', ['page' => $page, 'limit' => $limit]) as $item) {
			$object = $this->map(PressRelease::class, new PressReleaseMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/news/stock-latest
	 * @param int<1, 250> $limit
	 * @return iterable<int, StockNews>
	 */
	public function stockNewsLatest(int $limit, int $page = 0): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/news/stock-latest', ['page' => $page, 'limit' => $limit]);

		foreach ($this->requestJson('stable/news/stock-latest', ['page' => $page, 'limit' => $limit]) as $item) {
			$object = $this->map(StockNews::class, new StockNewsMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/profile
	 */
	public function companyProfile(string $symbol): ?CompanyProfile
	{
		$url = $this->buildUrlWithoutApiKey('stable/profile', ['symbol' => $symbol]);

		foreach ($this->requestJson('stable/profile', ['symbol' => $symbol]) as $item) {
			$object = $this->map(CompanyProfile::class, new CompanyProfileMapper(), $item, $url);
			if ($object !== null) {
				return $object;
			}
		}

		return null;
	}

	/**
	 * @see https://financialmodelingprep.com/stable/profile-bulk
	 * @return iterable<int, CompanyProfile>
	 */
	public function companyProfileBulk(): iterable
	{
		for ($part = 0; $part < 100; $part++) {
			$response = $this->request('stable/profile-bulk', ['part' => $part]);

			if ($response->getStatusCode() === 400) {
				return;
			}

			$url = $this->buildUrlWithoutApiKey('stable/profile-bulk', ['part' => $part]);

			foreach ($this->processCsvResponse($response) as $item) {
				$object = $this->map(CompanyProfile::class, new CompanyProfileMapper(), $item, $url, true);
				if ($object !== null) {
					yield $object;
				}
			}
		}

		if ($part === 100) {
			throw new LogicException('Reached maximum number of parts for company profile bulk request.');
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/shares-float
	 */
	public function sharesFloat(string $symbol): ?SharesFloat
	{
		$url = $this->buildUrlWithoutApiKey('stable/shares-float', ['symbol' => $symbol]);
		$count = 0;
		$result = null;

		foreach ($this->requestJson('stable/shares-float', ['symbol' => $symbol]) as $item) {
			$count++;
			if ($count > 1) {
				throw new LogicException(sprintf('Expected 0 or 1 record for shares float, got more than 1 for symbol %s', $symbol));
			}

			$object = $this->map(SharesFloat::class, new SharesFloatMapper(), $item, $url);
			if ($object !== null) {
				$result = $object;
			}
		}

		return $result;
	}

	/**
	 * @see https://financialmodelingprep.com/stable/shares-float-all
	 * @param int<1, 5000> $limit
	 * @return iterable<int, SharesFloat>
	 */
	public function sharesFloatAll(int $limit = 1000, int $page = 0): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/shares-float-all', ['page' => $page, 'limit' => $limit]);

		foreach ($this->requestJson('stable/shares-float-all', ['page' => $page, 'limit' => $limit]) as $item) {
			$object = $this->map(SharesFloat::class, new SharesFloatMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable
	{
		$url = $this->buildUrlWithoutApiKey(
			'stable/balance-sheet-statement',
			['symbol' => $symbol, 'limit' => $limit, 'period' => $period->value],
		);

		foreach ($this->requestJson('stable/balance-sheet-statement', ['symbol' => $symbol, 'limit' => $limit, 'period' => $period->value]) as $item) {
			$object = $this->map(BalanceSheetStatement::class, new BalanceSheetStatementMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-bulk
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatementBulk(int $year, Period $period = Period::FY): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/balance-sheet-statement-bulk', ['year' => $year, 'period' => $period->value]);

		$config = $this->createTypeConfigForStatements();
		foreach ($this->requestCsv('stable/balance-sheet-statement-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			$object = $this->map(BalanceSheetStatement::class, new BalanceSheetStatementMapper(), $item, $url, true, $config);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/analyst-estimates
	 * @return iterable<int, AnalystEstimate>
	 */
	public function analystEstimates(string $symbol, string $period = 'annual', int $page = 0, int $limit = 6): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/analyst-estimates', [
			'symbol' => $symbol,
			'period' => $period,
			'page' => $page,
			'limit' => $limit,
		]);

		foreach ($this->requestJson('stable/analyst-estimates', [
			'symbol' => $symbol,
			'period' => $period,
			'page' => $page,
			'limit' => $limit,
		]) as $item) {
			$object = $this->map(AnalystEstimate::class, new AnalystEstimateMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/dividends-calendar
	 * @return iterable<int, Dividend>
	 */
	public function dividendsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		$paginator = new FmpCalendarPaginator($from, $to);

		do {
			$values = $this->requestJson('stable/dividends-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);
			$lastStringDate = null;
			$count = 0;

			$url = $this->buildUrlWithoutApiKey('stable/dividends-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);

			foreach ($values as $item) {
				$object = $this->map(Dividend::class, new DividendMapper(), $item, $url);
				if ($object !== null) {
					$lastStringDate = $object->date;
					$count++;
					yield $object;
				}
			}
		} while ($paginator->next($count, $lastStringDate, $logger));
	}

	/**
	 * @see https://financialmodelingprep.com/stable/dividends
	 * @param int<1, 1000>|null $limit
	 * @return iterable<int, Dividend>
	 */
	public function dividends(string $symbol, ?int $limit = null): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/dividends', ['symbol' => $symbol, 'limit' => $limit]);

		foreach ($this->requestJson('stable/dividends', ['symbol' => $symbol, 'limit' => $limit]) as $item) {
			$object = $this->map(Dividend::class, new DividendMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/earnings-calendar
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		$paginator = new FmpCalendarPaginator($from, $to);

		do {
			$values = $this->requestJson('stable/earnings-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);
			$lastStringDate = null;
			$count = 0;

			$url = $this->buildUrlWithoutApiKey('stable/earnings-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);

			foreach ($values as $item) {
				$object = $this->map(EarningsCalendarItem::class, new EarningsCalendarItemMapper(), $item, $url);
				if ($object !== null) {
					$lastStringDate = $object->date;
					$count++;
					yield $object;
				}
			}
		} while ($paginator->next($count, $lastStringDate, $logger));
	}

	/**
	 * @see https://financialmodelingprep.com/api/v3/earning_calendar
	 * @return iterable<int, LegacyEarningsCalendar>
	 */
	public function legacyEarningsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		$from = $from->setTime(0, 0);
		$to = $to->setTime(0, 0);

		if ($to < $from) {
			throw new InvalidArgumentException('To date must be greater than from date');
		}

		$windowSize = sprintf('- %d days', self::MaxLegacyEarningsCalendarDaysWindow - 1);
		$windowTo = $to;

		do {
			$windowFrom = $windowTo->modify($windowSize);
			if ($windowFrom < $from) {
				$windowFrom = $from;
			}

			$query = [
				'from' => $windowFrom->format('Y-m-d'),
				'to' => $windowTo->format('Y-m-d'),
			];

			$url = $this->buildUrlWithoutApiKey('api/v3/earning_calendar', $query);

			foreach ($this->requestJson('api/v3/earning_calendar', $query) as $item) {
				$object = $this->map(LegacyEarningsCalendar::class, new LegacyEarningsCalendarMapper(), $item, $url);
				if ($object !== null) {
					yield $object;
				}
			}

			$windowTo = $windowFrom->modify('- 1 day');
		} while ($windowTo >= $from);
	}

	/**
	 * @see https://financialmodelingprep.com/stable/splits-calendar
	 * @return iterable<int, SplitsCalendarItem>
	 */
	public function splitsCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		$paginator = new FmpCalendarPaginator($from, $to);

		do {
			$values = $this->requestJson('stable/splits-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);
			$lastStringDate = null;
			$count = 0;

			$url = $this->buildUrlWithoutApiKey('stable/splits-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);

			foreach ($values as $item) {
				$object = $this->map(SplitsCalendarItem::class, new SplitsCalendarItemMapper(), $item, $url);
				if ($object !== null) {
					$lastStringDate = $object->date;
					$count++;
					yield $object;
				}
			}
		} while ($paginator->next($count, $lastStringDate, $logger));
	}

	/**
	 * @see https://financialmodelingprep.com/stable/economic-calendar
	 * @return iterable<int, EconomicCalendarItem>
	 */
	public function economicCalendar(DateTimeImmutable $from, DateTimeImmutable $to, ?LoggerInterface $logger = null): iterable
	{
		$paginator = new FmpCalendarPaginator($from, $to);

		do {
			$values = $this->requestJson('stable/economic-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);
			$lastStringDate = null;
			$count = 0;

			$url = $this->buildUrlWithoutApiKey('stable/economic-calendar', [
				'from' => $paginator->getFrom()->format('Y-m-d'),
				'to' => $paginator->getTo()->format('Y-m-d'),
			]);

			foreach ($values as $item) {
				$object = $this->map(EconomicCalendarItem::class, new EconomicCalendarItemMapper(), $item, $url);
				if ($object !== null) {
					$lastStringDate = substr($object->date, 0, 10);
					$count++;
					yield $object;
				}
			}
		} while ($paginator->next($count, $lastStringDate, $logger));
	}

	/**
	 * @see https://financialmodelingprep.com/stable/latest-financial-statements
	 * @param int<0, 100> $page
	 * @param int<1, 250> $limit
	 * @return iterable<int, LatestFinancialStatement>
	 */
	public function latestFinancialStatements(int $page = 0, int $limit = 250): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/latest-financial-statements', ['page' => $page, 'limit' => $limit]);

		foreach ($this->requestJson('stable/latest-financial-statements', ['page' => $page, 'limit' => $limit]) as $item) {
			$object = $this->map(LatestFinancialStatement::class, new LatestFinancialStatementMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable
	{
		$url = $this->buildUrlWithoutApiKey(
			'stable/income-statement',
			['symbol' => $symbol, 'limit' => $limit, 'period' => $period->value],
		);

		foreach ($this->requestJson('stable/income-statement', ['symbol' => $symbol, 'limit' => $limit, 'period' => $period->value]) as $item) {
			$object = $this->map(IncomeStatement::class, new IncomeStatementMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement-bulk
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatementBulk(int $year, Period $period = Period::FY): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/income-statement-bulk', ['year' => $year, 'period' => $period->value]);

		$config = $this->createTypeConfigForStatements();
		foreach ($this->requestCsv('stable/income-statement-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			$object = $this->map(IncomeStatement::class, new IncomeStatementMapper(), $item, $url, true, $config);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement
	 * @param int<1, 1000> $limit
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatement(string $symbol, int $limit = 4, PeriodQuery|Period $period = PeriodQuery::FY): iterable
	{
		$url = $this->buildUrlWithoutApiKey(
			'stable/cash-flow-statement',
			['symbol' => $symbol, 'limit' => $limit, 'period' => $period->value],
		);

		foreach ($this->requestJson('stable/cash-flow-statement', ['symbol' => $symbol, 'limit' => $limit, 'period' => $period->value]) as $item) {
			$object = $this->map(CashFlowStatement::class, new CashFlowStatementMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-bulk
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatementBulk(int $year, Period $period = Period::FY): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/cash-flow-statement-bulk', ['year' => $year, 'period' => $period->value]);

		$config = $this->createTypeConfigForStatements();
		foreach ($this->requestCsv('stable/cash-flow-statement-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			$object = $this->map(CashFlowStatement::class, new CashFlowStatementMapper(), $item, $url, true, $config);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/api/v3/income-statement-growth/{symbol}
	 * @return iterable<int, IncomeStatementGrowth>
	 */
	public function incomeStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable
	{
		$params = ['symbol' => $symbol];
		if ($limit !== null) {
			$params['limit'] = $limit;
		}
		if ($period !== null) {
			$params['period'] = $period->value;
		}

		$url = $this->buildUrlWithoutApiKey('api/v3/income-statement-growth/' . $symbol, $params);

		foreach ($this->requestJson('api/v3/income-statement-growth/' . $symbol, $params) as $item) {
			$object = $this->map(IncomeStatementGrowth::class, new IncomeStatementGrowthMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/api/v3/balance-sheet-statement-growth/{symbol}
	 * @return iterable<int, BalanceSheetStatementGrowth>
	 */
	public function balanceSheetStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable
	{
		$params = ['symbol' => $symbol];
		if ($limit !== null) {
			$params['limit'] = $limit;
		}
		if ($period !== null) {
			$params['period'] = $period->value;
		}

		$url = $this->buildUrlWithoutApiKey('api/v3/balance-sheet-statement-growth/' . $symbol, $params);

		foreach ($this->requestJson('api/v3/balance-sheet-statement-growth/' . $symbol, $params) as $item) {
			$object = $this->map(BalanceSheetStatementGrowth::class, new BalanceSheetStatementGrowthMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/api/v3/cash-flow-statement-growth/{symbol}
	 * @return iterable<int, CashFlowStatementGrowth>
	 */
	public function cashFlowStatementGrowth(string $symbol, int|null $limit = null, ?PeriodQuery $period = null): iterable
	{
		$params = ['symbol' => $symbol];
		if ($limit !== null) {
			$params['limit'] = $limit;
		}
		if ($period !== null) {
			$params['period'] = $period->value;
		}

		$url = $this->buildUrlWithoutApiKey('api/v3/cash-flow-statement-growth/' . $symbol, $params);

		foreach ($this->requestJson('api/v3/cash-flow-statement-growth/' . $symbol, $params) as $item) {
			$object = $this->map(CashFlowStatementGrowth::class, new CashFlowStatementGrowthMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement-growth-bulk
	 * @return iterable<int, IncomeStatementGrowthBulk>
	 */
	public function incomeStatementGrowthBulk(int $year, Period $period): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/income-statement-growth-bulk', ['year' => $year, 'period' => $period->value]);

		foreach ($this->requestCsv('stable/income-statement-growth-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			if (isset($item['fiscalYear'])) {
				$item['calendarYear'] = $item['fiscalYear'];
			}

			// Remove keys not in the payload
			unset(
				$item['fiscalYear'],
				$item['reportedCurrency'],
				$item['growthEBIT'],
				$item['growthNonOperatingIncomeExcludingInterest'],
				$item['growthNetInterestIncome'],
				$item['growthTotalOtherIncomeExpensesNet'],
				$item['growthNetIncomeFromContinuingOperations'],
				$item['growthOtherAdjustmentsToNetIncome'],
				$item['growthNetIncomeDeductions']
			);

			$object = $this->map(IncomeStatementGrowthBulk::class, new IncomeStatementGrowthBulkMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-growth-bulk
	 * @return iterable<int, BalanceSheetStatementGrowthBulk>
	 */
	public function balanceSheetStatementGrowthBulk(int $year, Period $period): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/balance-sheet-statement-growth-bulk', ['year' => $year, 'period' => $period->value]);

		foreach ($this->requestCsv('stable/balance-sheet-statement-growth-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			if (isset($item['fiscalYear'])) {
				$item['calendarYear'] = $item['fiscalYear'];
			}

			// Remove keys not in the payload
			unset(
				$item['fiscalYear'],
				$item['reportedCurrency'],
				$item['growthPreferredStock'],
				$item['growthMinorityInterest'],
				$item['growthTotalEquity'],
				$item['growthAccountsReceivables'],
				$item['growthOtherReceivables'],
				$item['growthPrepaids'],
				$item['growthTotalPayables'],
				$item['growthOtherPayables'],
				$item['growthAccruedExpenses'],
				$item['growthCapitalLeaseObligationsCurrent'],
				$item['growthAdditionalPaidInCapital'],
				$item['growthTreasuryStock']
			);

			$object = $this->map(BalanceSheetStatementGrowthBulk::class, new BalanceSheetStatementGrowthBulkMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-growth-bulk
	 * @return iterable<int, CashFlowStatementGrowthBulk>
	 */
	public function cashFlowStatementGrowthBulk(int $year, Period $period): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/cash-flow-statement-growth-bulk', ['year' => $year, 'period' => $period->value]);

		foreach ($this->requestCsv('stable/cash-flow-statement-growth-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			if (isset($item['fiscalYear'])) {
				$item['calendarYear'] = $item['fiscalYear'];
			}

			// Remove keys not in the payload
			unset(
				$item['fiscalYear'],
				$item['reportedCurrency'],
				$item['growthNetDebtIssuance'],
				$item['growthLongTermNetDebtIssuance'],
				$item['growthShortTermNetDebtIssuance'],
				$item['growthNetStockIssuance'],
				$item['growthPreferredDividendsPaid'],
				$item['growthIncomeTaxesPaid'],
				$item['growthInterestPaid']
			);

			$object = $this->map(CashFlowStatementGrowthBulk::class, new CashFlowStatementGrowthBulkMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/eod-bulk
	 * @return iterable<int, EodQuote>
	 */
	public function eodBulkQuotes(DateTimeImmutable $date): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/eod-bulk', ['date' => $date->format('Y-m-d')]);

		foreach ($this->requestCsv('stable/eod-bulk', ['date' => $date->format('Y-m-d')]) as $item) {
			$object = $this->map(EodQuote::class, new EodQuoteMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/financial-statement-symbol-list
	 * @return iterable<int, FinancialStatementSymbol>
	 */
	public function financialStatementSymbolList(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/financial-statement-symbol-list');

		foreach ($this->requestJson('stable/financial-statement-symbol-list') as $item) {
			$object = $this->map(FinancialStatementSymbol::class, new FinancialStatementSymbolMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/batch-exchange-quote
	 * @return iterable<int, BatchExchangeQuote>
	 */
	public function batchExchangeQuote(string $exchange): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/batch-exchange-quote', ['exchange' => $exchange]);

		foreach ($this->requestJson('stable/batch-exchange-quote', ['exchange' => $exchange]) as $item) {
			$object = $this->map(BatchExchangeQuote::class, new BatchExchangeQuoteMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/batch-exchange-quote
	 * @return iterable<int, BatchExchangeDetailedQuote>
	 */
	public function batchExchangeQuoteDetailed(string $exchange): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/batch-exchange-quote', ['exchange' => $exchange, 'short' => 'false']);

		foreach ($this->requestJson('stable/batch-exchange-quote', ['exchange' => $exchange, 'short' => 'false']) as $item) {
			$object = $this->map(BatchExchangeDetailedQuote::class, new BatchExchangeDetailedQuoteMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/batch-forex-quotes
	 * @return iterable<int, BatchForexQuote>
	 */
	public function batchForexQuotes(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/batch-forex-quotes');

		foreach ($this->requestJson('stable/batch-forex-quotes') as $item) {
			$object = $this->map(BatchForexQuote::class, new BatchForexQuoteMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/historical-price-eod/full
	 * @return iterable<int, HistoricalPriceEod>
	 */
	public function historicalPriceEod(string $symbol, DateTimeImmutable $from, DateTimeImmutable $to): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/historical-price-eod/full', [
			'symbol' => $symbol,
			'from' => $from->format('Y-m-d'),
			'to' => $to->format('Y-m-d'),
		]);

		foreach ($this->requestJson('stable/historical-price-eod/full', [
			'symbol' => $symbol,
			'from' => $from->format('Y-m-d'),
			'to' => $to->format('Y-m-d'),
		]) as $item) {
			$object = $this->map(HistoricalPriceEod::class, new HistoricalPriceEodMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/historical-chart
	 * @return iterable<int, HistoricalChart>
	 */
	public function historicalChart(string $symbol, TimeInterval $interval, DateTimeImmutable $from, DateTimeImmutable $to): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/historical-chart/' . $interval->value, [
			'symbol' => $symbol,
			'from' => $from->format('Y-m-d'),
			'to' => $to->format('Y-m-d'),
		]);

		foreach ($this->requestJson('stable/historical-chart/' . $interval->value, [
			'symbol' => $symbol,
			'from' => $from->format('Y-m-d'),
			'to' => $to->format('Y-m-d'),
		]) as $item) {
			$object = $this->map(HistoricalChart::class, new HistoricalChartMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/key-metrics
	 * @return iterable<int, KeyMetrics>
	 */
	public function keyMetrics(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/key-metrics', [
			'symbol' => $symbol,
			'limit' => $limit,
			'period' => $period->value,
		]);

		foreach ($this->requestJson('stable/key-metrics', [
			'symbol' => $symbol,
			'limit' => $limit,
			'period' => $period->value,
		]) as $item) {
			$object = $this->map(KeyMetrics::class, new KeyMetricsMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/key-metrics-ttm
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtm(string $symbol): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/key-metrics-ttm', ['symbol' => $symbol]);

		foreach ($this->requestJson('stable/key-metrics-ttm', ['symbol' => $symbol]) as $item) {
			$item = $this->removeTtmSuffix($item);
			$object = $this->map(KeyMetricsTtm::class, new KeyMetricsTtmMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/key-metrics-ttm-bulk
	 * @return iterable<int, KeyMetricsTtm>
	 */
	public function keyMetricsTtmBulk(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/key-metrics-ttm-bulk');

		foreach ($this->requestCsv('stable/key-metrics-ttm-bulk') as $item) {
			$item = $this->removeTtmSuffix($item);
			$object = $this->map(KeyMetricsTtm::class, new KeyMetricsTtmMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/ratios
	 * @return iterable<int, Ratios>
	 */
	public function ratios(string $symbol, int $limit = 80, PeriodQuery $period = PeriodQuery::Annual): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/ratios', [
			'symbol' => $symbol,
			'limit' => $limit,
			'period' => $period->value,
		]);

		foreach ($this->requestJson('stable/ratios', [
			'symbol' => $symbol,
			'limit' => $limit,
			'period' => $period->value,
		]) as $item) {
			$object = $this->map(Ratios::class, new RatiosMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/ratios-ttm
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtm(string $symbol): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/ratios-ttm', ['symbol' => $symbol]);

		foreach ($this->requestJson('stable/ratios-ttm', ['symbol' => $symbol]) as $item) {
			$item = $this->removeTtmSuffix($item);
			$object = $this->map(RatiosTtm::class, new RatiosTtmMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/ratios-ttm-bulk
	 * @return iterable<int, RatiosTtm>
	 */
	public function ratiosTtmBulk(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/ratios-ttm-bulk');

		foreach ($this->requestCsv('stable/ratios-ttm-bulk') as $item) {
			$item = $this->removeTtmSuffix($item);
			$object = $this->map(RatiosTtm::class, new RatiosTtmMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/financial-scores
	 * @return iterable<int, Scores>
	 */
	public function financialScores(string $symbol): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/financial-scores', ['symbol' => $symbol]);

		foreach ($this->requestJson('stable/financial-scores', ['symbol' => $symbol]) as $item) {
			$object = $this->map(Scores::class, new ScoresMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/scores-bulk
	 * @return iterable<int, Scores>
	 */
	public function scoresBulk(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/scores-bulk');

		foreach ($this->requestCsv('stable/scores-bulk') as $item) {
			$object = $this->map(Scores::class, new ScoresMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/peers-bulk
	 * @return iterable<int, PeersBulk>
	 */
	public function peersBulk(): iterable
	{
		$url = $this->buildUrlWithoutApiKey('stable/peers-bulk');

		foreach ($this->requestCsv('stable/peers-bulk') as $item) {
			$item['peers'] = $item['peers'] === '' ? [] : explode(',', $item['peers']);

			$object = $this->map(PeersBulk::class, new PeersBulkMapper(), $item, $url, true);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @template T
	 * @param callable(int<0, max> $page): iterable<T> $callback
	 * @param int<0, max> $initialPage
	 * @param int<0, max>|null $maxPage Inclusive
	 * @param int<1, max>|null $maxPageGuard Safety guard to prevent infinite loops
	 * @param-immediately-invoked-callable $callback
	 * @return iterable<int, T>
	 */
	public function iteratePages(callable $callback, int $initialPage = 0, ?int $maxPage = null, ?int $maxPageGuard = null): iterable
	{
		$page = $initialPage;

		while (true) {
			$result = $callback($page);
			$isEmpty = true;

			foreach ($result as $item) {
				$isEmpty = false;
				yield $item;
			}

			if ($isEmpty) {
				break;
			}

			$page++;
			if ($maxPage !== null && $page > $maxPage) {
				break;
			}
			if ($maxPageGuard !== null && $page - $initialPage >= $maxPageGuard) {
				throw new LogicException(sprintf('Maximum page of %d reached', $page));
			}
		}
	}

	/**
	 * @template TRet of object
	 * @param class-string<TRet> $payload
	 * @param Type<TRet> $type
	 * @return TRet|null
	 */
	private function map(
		string $payload,
		Type $type,
		mixed $value,
		string $url,
		bool $isCsv = false,
		?TypeConfig $config = null,
	): ?object
	{
		$config ??= $isCsv ? $this->csvTypeConfig : $this->jsonTypeConfig;

		$value = $this->schemaProcessor->parse($value, $type, $config, true);
		if ($value instanceof ErrorElement) {
			$exception = new Exception\UnexpectedResponseContentException(
				sprintf('%s: %s', $payload, TypeSchemaErrorFormatter::prettyString($value, '')),
				null,
				$url,
			);
			if ($this->strictMode === true) {
				throw $exception;
			}

			$this->invalidArgumentHandler?->handle($exception);
			return null;
		}

		return $value;
	}

	private function createTypeConfigForStatements(): TypeConfig
	{
		return new TypeConfig(new ConfigurableConversionStrategy(
			new LenientStringConverter(),
			new LenientAndEmptyNumberConverter(new LenientNumberConverter()),
			new LenientBoolConverter(),
			new LenientNullConverter(),
			new LenientArrayConverter(),
			new LenientObjectSupervisor(),
		), options: [
			SourceFormat::class => new SourceFormat('csv'),
		]);
	}

	/**
	 * @param array<string, scalar|null> $query
	 * @return iterable<array-key, mixed>
	 */
	private function requestJson(string $path, array $query = []): iterable
	{
		$response = $this->request($path, $query);

		FmpPromise::wait();

		if (!$this->builtInJsonParser) {
			foreach ($this->largeResponseParser->parseJson($this->httpClient, $response) as $item) {
				yield $item;
			}
		} else {
			yield from $response->toArray();
		}

		$response->cancel();
	}

	/**
	 * @param array<string, scalar|null> $query
	 * @return iterable<array<string, string>>
	 */
	private function requestCsv(string $path, array $query = []): iterable
	{
		$response = $this->request($path, $query);

		yield from $this->processCsvResponse($response);
	}

	/**
	 * @return iterable<array<string, string>>
	 */
	private function processCsvResponse(ResponseInterface $response): iterable
	{
		FmpPromise::wait();

		foreach ($this->largeResponseParser->parseCsv($this->httpClient, $response) as $item) {
			yield $item;
		}

		$response->cancel();
	}

	/**
	 * @param array<string, scalar|null> $query
	 */
	public function request(string $path, array $query = []): ResponseInterface
	{
		$query['apikey'] = $this->secret;

		return $this->httpClient->request('GET', 'https://financialmodelingprep.com/' . $path, [
			'query' => $query,
		]);
	}

	/**
	 * @param array<string, scalar|null> $query
	 */
	private function buildUrlWithoutApiKey(string $path, array $query = []): string
	{
		$url = 'https://financialmodelingprep.com/' . $path;

		if ($query) {
			if (str_contains($path, '?')) {
				$url .= '&' . http_build_query($query);
			} else {
				$url .= '?' . http_build_query($query);
			}
		}

		return $url;
	}

	private function removeTtmSuffix(mixed $item): mixed
	{
		if (!is_array($item)) {
			return $item;
		}

		$ret = [];
		foreach ($item as $key => $value) {
			if (is_string($key) && str_ends_with($key, 'TTM')) {
				$key = substr($key, 0, -3);
			}

			$ret[$key] = $value;
		}
		return $ret;
	}

}
