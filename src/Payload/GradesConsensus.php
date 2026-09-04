<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class GradesConsensus
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public int $strongBuy = 0,
		public int $buy = 0,
		public int $hold = 0,
		public int $sell = 0,
		public int $strongSell = 0,
		public string $consensus = '',
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, strongBuy: int, buy: int, hold: int, sell: int, strongSell: int, consensus: string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'strongBuy' => $this->strongBuy,
			'buy' => $this->buy,
			'hold' => $this->hold,
			'sell' => $this->sell,
			'strongSell' => $this->strongSell,
			'consensus' => $this->consensus,
		];
	}

}
