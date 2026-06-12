<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\HistoricalPriceEodNonSplitAdjusted;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class HistoricalPriceEodNonSplitAdjustedTest extends TestCase
{

	public function testHistoricalPriceEodNonSplitAdjusted(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-non-split-adjusted-aapl.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEodNonSplitAdjusted(
			'AAPL',
			new DateTimeImmutable('2026-05-25'),
			new DateTimeImmutable('2026-06-10')
		));

		$this->assertCount(12, $historicalPrices);

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2026-06-10',
			adjOpen: 290.74,
			adjHigh: 294.75,
			adjLow: 287.38,
			adjClose: 291.58,
			volume: 52793300,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2026-05-29',
			adjOpen: 311.77,
			adjHigh: 315.0,
			adjLow: 309.53,
			adjClose: 312.06,
			volume: 70026800,
		))->toArray(), $historicalPrices[8]->toArray());

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2026-05-26',
			adjOpen: 309.56,
			adjHigh: 311.82,
			adjLow: 307.67,
			adjClose: 308.33,
			volume: 48000500,
		))->toArray(), $historicalPrices[11]->toArray());
	}

	public function testHistoricalPriceEodNonSplitAdjustedPaginatesWhenRangeExceedsRecordLimit(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-non-split-adjusted-aapl-paginated-1.json');
		$secondPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-non-split-adjusted-aapl-paginated-2.json');
		$mockClient = new MockHttpClient([$firstPage, $secondPage]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$historicalPrices = iterator_to_array($client->historicalPriceEodNonSplitAdjusted(
			'AAPL',
			new DateTimeImmutable('2006-01-01'),
			new DateTimeImmutable('2026-06-12')
		));

		$this->assertCount(5143, $historicalPrices);
		$this->assertSame(2, $mockClient->getRequestsCount());
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-price-eod/non-split-adjusted?symbol=AAPL&from=2006-01-01&to=2026-06-12&apikey=SECRET',
			$firstPage->getRequestUrl(),
		);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-price-eod/non-split-adjusted?symbol=AAPL&from=2006-01-01&to=2006-07-27&apikey=SECRET',
			$secondPage->getRequestUrl(),
		);

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2026-06-12',
			adjOpen: 296.08,
			adjHigh: 297.14,
			adjLow: 290.01,
			adjClose: 290.61,
			volume: 21283140,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2006-07-28',
			adjOpen: 63.84,
			adjHigh: 65.8,
			adjLow: 63.56,
			adjClose: 65.52,
			volume: 24701368,
		))->toArray(), $historicalPrices[4999]->toArray());

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2006-07-27',
			adjOpen: 64.4,
			adjHigh: 64.96,
			adjLow: 62.72,
			adjClose: 63.28,
			volume: 26253242,
		))->toArray(), $historicalPrices[5000]->toArray());

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2006-01-03',
			adjOpen: 72.24,
			adjHigh: 74.76,
			adjLow: 72.24,
			adjClose: 74.76,
			volume: 28836172,
		))->toArray(), $historicalPrices[5142]->toArray());
	}

}
