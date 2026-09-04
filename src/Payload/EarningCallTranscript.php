<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EarningCallTranscript
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 */
	public function __construct(
		public string $symbol,
		public Period $period,
		public int $year,
		public string $date,
		public string $content,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, period: value-of<Period>, year: int, date: non-empty-string, content: string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'period' => $this->period->value,
			'year' => $this->year,
			'date' => $this->date,
			'content' => $this->content,
		];
	}

}
