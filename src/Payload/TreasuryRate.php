<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type TreasuryRateArray array{date: non-empty-string, month1: float, month2: float, month3: float, month6: float, year1: float, year2: float, year3: float, year5: float, year7: float, year10: float, year20: float, year30: float}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class TreasuryRate
{
	/**
	 * @param non-empty-string $date (2025-11-05, 2025-11-04, etc.)
	 */
	public function __construct(
		public string $date,
		public float $month1,
		public float $month2,
		public float $month3,
		public float $month6,
		public float $year1,
		public float $year2,
		public float $year3,
		public float $year5,
		public float $year7,
		public float $year10,
		public float $year20,
		public float $year30,
	) {}

	/**
	 * @return array{date: non-empty-string, month1: float, month2: float, month3: float, month6: float, year1: float, year2: float, year3: float, year5: float, year7: float, year10: float, year20: float, year30: float}
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'month1' => $this->month1,
			'month2' => $this->month2,
			'month3' => $this->month3,
			'month6' => $this->month6,
			'year1' => $this->year1,
			'year2' => $this->year2,
			'year3' => $this->year3,
			'year5' => $this->year5,
			'year7' => $this->year7,
			'year10' => $this->year10,
			'year20' => $this->year20,
			'year30' => $this->year30,
		];
	}
}
