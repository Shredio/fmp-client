<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Calendar;

use DateTimeImmutable;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

final class FmpCalendarPaginator
{

	private readonly DateTimeImmutable $from;

	private DateTimeImmutable $to;

	private DateTimeImmutable $lastTo;

	public function __construct(
		DateTimeImmutable $from,
		DateTimeImmutable $to,
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
	 * @param string|null $lastStringDate date in format Y-m-d
	 */
	public function next(int $itemCount, ?string $lastStringDate, ?LoggerInterface $logger = null): bool
	{
		if ($lastStringDate === null) { // null means no results
			return false;
		}

		$this->to = new DateTimeImmutable($lastStringDate);

		// FMP limits the number of records to 4000
		// if we have less than 4000 records, we can go back one day, because there are no more records for that day
		if ($itemCount < 4000) {
			$this->to = $this->to->modify('- 1 day');
		} else if ($this->to == $this->lastTo) { // the same `to` date can cause an infinite loop
			$logger?->info('Infinite loop detected for {date}', [
				'date' => $this->to->format('Y-m-d'),
			]);

			$this->to = $this->to->modify('- 1 day');
		}

		$this->lastTo = $this->to;

		return $this->to >= $this->from;
	}

}
