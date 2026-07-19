<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\DetailedEarningsCalendarItem;
use Tests\TestCase;

final class DetailedEarningsCalendarTest extends TestCase
{

	public function testDetailedEarningsCalendar(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/detailed-earnings-calendar-2026-06-12.json');

		$items = [];
		foreach ($client->detailedEarningsCalendar(new DateTimeImmutable('2026-06-12'), new DateTimeImmutable('2026-06-12')) as $item) {
			$items[] = $item;
		}

		$this->assertCount(115, $items);

		$expectedNullTimeItem = new DetailedEarningsCalendarItem(
			symbol: '7378.T',
			date: '2026-06-12',
			epsActual: 34.61,
			epsEstimated: null,
			revenueActual: 1889000000,
			revenueEstimated: null,
			time: null,
			periodEnding: '2026-04-30',
			fiscalPeriod: Period::Q2,
			fiscalYear: 2026,
			confirmed: true,
			lastUpdated: '2026-07-19',
		);

		$this->assertSame($expectedNullTimeItem->toArray(), $items[0]->toArray());

		$expectedBmoItem = new DetailedEarningsCalendarItem(
			symbol: 'JVA',
			date: '2026-06-12',
			epsActual: 0.05,
			epsEstimated: 0.08,
			revenueActual: 22126160,
			revenueEstimated: 24600000,
			time: 'bmo',
			periodEnding: '2026-04-30',
			fiscalPeriod: Period::Q2,
			fiscalYear: 2026,
			confirmed: true,
			lastUpdated: '2026-07-19',
		);

		$this->assertSame($expectedBmoItem->toArray(), $items[21]->toArray());

		$expectedAmcItem = new DetailedEarningsCalendarItem(
			symbol: '3038.T',
			date: '2026-06-12',
			epsActual: 48.79,
			epsEstimated: 32.3,
			revenueActual: 147715100000,
			revenueEstimated: 145717600000,
			time: 'amc',
			periodEnding: '2026-04-30',
			fiscalPeriod: Period::Q2,
			fiscalYear: 2026,
			confirmed: true,
			lastUpdated: '2026-07-19',
		);

		$this->assertSame($expectedAmcItem->toArray(), $items[35]->toArray());
	}

}
