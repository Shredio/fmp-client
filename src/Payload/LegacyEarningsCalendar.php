<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class LegacyEarningsCalendar
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 * @param non-empty-string|null $time
	 * @param non-empty-string|null $fiscalDateEnding
	 * @param non-empty-string|null $updatedFromDate
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public ?float $eps,
		public ?float $epsEstimated,
		public ?string $time,
		public ?int $revenue,
		public ?int $revenueEstimated,
		public ?string $fiscalDateEnding,
		public ?string $updatedFromDate,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string, eps: float|null, epsEstimated: float|null, time: non-empty-string|null, revenue: int|null, revenueEstimated: int|null, fiscalDateEnding: non-empty-string|null, updatedFromDate: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'eps' => $this->eps,
			'epsEstimated' => $this->epsEstimated,
			'time' => $this->time,
			'revenue' => $this->revenue,
			'revenueEstimated' => $this->revenueEstimated,
			'fiscalDateEnding' => $this->fiscalDateEnding,
			'updatedFromDate' => $this->updatedFromDate,
		];
	}

}
