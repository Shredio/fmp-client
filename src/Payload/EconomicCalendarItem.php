<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper]
final readonly class EconomicCalendarItem
{

	/**
	 * @param non-empty-string $date
	 * @param non-empty-string $country
	 * @param non-empty-string $event
	 * @param non-empty-string $currency
	 * @param non-empty-string $impact
	 * @param non-empty-string|null $unit
	 */
	public function __construct(
		public string $date,
		public string $country,
		public string $event,
		public string $currency,
		public float|null $previous,
		public float|null $estimate,
		public float|null $actual,
		public float|null $change,
		public string $impact,
		public float|null $changePercentage,
		public ?string $unit,
	)
	{
	}

	/**
	 * @return array{date: non-empty-string, country: non-empty-string, event: non-empty-string, currency: non-empty-string, previous: float|null, estimate: float|null, actual: float|null, change: float|null, impact: non-empty-string, changePercentage: float|null, unit: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'country' => $this->country,
			'event' => $this->event,
			'currency' => $this->currency,
			'previous' => $this->previous,
			'estimate' => $this->estimate,
			'actual' => $this->actual,
			'change' => $this->change,
			'impact' => $this->impact,
			'changePercentage' => $this->changePercentage,
			'unit' => $this->unit,
		];
	}

}
