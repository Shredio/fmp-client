<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\CacheFmpClient;
use Shredio\FmpClient\Payload\AvailableExchange;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Tests\TestCase;

final class CacheFmpClientTest extends TestCase
{

	public function testCache(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/available-exchanges.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
		);

		// First request
		$exchanges = iterator_to_array($client->availableExchanges());
		$this->assertCount(71, $exchanges);
		$this->assertSame((new AvailableExchange(
			exchange: 'AMEX',
			name: 'New York Stock Exchange Arca',
			countryName: 'United States of America',
			countryCode: 'US',
			symbolSuffix: null,
			delay: 'Real-time',
		))->toArray(), $exchanges[0]->toArray());

		$this->assertTrue($cache->has('fmp-client.availableExchanges'), 'Cache should have the key after first request');

		// Second request
		$exchanges = iterator_to_array($client->availableExchanges());
		$this->assertCount(71, $exchanges);
		$this->assertSame((new AvailableExchange(
			exchange: 'AMEX',
			name: 'New York Stock Exchange Arca',
			countryName: 'United States of America',
			countryCode: 'US',
			symbolSuffix: null,
			delay: 'Real-time',
		))->toArray(), $exchanges[0]->toArray());
	}

}
