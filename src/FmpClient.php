<?php declare(strict_types = 1);

namespace Shredio\FmpClient;

use DateTimeImmutable;
use LogicException;
use Psr\Log\LoggerInterface;
use SensitiveParameter;
use Shredio\FmpClient\Calendar\FmpCalendarPaginator;
use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
use Shredio\FmpClient\Enum\TimeInterval;
use Shredio\FmpClient\Exception\InvalidArgumentHandler;
use Shredio\FmpClient\Mapper\FmpPayloadMapper;
use Shredio\FmpClient\Payload\AnalystEstimate;
use Shredio\FmpClient\Payload\AvailableExchange;
use Shredio\FmpClient\Payload\BalanceSheetStatement;
use Shredio\FmpClient\Payload\BatchExchangeDetailedQuote;
use Shredio\FmpClient\Payload\BatchExchangeQuote;
use Shredio\FmpClient\Payload\BatchForexQuote;
use Shredio\FmpClient\Payload\CashFlowStatement;
use Shredio\FmpClient\Payload\CompanyProfile;
use Shredio\FmpClient\Payload\Cryptocurrency;
use Shredio\FmpClient\Payload\DividendsCalendarItem;
use Shredio\FmpClient\Payload\EarningsCalendarItem;
use Shredio\FmpClient\Payload\EodQuote;
use Shredio\FmpClient\Payload\ExchangeMarketHours;
use Shredio\FmpClient\Payload\FinancialStatementSymbol;
use Shredio\FmpClient\Payload\HistoricalChart;
use Shredio\FmpClient\Payload\HistoricalPriceEod;
use Shredio\FmpClient\Payload\IncomeStatement;
use Shredio\FmpClient\Payload\Index;
use Shredio\FmpClient\Payload\KeyMetrics;
use Shredio\FmpClient\Payload\KeyMetricsTtm;
use Shredio\FmpClient\Payload\LatestFinancialStatement;
use Shredio\FmpClient\Payload\Ratios;
use Shredio\FmpClient\Payload\RatiosTtm;
use Shredio\FmpClient\Payload\Scores;
use Shredio\FmpClient\Payload\SplitsCalendarItem;
use Shredio\FmpClient\Payload\Stock;
use Shredio\FmpClient\Promise\FmpPromise;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\InvalidArgumentException;

final readonly class FmpClient
{

	private Parser\LargeResponseParser $largeResponseParser;

	private FmpPayloadMapper $mapper;

	public function __construct(
		private HttpClientInterface $httpClient,
		#[SensitiveParameter]
		private string $secret,
		private ?InvalidArgumentHandler $invalidArgumentHandler = null,
		private bool $strictMode = false,
	)
	{
		$this->largeResponseParser = new Parser\LargeResponseParser();
		$this->mapper = new FmpPayloadMapper();
	}

	public function withStrictMode(bool $strictMode): self
	{
		return new self(
			$this->httpClient,
			$this->secret,
			$this->invalidArgumentHandler,
			$strictMode,
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
		foreach ($this->requestJson('stable/available-exchanges') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->availableExchange($item));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/all-exchange-market-hours
	 * @return iterable<int, ExchangeMarketHours>
	 */
	public function getAllExchangeMarketHours(): iterable
	{
		foreach ($this->requestJson('stable/all-exchange-market-hours') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->exchangeMarketHours($item));
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
		foreach ($this->requestJson('stable/index-list') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->index($item));
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
		foreach ($this->requestJson('stable/cryptocurrency-list') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->cryptocurrency($item));
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
		foreach ($this->requestJson('stable/stock-list') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->stock($item));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/profile
	 * @return iterable<int, CompanyProfile>
	 */
	public function companyProfile(string $symbols): iterable
	{
		foreach ($this->requestJson('stable/profile', ['symbol' => $symbols]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->companyProfile($item, false));
			if ($object !== null) {
				yield $object;
			}
		}
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

			foreach ($this->processCsvResponse($response) as $item) {
				$object = $this->safeInvoke(fn () => $this->mapper->companyProfile($item, true));
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
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatement(string $symbol): iterable
	{
		foreach ($this->requestJson('stable/balance-sheet-statement', ['symbol' => $symbol]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->balanceSheetStatement($item));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/balance-sheet-statement-bulk
	 * @return iterable<int, BalanceSheetStatement>
	 */
	public function balanceSheetStatementBulk(string $year, Period $period = Period::FY): iterable
	{
		foreach ($this->requestCsv('stable/balance-sheet-statement-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->balanceSheetStatement($item, true));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/analyst-estimates
	 * @return iterable<int, AnalystEstimate>
	 */
	public function getAnalystEstimates(string $symbol, string $period = 'annual', int $page = 0, int $limit = 6): iterable
	{
		foreach ($this->requestJson('stable/analyst-estimates', [
			'symbol' => $symbol,
			'period' => $period,
			'page' => $page,
			'limit' => $limit,
		]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->analystEstimate($item));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/dividends-calendar
	 * @return iterable<int, DividendsCalendarItem>
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

			foreach ($values as $item) {
				$object = $this->safeInvoke(fn() => $this->mapper->dividendsCalendar($item));
				if ($object !== null) {
					$lastStringDate = $object->date;
					$count++;
					yield $object;
				}
			}
		} while ($paginator->next($count, $lastStringDate, $logger));
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

			foreach ($values as $item) {
				$object = $this->safeInvoke(fn() => $this->mapper->earningsCalendar($item));
				if ($object !== null) {
					$lastStringDate = $object->date;
					$count++;
					yield $object;
				}
			}
		} while ($paginator->next($count, $lastStringDate, $logger));
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

			foreach ($values as $item) {
				$object = $this->safeInvoke(fn() => $this->mapper->splitsCalendar($item));
				if ($object !== null) {
					$lastStringDate = $object->date;
					$count++;
					yield $object;
				}
			}
		} while ($paginator->next($count, $lastStringDate, $logger));
	}

	/**
	 * @see https://financialmodelingprep.com/stable/latest-financial-statements
	 * @return iterable<int, LatestFinancialStatement>
	 */
	public function latestFinancialStatements(int $page = 0, int $limit = 250): iterable
	{
		foreach ($this->requestJson('stable/latest-financial-statements', ['page' => $page, 'limit' => $limit]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->latestFinancialStatement($item));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatement(string $symbol): iterable
	{
		foreach ($this->requestJson('stable/income-statement', ['symbol' => $symbol]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->incomeStatement($item));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/income-statement-bulk
	 * @return iterable<int, IncomeStatement>
	 */
	public function incomeStatementBulk(string $year, Period $period = Period::FY): iterable
	{
		foreach ($this->requestCsv('stable/income-statement-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->incomeStatement($item, true));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatement(string $symbol): iterable
	{
		foreach ($this->requestJson('stable/cash-flow-statement', ['symbol' => $symbol]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->cashFlowStatement($item));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://financialmodelingprep.com/stable/cash-flow-statement-bulk
	 * @return iterable<int, CashFlowStatement>
	 */
	public function cashFlowStatementBulk(string $year, Period $period = Period::FY): iterable
	{
		foreach ($this->requestCsv('stable/cash-flow-statement-bulk', ['year' => $year, 'period' => $period->value]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->cashFlowStatement($item, true));
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
		foreach ($this->requestCsv('stable/eod-bulk', ['date' => $date->format('Y-m-d')]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->eodBulkQuote($item, true));
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
		foreach ($this->requestJson('stable/financial-statement-symbol-list') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->financialStatementSymbol($item));
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
		foreach ($this->requestJson('stable/batch-exchange-quote', ['exchange' => $exchange]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->batchExchangeQuote($item));
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
		foreach ($this->requestJson('stable/batch-exchange-quote', ['exchange' => $exchange, 'short' => 'false']) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->batchExchangeDetailedQuote($item));
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
		foreach ($this->requestJson('stable/batch-forex-quotes') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->batchForexQuote($item));
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
		foreach ($this->requestJson('stable/historical-price-eod/full', [
			'symbol' => $symbol,
			'from' => $from->format('Y-m-d'),
			'to' => $to->format('Y-m-d'),
		]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->historicalPriceEod($item));
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
		foreach ($this->requestJson('stable/historical-chart/' . $interval->value, [
			'symbol' => $symbol,
			'from' => $from->format('Y-m-d'),
			'to' => $to->format('Y-m-d'),
		]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->historicalChart($item));
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
		foreach ($this->requestJson('stable/key-metrics', [
			'symbol' => $symbol,
			'limit' => $limit,
			'period' => $period->value,
		]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->keyMetrics($item));
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
		foreach ($this->requestJson('stable/key-metrics-ttm', ['symbol' => $symbol]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->keyMetricsTtm($item));
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
		foreach ($this->requestCsv('stable/key-metrics-ttm-bulk') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->keyMetricsTtm($item, true));
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
		foreach ($this->requestJson('stable/ratios', [
			'symbol' => $symbol,
			'limit' => $limit,
			'period' => $period->value,
		]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->ratios($item));
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
		foreach ($this->requestJson('stable/ratios-ttm', ['symbol' => $symbol]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->ratiosTtm($item));
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
		foreach ($this->requestCsv('stable/ratios-ttm-bulk') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->ratiosTtm($item, true));
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
		foreach ($this->requestJson('stable/financial-scores', ['symbol' => $symbol]) as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->scores($item));
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
		foreach ($this->requestCsv('stable/scores-bulk') as $item) {
			$object = $this->safeInvoke(fn() => $this->mapper->scores($item, true));
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @template TRet of object
	 * @param callable(): TRet $fn
	 * @return TRet|null
	 */
	private function safeInvoke(callable $fn): ?object
	{
		if ($this->strictMode === true) {
			try {
				return $fn();
			} catch (InvalidArgumentException $exception) {
				throw new Exception\InvalidArgumentException(
					$exception->getMessage(),
					$exception->getCode(),
					$exception,
				);
			}
		}

		try {
			return $fn();
		} catch (InvalidArgumentException $exception) {
			$this->invalidArgumentHandler?->handle(
				new Exception\InvalidArgumentException(
					$exception->getMessage(),
					$exception->getCode(),
					$exception,
				)
			);

			return null;
		}
	}

	/**
	 * @param array<string, scalar|null> $query
	 * @return iterable<array-key, mixed>
	 */
	private function requestJson(string $path, array $query = []): iterable
	{
		$response = $this->request($path, $query);

		FmpPromise::wait();

		foreach ($this->largeResponseParser->parseJson($this->httpClient, $response) as $item) {
			yield $item;
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
	private function request(string $path, array $query = []): ResponseInterface
	{
		$query['apikey'] = $this->secret;

		return $this->httpClient->request('GET', 'https://financialmodelingprep.com/' . $path, [
			'query' => $query,
		]);
	}

}
