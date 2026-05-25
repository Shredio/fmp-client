<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\EconomicCalendarItem;
use Tests\TestCase;

final class EconomicCalendarTest extends TestCase
{

	public function testEconomicCalendar(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/economic-calendar-2026-05-20-to-2026-05-22.json');

		$items = [];
		foreach ($client->economicCalendar(new DateTimeImmutable('2026-05-20'), new DateTimeImmutable('2026-05-22')) as $item) {
			$items[] = $item;
		}

		$this->assertCount(388, $items);

		$expectedFirstItem = new EconomicCalendarItem(
			date: '2026-05-22 19:30:00',
			country: 'MX',
			event: 'CFTC MXN speculative net positions',
			currency: 'MXN',
			previous: 64.1,
			estimate: null,
			actual: 62.2,
			change: -1.9,
			impact: 'Low',
			changePercentage: -2.964,
			unit: 'K',
		);

		$this->assertSame($expectedFirstItem->toArray(), $items[0]->toArray());

		$expectedAllNumbersNullItem = new EconomicCalendarItem(
			date: '2026-05-22 15:00:00',
			country: 'US',
			event: 'Fed Waller Speech',
			currency: 'USD',
			previous: null,
			estimate: null,
			actual: null,
			change: null,
			impact: 'Medium',
			changePercentage: 0.0,
			unit: null,
		);

		$this->assertSame($expectedAllNumbersNullItem->toArray(), $items[24]->toArray());

		$expectedUnitNullItem = new EconomicCalendarItem(
			date: '2026-05-22 17:00:00',
			country: 'US',
			event: 'Baker Hughes Oil Rig Count (May/22)',
			currency: 'USD',
			previous: 415.0,
			estimate: 416.0,
			actual: 425.0,
			change: 10.0,
			impact: 'Low',
			changePercentage: 2.41,
			unit: null,
		);

		$this->assertSame($expectedUnitNullItem->toArray(), $items[22]->toArray());
	}

}
