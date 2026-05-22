<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\SplitsCalendarItem;
use Tests\TestCase;

final class SplitsCalendarTest extends TestCase
{

	public function testSplitsCalendar(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/splits-calendar-2021-01-07-to-2021-01-10.json');

		$splits = [];
		foreach ($client->splitsCalendar(new DateTimeImmutable('2021-01-07'), new DateTimeImmutable('2021-01-10')) as $split) {
			$splits[] = $split;
		}

		$this->assertNotEmpty($splits);
		$this->assertCount(22, $splits);

		$expectedFirstSplit = new SplitsCalendarItem(
			symbol: 'IPCALAB.BO',
			date: '2021-01-10',
			numerator: 2,
			denominator: 1,
			splitType: 'stock-split',
		);

		$this->assertSame($expectedFirstSplit->toArray(), $splits[0]->toArray());

		$expectedStockDividendSplit = new SplitsCalendarItem(
			symbol: '6251.TW',
			date: '2021-01-07',
			numerator: 223,
			denominator: 250,
			splitType: 'stock-dividend',
		);

		$this->assertSame($expectedStockDividendSplit->toArray(), $splits[11]->toArray());

		$expectedNullSplitTypeSplit = new SplitsCalendarItem(
			symbol: '7516.TWO',
			date: '2021-01-07',
			numerator: 2404,
			denominator: 3125,
			splitType: null,
		);

		$this->assertSame($expectedNullSplitTypeSplit->toArray(), $splits[16]->toArray());

		$expectedLastSplit = new SplitsCalendarItem(
			symbol: '0208.KL',
			date: '2021-01-07',
			numerator: 2,
			denominator: 1,
			splitType: 'stock-split',
		);

		$this->assertSame($expectedLastSplit->toArray(), $splits[21]->toArray());
	}

}
