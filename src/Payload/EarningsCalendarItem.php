<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class EarningsCalendarItem
{

	public function __construct(
		public string $symbol,
		public string $date,
		public float $epsActual,
		public float|null $epsEstimated,
		public int $revenueActual,
		public int|null $revenueEstimated,
		public string $lastUpdated,
	)
	{
	}

	/**
	 * @return array{symbol: string, date: string, epsActual: float, epsEstimated: float|null, revenueActual: int, revenueEstimated: int|null, lastUpdated: string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'epsActual' => $this->epsActual,
			'epsEstimated' => $this->epsEstimated,
			'revenueActual' => $this->revenueActual,
			'revenueEstimated' => $this->revenueEstimated,
			'lastUpdated' => $this->lastUpdated,
		];
	}

}