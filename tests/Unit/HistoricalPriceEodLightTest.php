<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\HistoricalPriceEodLight;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class HistoricalPriceEodLightTest extends TestCase
{

	public function testHistoricalPriceEodLight(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-light-aapl.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEodLight(
			'AAPL',
			new DateTimeImmutable('2026-05-25'),
			new DateTimeImmutable('2026-06-10')
		));

		$this->assertCount(12, $historicalPrices);

		$this->assertSame((new HistoricalPriceEodLight(
			symbol: 'AAPL',
			date: '2026-06-10',
			price: 291.58,
			volume: 52793300,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEodLight(
			symbol: 'AAPL',
			date: '2026-05-29',
			price: 312.06,
			volume: 70026800,
		))->toArray(), $historicalPrices[8]->toArray());

		$this->assertSame((new HistoricalPriceEodLight(
			symbol: 'AAPL',
			date: '2026-05-26',
			price: 308.33,
			volume: 48000500,
		))->toArray(), $historicalPrices[11]->toArray());
	}

	public function testHistoricalPriceEodLightPaginatesWhenRangeExceedsRecordLimit(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-light-aapl-paginated-1.json');
		$secondPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-price-eod-light-aapl-paginated-2.json');
		$mockClient = new MockHttpClient([$firstPage, $secondPage]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$historicalPrices = iterator_to_array($client->historicalPriceEodLight(
			'AAPL',
			new DateTimeImmutable('2006-01-01'),
			new DateTimeImmutable('2026-06-12')
		));

		$this->assertCount(5143, $historicalPrices);
		$this->assertSame(2, $mockClient->getRequestsCount());
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-price-eod/light?symbol=AAPL&from=2006-01-01&to=2026-06-12&apikey=SECRET',
			$firstPage->getRequestUrl(),
		);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-price-eod/light?symbol=AAPL&from=2006-01-01&to=2006-07-27&apikey=SECRET',
			$secondPage->getRequestUrl(),
		);

		$this->assertSame((new HistoricalPriceEodLight(
			symbol: 'AAPL',
			date: '2026-06-12',
			price: 291.13,
			volume: 38784789,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEodLight(
			symbol: 'AAPL',
			date: '2006-07-28',
			price: 2.34,
			volume: 691638320,
		))->toArray(), $historicalPrices[4999]->toArray());

		$this->assertSame((new HistoricalPriceEodLight(
			symbol: 'AAPL',
			date: '2006-07-27',
			price: 2.26,
			volume: 735090780,
		))->toArray(), $historicalPrices[5000]->toArray());

		$this->assertSame((new HistoricalPriceEodLight(
			symbol: 'AAPL',
			date: '2006-01-03',
			price: 2.67,
			volume: 807412836,
		))->toArray(), $historicalPrices[5142]->toArray());
	}

}
