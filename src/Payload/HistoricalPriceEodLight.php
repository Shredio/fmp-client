<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class HistoricalPriceEodLight
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float $price,
		public int $volume,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, price: float, volume: int}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'price' => $this->price,
			'volume' => $this->volume,
		];
	}

}
