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
	 * @param string|null $content Null when FMP lists the call without its text
	 */
	public function __construct(
		public string $symbol,
		public Period $period,
		public int $year,
		public string $date,
		public string|null $content,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, period: value-of<Period>, year: int, date: non-empty-string, content: string|null}
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
