<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\LegacyEarningsCalendar;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class LegacyEarningsCalendarTest extends TestCase
{

	public function testLegacyEarningsCalendar(): void
	{
		$client = $this->createClient(
			__DIR__ . '/fixtures/legacy-earnings-calendar-2026-01-01-to-2026-01-10.json',
			responses: [new MockResponse('[]')],
		);

		$items = [];
		foreach ($client->legacyEarningsCalendar(new DateTimeImmutable('2026-01-01'), new DateTimeImmutable('2026-01-10')) as $item) {
			$items[] = $item;
		}

		$this->assertCount(332, $items);

		$expectedAmcItem = new LegacyEarningsCalendar(
			symbol: 'ISOU',
			date: '2026-01-01',
			eps: 0.00718,
			epsEstimated: -0.06,
			time: 'amc',
			revenue: null,
			revenueEstimated: null,
			fiscalDateEnding: '2025-09-30',
			updatedFromDate: '2026-03-31',
		);

		$this->assertSame($expectedAmcItem->toArray(), $items[0]->toArray());

		$expectedMostlyNullsItem = new LegacyEarningsCalendar(
			symbol: 'NYMXF',
			date: '2026-01-01',
			eps: null,
			epsEstimated: null,
			time: null,
			revenue: null,
			revenueEstimated: null,
			fiscalDateEnding: '2025-12-31',
			updatedFromDate: '2026-04-01',
		);

		$this->assertSame($expectedMostlyNullsItem->toArray(), $items[1]->toArray());

		$expectedFullItem = new LegacyEarningsCalendar(
			symbol: 'PENG',
			date: '2026-01-06',
			eps: 0.49,
			epsEstimated: 0.41,
			time: 'amc',
			revenue: 343071000,
			revenueEstimated: 338428200,
			fiscalDateEnding: '2025-11-28',
			updatedFromDate: '2026-04-05',
		);

		$fullItem = $this->findItemBySymbol($items, 'PENG');
		$this->assertNotNull($fullItem);
		$this->assertSame($expectedFullItem->toArray(), $fullItem->toArray());
	}

	/**
	 * @param list<LegacyEarningsCalendar> $items
	 */
	private function findItemBySymbol(array $items, string $symbol): ?LegacyEarningsCalendar
	{
		foreach ($items as $item) {
			if ($item->symbol === $symbol) {
				return $item;
			}
		}

		return null;
	}

}
