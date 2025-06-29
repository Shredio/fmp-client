<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\DividendsCalendarItem;
use Tests\TestCase;

final class DividendsCalendarTest extends TestCase
{

	public function testDividendsCalendar(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/dividends-calendar-2021-01-01-to-2021-01-02.json');

		$dividends = [];
		foreach ($client->dividendsCalendar(new DateTimeImmutable('2021-01-01'), new DateTimeImmutable('2021-01-02')) as $dividend) {
			$dividends[] = $dividend;
		}

		$this->assertNotEmpty($dividends);
		$this->assertCount(14, $dividends);

		$expectedFirstDividend = new DividendsCalendarItem(
			symbol: 'UBN',
			date: '2021-01-01',
			recordDate: null,
			paymentDate: null,
			declarationDate: null,
			adjDividend: 0.13,
			dividend: 0.13,
			yield: 2.429906542056075,
			frequency: 'Monthly'
		);

		$this->assertSame($expectedFirstDividend->toArray(), $dividends[0]->toArray());

		$expectedSecondDividend = new DividendsCalendarItem(
			symbol: 'HEOL',
			date: '2021-01-01',
			recordDate: null,
			paymentDate: null,
			declarationDate: null,
			adjDividend: 0.13,
			dividend: 0.13,
			yield: 0.0021996615905245345,
			frequency: 'Monthly'
		);

		$this->assertSame($expectedSecondDividend->toArray(), $dividends[1]->toArray());

		$expectedDividendWithDates = new DividendsCalendarItem(
			symbol: '5072.KL',
			date: '2021-01-01',
			recordDate: '2021-01-04',
			paymentDate: '2021-01-25',
			declarationDate: null,
			adjDividend: 0.003,
			dividend: 0.003,
			yield: 0.6521739130434783,
			frequency: 'Annual'
		);

		$this->assertSame($expectedDividendWithDates->toArray(), $dividends[8]->toArray());

		$expectedQuarterlyDividend = new DividendsCalendarItem(
			symbol: 'CONE',
			date: '2021-01-01',
			recordDate: '2021-01-04',
			paymentDate: '2021-01-08',
			declarationDate: '2020-10-28',
			adjDividend: 0.51,
			dividend: 0.51,
			yield: 2.761449077238551,
			frequency: 'Quarterly'
		);

		$this->assertSame($expectedQuarterlyDividend->toArray(), $dividends[10]->toArray());
	}

}
