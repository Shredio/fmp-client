<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class StockSplit
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $splitType
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public int $numerator,
		public int $denominator,
		public ?string $splitType,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, numerator: int, denominator: int, splitType: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'numerator' => $this->numerator,
			'denominator' => $this->denominator,
			'splitType' => $this->splitType,
		];
	}

}
