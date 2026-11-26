<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class StockSplit
{

	/**
	 * @param non-empty-string $symbol
	 * @param int|float $numerator Usually an integer, a float for ratios FMP sends with decimals (RYAAY 2.49:1)
	 * @param int|float $denominator Usually an integer, a float for ratios FMP sends with decimals (USCMF 1:2.5)
	 * @param non-empty-string|null $splitType
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public int|float $numerator,
		public int|float $denominator,
		public ?string $splitType,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, numerator: int|float, denominator: int|float, splitType: non-empty-string|null}
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
