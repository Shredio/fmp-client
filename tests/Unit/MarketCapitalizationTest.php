<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\MarketCapitalization;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class MarketCapitalizationTest extends TestCase
{

	public function testHistoricalMarketCapitalization(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/historical-market-capitalization-sap-de.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$history = iterator_to_array($client->historicalMarketCapitalization(
			'SAP.DE',
			new DateTimeImmutable('2026-09-01'),
			new DateTimeImmutable('2026-10-13'),
		));

		$this->assertCount(31, $history);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-market-capitalization?symbol=SAP.DE&from=2026-09-01&to=2026-10-13&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new MarketCapitalization(
			symbol: 'SAP.DE',
			date: '2026-10-13',
			marketCap: 224811940000,
		))->toArray(), $history[0]->toArray());
		$this->assertSame((new MarketCapitalization(
			symbol: 'SAP.DE',
			date: '2026-09-01',
			marketCap: 212451780000,
		))->toArray(), $history[30]->toArray());
	}

	public function testHistoricalMarketCapitalizationPaginatesWhenRangeExceedsRecordLimit(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-market-capitalization-aapl-paginated-1.json');
		$secondPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-market-capitalization-aapl-paginated-2.json');
		$mockClient = new MockHttpClient([$firstPage, $secondPage]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$history = iterator_to_array($client->historicalMarketCapitalization(
			'AAPL',
			new DateTimeImmutable('2006-01-01'),
			new DateTimeImmutable('2026-10-13'),
		));

		$this->assertCount(5226, $history);
		$this->assertSame(2, $mockClient->getRequestsCount());
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-market-capitalization?symbol=AAPL&from=2006-01-01&to=2026-10-13&apikey=SECRET',
			$firstPage->getRequestUrl(),
		);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-market-capitalization?symbol=AAPL&from=2006-01-01&to=2006-11-23&apikey=SECRET',
			$secondPage->getRequestUrl(),
		);
		$this->assertSame((new MarketCapitalization(
			symbol: 'AAPL',
			date: '2026-10-12',
			marketCap: 5009706839550,
		))->toArray(), $history[0]->toArray());
		$this->assertSame((new MarketCapitalization(
			symbol: 'AAPL',
			date: '2006-11-24',
			marketCap: 78530187960,
		))->toArray(), $history[4999]->toArray());
		$this->assertSame((new MarketCapitalization(
			symbol: 'AAPL',
			date: '2006-11-22',
			marketCap: 77569574040,
		))->toArray(), $history[5000]->toArray());
		$this->assertSame((new MarketCapitalization(
			symbol: 'AAPL',
			date: '2006-01-03',
			marketCap: 62866431600,
		))->toArray(), $history[5225]->toArray());
	}

	public function testHistoricalMarketCapitalizationStopsWhenContinuationReturnsNoRecords(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-market-capitalization-aapl-paginated-1.json');
		$mockClient = new MockHttpClient([$firstPage, new MockResponse('[]')]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$history = iterator_to_array($client->historicalMarketCapitalization(
			'AAPL',
			new DateTimeImmutable('2006-01-01'),
			new DateTimeImmutable('2026-10-13'),
		));

		$this->assertCount(5000, $history);
		$this->assertSame(2, $mockClient->getRequestsCount());
	}

	public function testHistoricalMarketCapitalizationDoesNotPaginateBeyondFromDate(): void
	{
		$firstPage = MockResponse::fromFile(__DIR__ . '/fixtures/historical-market-capitalization-aapl-paginated-1.json');
		$mockClient = new MockHttpClient([$firstPage]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		// the oldest record of the page is dated 2006-11-24
		$history = iterator_to_array($client->historicalMarketCapitalization(
			'AAPL',
			new DateTimeImmutable('2006-11-24'),
			new DateTimeImmutable('2026-10-13'),
		));

		$this->assertCount(5000, $history);
		$this->assertSame(1, $mockClient->getRequestsCount());
	}

	public function testHistoricalMarketCapitalizationOfUnknownSymbol(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->historicalMarketCapitalization(
			'UNKNOWN',
			new DateTimeImmutable('2026-09-01'),
			new DateTimeImmutable('2026-10-13'),
		)));
	}

	public function testAmountWithFloatingPointTailIsRounded(): void
	{
		$response = new MockResponse('[{"symbol":"GOOG","date":"2026-10-12","marketCap":4216133870848.9995}]');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$capitalizations = iterator_to_array($client->marketCapitalizationBatch(['GOOG']));

		$this->assertCount(1, $capitalizations);
		$this->assertSame(4216133870849, $capitalizations[0]->marketCap);
	}

	public function testMarketCapitalizationBatch(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/market-capitalization-batch.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$capitalizations = iterator_to_array($client->marketCapitalizationBatch(
			['AAPL', 'MSFT', 'SAP.DE', '7203.T', 'NSRGY', 'SPY', 'BTCUSD', '^GSPC', 'UNKNOWN123'],
		));

		// UNKNOWN123 is left out by the API
		$this->assertCount(8, $capitalizations);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/market-capitalization-batch?symbols=AAPL%2CMSFT%2CSAP.DE%2C7203.T%2CNSRGY%2CSPY%2CBTCUSD%2C%5EGSPC%2CUNKNOWN123&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new MarketCapitalization(
			symbol: 'AAPL',
			date: '2026-10-12',
			marketCap: 5007947775320,
		))->toArray(), $capitalizations[0]->toArray());
		$this->assertSame((new MarketCapitalization(
			symbol: 'SAP.DE',
			date: '2026-10-13',
			marketCap: 224977497283,
		))->toArray(), $capitalizations[2]->toArray());
		// an index has no market capitalization
		$this->assertSame((new MarketCapitalization(
			symbol: '^GSPC',
			date: '2026-10-12',
			marketCap: 0,
		))->toArray(), $capitalizations[7]->toArray());
	}

	public function testNoSymbolsSendNoRequest(): void
	{
		$mockClient = new MockHttpClient([]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$this->assertSame([], iterator_to_array($client->marketCapitalizationBatch([])));
		$this->assertSame(0, $mockClient->getRequestsCount());
	}

}
