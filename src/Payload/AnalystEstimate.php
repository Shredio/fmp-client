<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class AnalystEstimate
{

	public function __construct(
		public string $symbol,
		public string $date,
		public int $revenueLow,
		public int $revenueHigh,
		public int $revenueAvg,
		public int $ebitdaLow,
		public int $ebitdaHigh,
		public int $ebitdaAvg,
		public int $ebitLow,
		public int $ebitHigh,
		public int $ebitAvg,
		public int $netIncomeLow,
		public int $netIncomeHigh,
		public int $netIncomeAvg,
		public int $sgaExpenseLow,
		public int $sgaExpenseHigh,
		public int $sgaExpenseAvg,
		public float $epsAvg,
		public float $epsHigh,
		public float $epsLow,
		public int $numAnalystsRevenue,
		public int $numAnalystsEps,
	)
	{
	}

	/**
	 * @return array{symbol: string, date: string, revenueLow: int, revenueHigh: int, revenueAvg: int, ebitdaLow: int, ebitdaHigh: int, ebitdaAvg: int, ebitLow: int, ebitHigh: int, ebitAvg: int, netIncomeLow: int, netIncomeHigh: int, netIncomeAvg: int, sgaExpenseLow: int, sgaExpenseHigh: int, sgaExpenseAvg: int, epsAvg: float, epsHigh: float, epsLow: float, numAnalystsRevenue: int, numAnalystsEps: int}
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

}