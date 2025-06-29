<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class LatestFinancialStatement
{

	public function __construct(
		public string $symbol,
		public int $calendarYear,
		public string $period,
		public string $date,
		public string $dateAdded,
	)
	{
	}

	/**
	 * @return array{symbol: string, calendarYear: int, period: string, date: string, dateAdded: string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'calendarYear' => $this->calendarYear,
			'period' => $this->period,
			'date' => $this->date,
			'dateAdded' => $this->dateAdded,
		];
	}

}