<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EarningsCalendarItem
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float|null $epsActual,
		public float|null $epsEstimated,
		public int|float|null $revenueActual,
		public int|float|null $revenueEstimated,
		public string $lastUpdated,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, epsActual: float|null, epsEstimated: float|null, revenueActual: int|float|null, revenueEstimated: int|float|null, lastUpdated: string}
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
