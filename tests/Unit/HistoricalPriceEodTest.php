<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\HistoricalPriceEod;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class HistoricalPriceEodTest extends TestCase
{

	public function testHistoricalPriceEod(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-aapl.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'AAPL',
			new DateTimeImmutable('2025-01-01'),
			new DateTimeImmutable('2025-01-10')
		));

		$this->assertNotEmpty($historicalPrices);
		$this->assertCount(6, $historicalPrices);

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2025-01-10',
			open: 240.01,
			high: 240.16,
			low: 233.0,
			close: 236.85,
			volume: 61710900,
			change: -3.16,
			changePercent: -1.32,
			vwap: 237.505,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2025-01-08',
			open: 241.92,
			high: 243.71,
			low: 240.05,
			close: 242.7,
			volume: 37628940,
			change: 0.78,
			changePercent: 0.32242,
			vwap: 242.095,
		))->toArray(), $historicalPrices[1]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2025-01-02',
			open: 248.93,
			high: 249.1,
			low: 241.82,
			close: 243.85,
			volume: 55740731,
			change: -5.08,
			changePercent: -2.04,
			vwap: 245.925,
		))->toArray(), $historicalPrices[5]->toArray());
	}

	public function testHistoricalPriceEodForexPair(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-eurusd.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'EURUSD',
			new DateTimeImmutable('2026-06-25'),
			new DateTimeImmutable('2026-07-06')
		));

		$this->assertCount(10, $historicalPrices);

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'EURUSD',
			date: '2026-07-06',
			open: 1.14338,
			high: 1.14456,
			low: 1.14102,
			close: 1.14172,
			volume: 49574,
			change: -0.00166,
			changePercent: -0.14518358,
			vwap: 1.14,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'EURUSD',
			date: '2026-06-25',
			open: 1.13618,
			high: 1.13882,
			low: 1.13335,
			close: 1.13702,
			volume: 123282,
			change: 0.00084,
			changePercent: 0.07393195,
			vwap: 1.13634,
		))->toArray(), $historicalPrices[9]->toArray());
	}

	public function testHistoricalPriceEodWithNullChangePercent(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-gdstu.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'GDSTU',
			new DateTimeImmutable('2024-07-17'),
			new DateTimeImmutable('2024-07-19')
		));

		$this->assertCount(1, $historicalPrices);

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'GDSTU',
			date: '2024-07-18',
			open: 11.09,
			high: 11.09,
			low: 11.09,
			close: 11.09,
			volume: 0,
			change: 11.09,
			changePercent: null,
			vwap: 11.09,
		))->toArray(), $historicalPrices[0]->toArray());
	}

	public function testHistoricalPriceEodWithNullVwap(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-bancausd.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'BANCAUSD',
			new DateTimeImmutable('2021-05-28'),
			new DateTimeImmutable('2021-05-30')
		));

		$this->assertCount(3, $historicalPrices);

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'BANCAUSD',
			date: '2021-05-29',
			open: 0.0000481555,
			high: 0.0000523977,
			low: 0.0000481555,
			close: 0.000049898,
			volume: 40912,
			change: 0.00000174,
			changePercent: 3.62,
			vwap: null,
		))->toArray(), $historicalPrices[1]->toArray());

		$this->assertSame(0.000049651675, $historicalPrices[1]->vwap);
	}

	public function testHistoricalPriceEodPaginatesWhenRangeExceedsRecordLimit(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-aapl-paginated-1.json');
		$secondPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-aapl-paginated-2.json');
		$mockClient = new MockHttpClient([$firstPage, $secondPage]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'AAPL',
			new DateTimeImmutable('2006-01-01'),
			new DateTimeImmutable('2026-06-12')
		));

		$this->assertCount(5143, $historicalPrices);
		$this->assertSame(2, $mockClient->getRequestsCount());
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-price-eod/full?symbol=AAPL&from=2006-01-01&to=2026-06-12&apikey=SECRET',
			$firstPage->getRequestUrl(),
		);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-price-eod/full?symbol=AAPL&from=2006-01-01&to=2006-07-27&apikey=SECRET',
			$secondPage->getRequestUrl(),
		);

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2026-06-12',
			open: 296.08,
			high: 297.14,
			low: 290.01,
			close: 290.61,
			volume: 21283140,
			change: -5.47,
			changePercent: -1.84747,
			vwap: 292.59,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2006-07-28',
			open: 2.28,
			high: 2.35,
			low: 2.27,
			close: 2.34,
			volume: 691638320,
			change: 0.058929,
			changePercent: 2.63,
			vwap: 2.31,
		))->toArray(), $historicalPrices[4999]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2006-07-27',
			open: 2.3,
			high: 2.32,
			low: 2.24,
			close: 2.26,
			volume: 735090780,
			change: -0.039465,
			changePercent: -1.74,
			vwap: 2.28,
		))->toArray(), $historicalPrices[5000]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2006-01-03',
			open: 2.58,
			high: 2.67,
			low: 2.58,
			close: 2.67,
			volume: 807412836,
			change: 0.084643,
			changePercent: 3.49,
			vwap: 2.625,
		))->toArray(), $historicalPrices[5142]->toArray());
	}

	public function testHistoricalPriceEodStopsWhenContinuationReturnsNoRecords(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-aapl-paginated-1.json');
		$secondPage = new MockResponse('[]');
		$mockClient = new MockHttpClient([$firstPage, $secondPage]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'AAPL',
			new DateTimeImmutable('2006-01-01'),
			new DateTimeImmutable('2026-06-12')
		));

		$this->assertCount(5000, $historicalPrices);
		$this->assertSame(2, $mockClient->getRequestsCount());
	}

	public function testHistoricalPriceEodDoesNotPaginateBeyondFromDate(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-aapl-paginated-1.json');
		$mockClient = new MockHttpClient([$firstPage]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'AAPL',
			new DateTimeImmutable('2006-07-28'),
			new DateTimeImmutable('2026-06-12')
		));

		$this->assertCount(5000, $historicalPrices);
		$this->assertSame(1, $mockClient->getRequestsCount());
	}

}