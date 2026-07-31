<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class RevenueProductSegmentation
{

	/**
	 * @param non-empty-string $symbol
	 * @param array<string, float> $data revenue per product line
	 */
	public function __construct(
		public string $symbol,
		public int $fiscalYear,
		public Period $period,
		public string $reportedCurrency,
		public string $date,
		public array $data = [],
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, fiscalYear: int, period: value-of<Period>, reportedCurrency: string, date: string, data: array<string, float>}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'fiscalYear' => $this->fiscalYear,
			'period' => $this->period->value,
			'reportedCurrency' => $this->reportedCurrency,
			'date' => $this->date,
			'data' => $this->data,
		];
	}

}
