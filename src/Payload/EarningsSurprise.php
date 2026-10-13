<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EarningsSurprise
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date Date of the earnings report
	 * @param non-empty-string $lastUpdated
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float|null $epsActual,
		public float|null $epsEstimated,
		public string $lastUpdated,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string, epsActual: float|null, epsEstimated: float|null, lastUpdated: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'epsActual' => $this->epsActual,
			'epsEstimated' => $this->epsEstimated,
			'lastUpdated' => $this->lastUpdated,
		];
	}

}
