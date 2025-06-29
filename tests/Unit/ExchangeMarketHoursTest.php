<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\ExchangeMarketHours;
use Tests\TestCase;

final class ExchangeMarketHoursTest extends TestCase
{

	public function testGetAllExchangeMarketHours(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/all-exchange-market-hours.json');

		$marketHours = iterator_to_array($client->getAllExchangeMarketHours());

		$this->assertNotEmpty($marketHours);
		$this->assertContainsOnlyInstancesOf(ExchangeMarketHours::class, $marketHours);

		$firstExchange = $marketHours[0];
		$this->assertNotEmpty($firstExchange->exchange);
		$this->assertNotEmpty($firstExchange->name);
		$this->assertNotEmpty($firstExchange->openingHour);
		$this->assertNotEmpty($firstExchange->closingHour);
		$this->assertNotEmpty($firstExchange->timezone);
		$this->assertIsBool($firstExchange->isMarketOpen);

		$exchangeWithAdditional = null;
		foreach ($marketHours as $exchange) {
			if ($exchange->openingAdditional !== null) {
				$exchangeWithAdditional = $exchange;
				break;
			}
		}

		$this->assertNotNull($exchangeWithAdditional);
		$this->assertNotNull($exchangeWithAdditional->openingAdditional);
		$this->assertNotNull($exchangeWithAdditional->closingAdditional);
	}

	public function testGetAllExchangeMarketHoursWhenClosed(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/all_exchange_market_hours.closed.json');

		$marketHours = iterator_to_array($client->getAllExchangeMarketHours());

		$this->assertNotEmpty($marketHours);
		$this->assertContainsOnlyInstancesOf(ExchangeMarketHours::class, $marketHours);

		foreach ($marketHours as $exchange) {
			$this->assertNotEmpty($exchange->exchange);
			$this->assertNotEmpty($exchange->name);
			$this->assertEquals('CLOSED', $exchange->openingHour);
			$this->assertEquals('CLOSED', $exchange->closingHour);
			$this->assertNotEmpty($exchange->timezone);
			$this->assertFalse($exchange->isMarketOpen);
			$this->assertNull($exchange->openingAdditional);
			$this->assertNull($exchange->closingAdditional);
		}

		$firstExchange = $marketHours[0];
		$this->assertEquals('ASX', $firstExchange->exchange);
		$this->assertEquals('Australian Stock Exchange', $firstExchange->name);
		$this->assertEquals('Australia/Sydney', $firstExchange->timezone);
	}

}