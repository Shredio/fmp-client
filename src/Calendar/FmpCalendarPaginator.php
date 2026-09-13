<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Calendar;

use DateTimeImmutable;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Walks a calendar endpoint backwards from `to` down to `from`, one response at a time.
 *
 * The API answers with its newest records first, at most $maxRecordsPerPage of them, so after every page the
 * next request ends where the last one stopped. The API also silently narrows any request to roughly the last
 * three months before `to` ($maxIntervalDays, observed as 90 days): an empty page therefore proves only that
 * those days hold nothing, not the rest of the window, so the walk steps `to` back by that span and carries on
 * until it passes `from`. Stopping on the first empty page instead made a window whose tail is empty - stock
 * splits a year ahead, where none is announced yet - return nothing at all.
 */
final class FmpCalendarPaginator
{

	private readonly DateTimeImmutable $from;

	private DateTimeImmutable $to;

	private DateTimeImmutable $lastTo;

	/**
	 * @param int<1, max> $maxRecordsPerPage Maximum number of records returned by the API in a single response
	 * @param int<1, max> $maxIntervalDays Widest span before `to` the API fills; a request reaching further back is silently cut there
	 */
	public function __construct(
		DateTimeImmutable $from,
		DateTimeImmutable $to,
		private readonly int $maxRecordsPerPage = 4000,
		private readonly int $maxIntervalDays = 90,
	)
	{
		$this->from = $from->setTime(0, 0);
		$this->lastTo = $this->to = $to->setTime(0, 0);

		if ($this->to < $this->from) {
			throw new InvalidArgumentException('To date must be greater than from date');
		}
	}

	public function getFrom(): DateTimeImmutable
	{
		return $this->from;
	}

	public function getTo(): DateTimeImmutable
	{
		return $this->to;
	}

	/**
	 * @param int $itemCount number of records the last page held
	 * @param string|null $lastStringDate date of the oldest record on the last page in format Y-m-d; null for an empty page
	 * @return bool whether another page is needed to cover the window
	 */
	public function next(int $itemCount, ?string $lastStringDate, ?LoggerInterface $logger = null): bool
	{
		if ($lastStringDate === null) {
			// An empty page covers only the span the API fills before `to`; the rest of the window is still unknown.
			$logger?->debug('Empty calendar page ending {date}, stepping back {days} days', [
				'date' => $this->to->format('Y-m-d'),
				'days' => $this->maxIntervalDays,
			]);

			$this->to = $this->to->modify(sprintf('- %d days', $this->maxIntervalDays));
		} else {
			$this->to = new DateTimeImmutable($lastStringDate);

			// If we have less than the API page limit, we can go back one day, because there are no more records for that day
			if ($itemCount < $this->maxRecordsPerPage) {
				$this->to = $this->to->modify('- 1 day');
			} else if ($this->to == $this->lastTo) { // the same `to` date can cause an infinite loop
				$logger?->info('Infinite loop detected for {date}', [
					'date' => $this->to->format('Y-m-d'),
				]);

				$this->to = $this->to->modify('- 1 day');
			}
		}

		$this->lastTo = $this->to;

		return $this->to >= $this->from;
	}

}
