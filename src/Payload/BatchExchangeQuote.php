<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class BatchExchangeQuote
{

	public function __construct(
		public string $symbol,
		public float|null $price = null,
		public float|null $change = null,
		public int|float|null $volume = null, // float for cryptocurrencies
	)
	{
	}

	/**
	 * @return array{symbol: string, price: float|null, change: float|null, volume: int|float|null}
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