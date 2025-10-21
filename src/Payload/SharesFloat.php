<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class SharesFloat
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 * @param non-empty-string $source
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float $freeFloat,
		public int $floatShares,
		public int $outstandingShares,
		public string $source,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string, freeFloat: float, floatShares: int, outstandingShares: int, source: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'freeFloat' => $this->freeFloat,
			'floatShares' => $this->floatShares,
			'outstandingShares' => $this->outstandingShares,
			'source' => $this->source,
		];
	}

}