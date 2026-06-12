<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class HistoricalPriceEodNonSplitAdjusted
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float $adjOpen,
		public float $adjHigh,
		public float $adjLow,
		public float $adjClose,
		public float $volume,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, adjOpen: float, adjHigh: float, adjLow: float, adjClose: float, volume: float}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'adjOpen' => $this->adjOpen,
			'adjHigh' => $this->adjHigh,
			'adjLow' => $this->adjLow,
			'adjClose' => $this->adjClose,
			'volume' => $this->volume,
		];
	}

}
