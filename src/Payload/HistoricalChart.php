<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class HistoricalChart
{

	public function __construct(
		public string $date,
		public float $open,
		public float $high,
		public float $low,
		public float $close,
		public int $volume,
	)
	{
	}

	/**
	 * @return array{date: string, open: float, high: float, low: float, close: float, volume: int}
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'open' => $this->open,
			'high' => $this->high,
			'low' => $this->low,
			'close' => $this->close,
			'volume' => $this->volume,
		];
	}

}