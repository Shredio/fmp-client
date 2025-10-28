<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class LatestFinancialStatement
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public int $calendarYear,
		public Period $period,
		public string $date,
		public string $dateAdded,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, calendarYear: int, period: Period, date: string, dateAdded: string}
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
