<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class BatchExchangeDetailedQuote
{

	public function __construct(
		public string $symbol,
		public string $name,
		public string $exchange,
		public float|null $price = null,
		public float|null $changePercentage = null,
		public float|null $change = null,
		public int|float|null $volume = null, // float for cryptocurrencies
		public float|null $dayLow = null,
		public float|null $dayHigh = null,
		public float|null $yearHigh = null,
		public float|null $yearLow = null,
		public int|null $marketCap = null,
		public float|null $priceAvg50 = null,
		public float|null $priceAvg200 = null,
		public float|null $open = null,
		public float|null $previousClose = null,
		public int|null $timestamp = null,
	)
	{
	}

	/**
	 * @return array{symbol: string, name: string, exchange: string, price: float|null, changePercentage: float|null, change: float|null, volume: int|float|null, dayLow: float|null, dayHigh: float|null, yearHigh: float|null, yearLow: float|null, marketCap: int|null, priceAvg50: float|null, priceAvg200: float|null, open: float|null, previousClose: float|null, timestamp: int|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'name' => $this->name,
			'exchange' => $this->exchange,
			'price' => $this->price,
			'changePercentage' => $this->changePercentage,
			'change' => $this->change,
			'volume' => $this->volume,
			'dayLow' => $this->dayLow,
			'dayHigh' => $this->dayHigh,
			'yearHigh' => $this->yearHigh,
			'yearLow' => $this->yearLow,
			'marketCap' => $this->marketCap,
			'priceAvg50' => $this->priceAvg50,
			'priceAvg200' => $this->priceAvg200,
			'open' => $this->open,
			'previousClose' => $this->previousClose,
			'timestamp' => $this->timestamp,
		];
	}

}