<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
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

		$this->assertCount(120, $items);

		$expectedNullTimeItem = new DetailedEarningsCalendarItem(
			symbol: '1766.T',
			date: '2026-06-12',
			epsActual: null,
			epsEstimated: null,
			revenueActual: null,
			revenueEstimated: 103250000000,
			time: null,
			periodEnding: '2025-04-30',
			confirmed: false,
			lastUpdated: '2026-06-11',
		);

		$this->assertSame($expectedNullTimeItem->toArray(), $items[0]->toArray());

		$expectedAmcItem = new DetailedEarningsCalendarItem(
			symbol: 'CBLU.V',
			date: '2026-06-12',
			epsActual: null,
			epsEstimated: null,
			revenueActual: null,
			revenueEstimated: null,
			time: 'amc',
			periodEnding: '2026-03-31',
			confirmed: true,
			lastUpdated: '2026-06-11',
		);

		$this->assertSame($expectedAmcItem->toArray(), $items[19]->toArray());

		$expectedBmoItem = new DetailedEarningsCalendarItem(
			symbol: 'JFIN',
			date: '2026-06-12',
			epsActual: null,
			epsEstimated: 0.1707,
			revenueActual: null,
			revenueEstimated: 53395030,
			time: 'bmo',
			periodEnding: '2026-03-31',
			confirmed: true,
			lastUpdated: '2026-06-11',
		);

		$this->assertSame($expectedBmoItem->toArray(), $items[20]->toArray());
	}

}
