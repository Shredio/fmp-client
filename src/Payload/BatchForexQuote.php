<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class BatchForexQuote
{

	public function __construct(
		public string $symbol,
		public float $price,
		public float $change,
		public int|float|null $volume = null, // float for cryptocurrencies
	)
	{
	}

	/**
	 * @return array{symbol: string, price: float, change: float, volume: int|float|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'price' => $this->price,
			'change' => $this->change,
			'volume' => $this->volume,
		];
	}

}