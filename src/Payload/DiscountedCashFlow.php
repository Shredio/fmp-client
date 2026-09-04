<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class DiscountedCashFlow
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public float|null $dcf = null,
		#[CompilePropertyOptions(name: 'Stock Price')]
		public float|null $stockPrice = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: string, dcf: float|null, stockPrice: float|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'dcf' => $this->dcf,
			'stockPrice' => $this->stockPrice,
		];
	}

}
