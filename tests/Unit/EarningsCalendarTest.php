<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\EarningsCalendarItem;
use Tests\TestCase;

final class EarningsCalendarTest extends TestCase
{

	public function testEarningsCalendar(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/earnings-calendar-2026-05-22.json');

		$items = [];
		foreach ($client->earningsCalendar(new DateTimeImmutable('2026-05-22'), new DateTimeImmutable('2026-05-22')) as $item) {
			$items[] = $item;
		}

		$this->assertCount(639, $items);

		$expectedFirstItem = new EarningsCalendarItem(
			symbol: 'MWG',
			date: '2026-05-22',
			epsActual: -0.0297,
			epsEstimated: null,
			revenueActual: 14209920,
			revenueEstimated: 14209920,
			lastUpdated: '2026-05-22',
		);

		$this->assertSame($expectedFirstItem->toArray(), $items[0]->toArray());

		$expectedAllNullItem = new EarningsCalendarItem(
			symbol: 'VTGDF',
			date: '2026-05-22',
			epsActual: null,
			epsEstimated: null,
			revenueActual: null,
			revenueEstimated: null,
			lastUpdated: '2026-05-22',
		);

		$this->assertSame($expectedAllNullItem->toArray(), $items[1]->toArray());

		$expectedEpsActualNullItem = new EarningsCalendarItem(
			symbol: 'ITC.NS',
			date: '2026-05-22',
			epsActual: null,
			epsEstimated: 4.15,
			revenueActual: 176288900000,
			revenueEstimated: 173793700000,
			lastUpdated: '2026-05-22',
		);

		$this->assertSame($expectedEpsActualNullItem->toArray(), $items[72]->toArray());
	}

}
