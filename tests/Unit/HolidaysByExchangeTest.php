<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\HolidayByExchange;
use Tests\TestCase;

final class HolidaysByExchangeTest extends TestCase
{

	public function testHolidaysByExchange(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/holidays-by-exchange.json');

		$holidays = iterator_to_array($client->holidaysByExchange('NASDAQ'));

		$this->assertNotEmpty($holidays);
		$this->assertContainsOnlyInstancesOf(HolidayByExchange::class, $holidays);

		$this->assertSame((new HolidayByExchange(
			exchange: 'NASDAQ',
			date: '2026-05-25',
			name: 'Memorial Day',
			isClosed: true,
			adjOpenTime: null,
			adjCloseTime: null,
			isFullyClosed: null,
		))->toArray(), $holidays[0]->toArray());
	}

	public function testHolidaysByExchangeEarlyClose(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/holidays-by-exchange.json');

		$holidays = iterator_to_array($client->holidaysByExchange('NASDAQ'));

		$earlyClose = null;
		foreach ($holidays as $holiday) {
			if ($holiday->isFullyClosed !== null) {
				$earlyClose = $holiday;
				break;
			}
		}

		$this->assertNotNull($earlyClose);
		$this->assertSame((new HolidayByExchange(
			exchange: 'NASDAQ',
			date: '2025-12-24',
			name: 'Christmas Early Close',
			isClosed: null,
			adjOpenTime: null,
			adjCloseTime: '13:00',
			isFullyClosed: false,
		))->toArray(), $earlyClose->toArray());
	}

}
