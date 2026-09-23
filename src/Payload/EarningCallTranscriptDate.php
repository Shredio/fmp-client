<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper]
final readonly class EarningCallTranscriptDate
{

	/**
	 * @param int<1, 4> $quarter
	 * @param non-empty-string $date
	 * @param non-empty-string|null $symbol Returned only for some symbols
	 */
	public function __construct(
		public int $quarter,
		public int $fiscalYear,
		public string $date,
		public string|null $symbol = null,
	)
	{
	}

	/**
	 * @return array{quarter: int<1, 4>, fiscalYear: int, date: non-empty-string, symbol: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'quarter' => $this->quarter,
			'fiscalYear' => $this->fiscalYear,
			'date' => $this->date,
			'symbol' => $this->symbol,
		];
	}

}
