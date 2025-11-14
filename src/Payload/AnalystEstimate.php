<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\NullAsZeroConversion;
use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol', contextFactory: 'createContext')]
final readonly class AnalystEstimate
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public int|float $revenueLow = 0,
		public int|float $revenueHigh = 0,
		public int|float $revenueAvg = 0,
		public int|float $ebitdaLow = 0,
		public int|float $ebitdaHigh = 0,
		public int|float $ebitdaAvg = 0,
		public int|float $ebitLow = 0,
		public int|float $ebitHigh = 0,
		public int|float $ebitAvg = 0,
		public int|float $netIncomeLow = 0,
		public int|float $netIncomeHigh = 0,
		public int|float $netIncomeAvg = 0,
		public int|float $sgaExpenseLow = 0,
		public int|float $sgaExpenseHigh = 0,
		public int|float $sgaExpenseAvg = 0,
		public float $epsAvg = 0.0,
		public float $epsHigh = 0.0,
		public float $epsLow = 0.0,
		public int $numAnalystsRevenue = 0,
		public int $numAnalystsEps = 0,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string, revenueLow: int|float, revenueHigh: int|float, revenueAvg: int|float, ebitdaLow: int|float, ebitdaHigh: int|float, ebitdaAvg: int|float, ebitLow: int|float, ebitHigh: int|float, ebitAvg: int|float, netIncomeLow: int|float, netIncomeHigh: int|float, netIncomeAvg: int|float, sgaExpenseLow: int|float, sgaExpenseHigh: int|float, sgaExpenseAvg: int|float, epsAvg: float, epsHigh: float, epsLow: float, numAnalystsRevenue: int, numAnalystsEps: int}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'revenueLow' => $this->revenueLow,
			'revenueHigh' => $this->revenueHigh,
			'revenueAvg' => $this->revenueAvg,
			'ebitdaLow' => $this->ebitdaLow,
			'ebitdaHigh' => $this->ebitdaHigh,
			'ebitdaAvg' => $this->ebitdaAvg,
			'ebitLow' => $this->ebitLow,
			'ebitHigh' => $this->ebitHigh,
			'ebitAvg' => $this->ebitAvg,
			'netIncomeLow' => $this->netIncomeLow,
			'netIncomeHigh' => $this->netIncomeHigh,
			'netIncomeAvg' => $this->netIncomeAvg,
			'sgaExpenseLow' => $this->sgaExpenseLow,
			'sgaExpenseHigh' => $this->sgaExpenseHigh,
			'sgaExpenseAvg' => $this->sgaExpenseAvg,
			'epsAvg' => $this->epsAvg,
			'epsHigh' => $this->epsHigh,
			'epsLow' => $this->epsLow,
			'numAnalystsRevenue' => $this->numAnalystsRevenue,
			'numAnalystsEps' => $this->numAnalystsEps,
		];
	}

	/**
	 * @internal Internal method for object mapper
	 */
	public static function createContext(TypeContext $context): TypeContext
	{
		return $context->withConversionStrategy(new NullAsZeroConversion($context->conversionStrategy));
	}

}
