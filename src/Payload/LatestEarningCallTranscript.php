<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class LatestEarningCallTranscript
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date Date of the earning call, occasionally in the future
	 */
	public function __construct(
		public string $symbol,
		public Period $period,
		public int $fiscalYear,
		public string $date,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, period: value-of<Period>, fiscalYear: int, date: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'period' => $this->period->value,
			'fiscalYear' => $this->fiscalYear,
			'date' => $this->date,
		];
	}

}
