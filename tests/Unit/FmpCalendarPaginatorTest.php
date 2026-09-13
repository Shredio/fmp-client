<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use InvalidArgumentException;
use Shredio\FmpClient\Calendar\FmpCalendarPaginator;
use Tests\TestCase;

final class FmpCalendarPaginatorTest extends TestCase
{

	public function testEmptyPagesStepBackByTheIntervalTheApiFillsUntilTheWindowIsPassed(): void
	{
		// A year-ahead window whose tail holds nothing: every empty page moves `to` 90 days back.
		$paginator = new FmpCalendarPaginator(new DateTimeImmutable('2026-09-10'), new DateTimeImmutable('2027-09-10'));

		$visited = [];
		while ($paginator->next(0, null)) {
			$visited[] = $paginator->getTo()->format('Y-m-d');
		}

		$this->assertSame(['2027-06-12', '2027-03-14', '2026-12-14', '2026-09-15'], $visited);
		$this->assertSame('2026-06-17', $paginator->getTo()->format('Y-m-d'));
	}

	public function testAnEmptyPageOnAWindowShorterThanTheIntervalEndsTheWalk(): void
	{
		$paginator = new FmpCalendarPaginator(new DateTimeImmutable('2021-01-07'), new DateTimeImmutable('2021-01-10'));

		$this->assertFalse($paginator->next(0, null));
	}

	public function testAPartialPageContinuesFromTheDayBeforeItsOldestRecord(): void
	{
		$paginator = new FmpCalendarPaginator(new DateTimeImmutable('2021-01-01'), new DateTimeImmutable('2021-03-01'));

		$this->assertTrue($paginator->next(22, '2021-01-10'));
		$this->assertSame('2021-01-09', $paginator->getTo()->format('Y-m-d'));
	}

	public function testAFullPageContinuesFromItsOldestDayAndBreaksOutOfARepeatedDay(): void
	{
		$paginator = new FmpCalendarPaginator(new DateTimeImmutable('2021-01-01'), new DateTimeImmutable('2021-03-01'), maxRecordsPerPage: 3);

		// A full page may have more records on its oldest day, so that day is requested again...
		$this->assertTrue($paginator->next(3, '2021-02-01'));
		$this->assertSame('2021-02-01', $paginator->getTo()->format('Y-m-d'));

		// ...but a second full page ending on the same day would loop forever, so the walk moves past it.
		$this->assertTrue($paginator->next(3, '2021-02-01'));
		$this->assertSame('2021-01-31', $paginator->getTo()->format('Y-m-d'));
	}

	public function testStopsOncePastFrom(): void
	{
		$paginator = new FmpCalendarPaginator(new DateTimeImmutable('2021-01-05'), new DateTimeImmutable('2021-01-10'));

		$this->assertFalse($paginator->next(3, '2021-01-05'));
		$this->assertSame('2021-01-04', $paginator->getTo()->format('Y-m-d'));
	}

	public function testRejectsAWindowEndingBeforeItStarts(): void
	{
		$this->expectException(InvalidArgumentException::class);

		new FmpCalendarPaginator(new DateTimeImmutable('2021-01-10'), new DateTimeImmutable('2021-01-09'));
	}

}
