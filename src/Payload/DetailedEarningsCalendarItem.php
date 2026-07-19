<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class DetailedEarningsCalendarItem
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $time
	 * @param non-empty-string|null $periodEnding
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float|null $epsActual,
		public float|null $epsEstimated,
		public int|float|null $revenueActual,
		public int|float|null $revenueEstimated,
		public ?string $time,
		public ?string $periodEnding,
		public Period $fiscalPeriod,
		public int $fiscalYear,
		public bool $confirmed,
		public string $lastUpdated,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, epsActual: float|null, epsEstimated: float|null, revenueActual: int|float|null, revenueEstimated: int|float|null, time: non-empty-string|null, periodEnding: non-empty-string|null, fiscalPeriod: string, fiscalYear: int, confirmed: bool, lastUpdated: string}
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
			'time' => $this->time,
			'periodEnding' => $this->periodEnding,
			'fiscalPeriod' => $this->fiscalPeriod->value,
			'fiscalYear' => $this->fiscalYear,
			'confirmed' => $this->confirmed,
			'lastUpdated' => $this->lastUpdated,
		];
	}

}
