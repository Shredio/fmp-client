<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\EarningsCalendarItem;
use Tests\TestCase;

final class EarningsTest extends TestCase
{

	public function testEarnings(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/earnings-aapl.json');

		$earnings = iterator_to_array($client->earnings('AAPL'));

		$this->assertCount(165, $earnings);
		$this->assertSame((new EarningsCalendarItem(
			symbol: 'AAPL',
			date: '2026-10-29',
			epsActual: null,
			epsEstimated: 1.98,
			revenueActual: null,
			revenueEstimated: 113218400000,
			lastUpdated: '2026-09-04',
		))->toArray(), $earnings[0]->toArray());
	}

	public function testReportedEarningsFollowTheUpcomingOne(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/earnings-aapl.json');

		$earnings = iterator_to_array($client->earnings('AAPL'));

		$this->assertSame((new EarningsCalendarItem(
			symbol: 'AAPL',
			date: '2026-07-30',
			epsActual: 2.02,
			epsEstimated: 1.89,
			revenueActual: 109417000000,
			revenueEstimated: 109038900000,
			lastUpdated: '2026-09-04',
		))->toArray(), $earnings[1]->toArray());
	}

	public function testEstimatesAreMissingForOldestReports(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/earnings-aapl.json');

		$earnings = iterator_to_array($client->earnings('AAPL'));
		$oldest = $earnings[array_key_last($earnings)];

		$this->assertSame('1985-09-30', $oldest->date);
		$this->assertNull($oldest->epsEstimated);
		$this->assertNull($oldest->revenueEstimated);
	}

}
