<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\MoneyAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class MarketCapitalization
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 * @param int $marketCap In the currency the symbol is traded in, 0 for indexes
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $marketCap,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string, marketCap: int}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'marketCap' => $this->marketCap,
		];
	}

}
