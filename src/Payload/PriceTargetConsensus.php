<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class PriceTargetConsensus
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public float|null $targetHigh = null,
		public float|null $targetLow = null,
		public float|null $targetConsensus = null,
		public float|null $targetMedian = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, targetHigh: float|null, targetLow: float|null, targetConsensus: float|null, targetMedian: float|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'targetHigh' => $this->targetHigh,
			'targetLow' => $this->targetLow,
			'targetConsensus' => $this->targetConsensus,
			'targetMedian' => $this->targetMedian,
		];
	}

}
