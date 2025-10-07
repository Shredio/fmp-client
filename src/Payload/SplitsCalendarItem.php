<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class SplitsCalendarItem
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public int $numerator,
		public int $denominator,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, numerator: int, denominator: int}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'numerator' => $this->numerator,
			'denominator' => $this->denominator,
		];
	}

}
