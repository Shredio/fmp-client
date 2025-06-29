<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class EodQuote
{

	public function __construct(
		public string $symbol,
		public string $date,
		public float $open,
		public float $low,
		public float $high,
		public float $close,
		public float $adjClose,
		public float $volume,
	)
	{
	}

	/**
	 * @return array{symbol: string, date: string, open: float, low: float, high: float, close: float, adjClose: float, volume: float}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'open' => $this->open,
			'low' => $this->low,
			'high' => $this->high,
			'close' => $this->close,
			'adjClose' => $this->adjClose,
			'volume' => $this->volume,
		];
	}

}
