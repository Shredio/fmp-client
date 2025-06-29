<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\AvailableExchange;
use Tests\TestCase;

final class AvailableExchangesTest extends TestCase
{

	public function testAvailableExchanges(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/available-exchanges.json');

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
