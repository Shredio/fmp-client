<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class SharesFloat
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $date
	 * @param non-empty-string|null $source
	 */
	public function __construct(
		public string $symbol,
		public string|null $date,
		public ?float $freeFloat,
		public ?int $floatShares,
		public int $outstandingShares,
		public string|null $source = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string|null, freeFloat: float, floatShares: int, outstandingShares: int, source: non-empty-string|null}
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
