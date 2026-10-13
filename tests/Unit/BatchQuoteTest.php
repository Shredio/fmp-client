<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Quote;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class BatchQuoteTest extends TestCase
{

	public function testBatchQuote(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/batch-quote.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$quotes = iterator_to_array($client->batchQuote(
			['AAPL', 'SAP.DE', '7203.T', '^GSPC', 'BTCUSD', 'EURUSD', 'GCUSD', 'SPY', 'VWCE.DE', 'NSRGY', 'UNKNOWN123'],
		));

		// UNKNOWN123 is left out by the API
		$this->assertSame(
			['AAPL', 'SAP.DE', '7203.T', '^GSPC', 'BTCUSD', 'EURUSD', 'GCUSD', 'SPY', 'VWCE.DE', 'NSRGY'],
			array_map(static fn (Quote $quote): string => $quote->symbol, $quotes),
		);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/batch-quote?symbols=AAPL%2CSAP.DE%2C7203.T%2C%5EGSPC%2CBTCUSD%2CEURUSD%2CGCUSD%2CSPY%2CVWCE.DE%2CNSRGY%2CUNKNOWN123&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new Quote(
			symbol: 'AAPL',
			name: 'Apple Inc.',
			exchange: 'NASDAQ',
			price: 340.97,
			changePercentage: 1.28624,
			change: 4.33,
			volume: 25796997,
			dayLow: 337.6,
			dayHigh: 342.63,
			yearHigh: 345.34,
			yearLow: 243.42,
			marketCap: 5007947775320,
			priceAvg50: 323.0298,
			priceAvg200: 291.0865,
			open: 338.16,
			previousClose: 336.64,
			timestamp: 1791835201,
		))->toArray(), $quotes[0]->toArray());
	}

	public function testWholePricesAndAssetsWithoutMarketCap(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/batch-quote.json');

		$quotes = iterator_to_array($client->batchQuote(['AAPL', 'SAP.DE', '7203.T', '^GSPC', 'BTCUSD', 'EURUSD', 'GCUSD']));

		// the price of Toyota arrives as the integer 2984
		$this->assertSame((new Quote(
			symbol: '7203.T',
			name: 'Toyota Motor Corp.',
			exchange: 'JPX',
			price: 2984.0,
			changePercentage: 2.52534,
			change: 73.5,
			volume: 25048800,
			dayLow: 2916.5,
			dayHigh: 2984.0,
			yearHigh: 4000.0,
			yearLow: 2686.0,
			marketCap: 35333700600320,
			priceAvg50: 3010.16,
			priceAvg200: 3163.44,
			open: 2930.0,
			previousClose: 2910.5,
			timestamp: 1791873000,
		))->toArray(), $quotes[2]->toArray());
		$this->assertSame('INDEX', $quotes[3]->exchange);
		$this->assertSame(0, $quotes[3]->marketCap);
		$this->assertSame('FOREX', $quotes[5]->exchange);
		$this->assertNull($quotes[5]->marketCap);
	}

	public function testDelistedSymbolsWithMissingValues(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/batch-quote-delisted.json');

		$quotes = iterator_to_array($client->batchQuote(['MTOR', 'MRO', 'RVI']));

		$this->assertCount(3, $quotes);
		$this->assertSame((new Quote(
			symbol: 'MTOR',
			name: 'Meritor, Inc.',
			exchange: 'NYSE',
			price: 36.5,
			changePercentage: 0.0274002,
			change: 0.00999832,
			volume: 2543288,
			dayLow: 36.49,
			dayHigh: 36.5,
			yearHigh: null,
			yearLow: null,
			marketCap: 0,
			priceAvg50: null,
			priceAvg200: null,
			open: 36.49,
			previousClose: 36.49,
			timestamp: 1659470401,
		))->toArray(), $quotes[0]->toArray());
		$this->assertNull($quotes[1]->change);
		$this->assertNull($quotes[1]->changePercentage);
		$this->assertNull($quotes[1]->open);
		$this->assertNull($quotes[1]->previousClose);
		// a fractional volume
		$this->assertSame(45424.207, $quotes[2]->volume);
	}

	public function testNoSymbolsSendNoRequest(): void
	{
		$mockClient = new MockHttpClient([]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$this->assertSame([], iterator_to_array($client->batchQuote([])));
		$this->assertSame(0, $mockClient->getRequestsCount());
	}

	public function testListLongerThanTheApiLimitIsSplitIntoSeveralRequests(): void
	{
		$requested = [];
		$mockClient = new MockHttpClient(static function (string $method, string $url) use (&$requested): MockResponse {
			parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
			$symbols = explode(',', is_string($query['symbols'] ?? null) ? $query['symbols'] : '');
			$requested[] = $symbols;

			return new MockResponse((string) json_encode(array_map(
				static fn (string $symbol): array => ['symbol' => $symbol, 'name' => $symbol, 'exchange' => 'NASDAQ'],
				$symbols,
			)));
		});
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		// four characters per symbol keep 1000 of them below the length limit of the URL
		$symbols = array_map(static fn (int $number): string => sprintf('S%03d', $number % 1000), range(0, 2499));
		$quotes = iterator_to_array($client->batchQuote($symbols));

		$this->assertSame([1000, 1000, 500], array_map(count(...), $requested));
		$this->assertSame($symbols, array_merge(...$requested));
		$this->assertCount(2500, $quotes);
		$this->assertSame('S499', $quotes[2499]->symbol);
	}

	public function testListOfLongSymbolsIsSplitBeforeTheUrlGetsTooLong(): void
	{
		$urls = [];
		$mockClient = new MockHttpClient(static function (string $method, string $url) use (&$urls): MockResponse {
			$urls[] = $url;

			return new MockResponse('[]');
		});
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		// 900 symbols of 12 characters, 15 bytes each once the separating comma is encoded as %2C
		$symbols = array_map(static fn (int $number): string => sprintf('0P%08d.L', $number), range(1, 900));
		iterator_to_array($client->batchQuote($symbols));

		$this->assertCount(2, $urls);
		foreach ($urls as $url) {
			$this->assertLessThan(8000, strlen($url));
		}

		parse_str((string) parse_url($urls[0], PHP_URL_QUERY), $firstQuery);
		parse_str((string) parse_url($urls[1], PHP_URL_QUERY), $secondQuery);
		$this->assertIsString($firstQuery['symbols']);
		$this->assertIsString($secondQuery['symbols']);
		$this->assertSame($symbols, [...explode(',', $firstQuery['symbols']), ...explode(',', $secondQuery['symbols'])]);
	}

}
