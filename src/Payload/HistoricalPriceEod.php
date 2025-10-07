<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class HistoricalPriceEod
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float $open,
		public float $high,
		public float $low,
		public float $close,
		public int $volume,
		public float $change,
		public float $changePercent,
		public float $vwap,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, open: float, high: float, low: float, close: float, volume: int, change: float, changePercent: float, vwap: float}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'open' => $this->open,
			'high' => $this->high,
			'low' => $this->low,
			'close' => $this->close,
			'volume' => $this->volume,
			'change' => $this->change,
			'changePercent' => $this->changePercent,
			'vwap' => $this->vwap,
		];
	}

}
