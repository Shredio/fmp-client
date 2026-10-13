<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * Growth rates are fractions, 0.064 stands for a growth of 6.4 %, measured against the previous period: the previous
 * fiscal year for an annual record, the previous quarter for a quarterly one. The properties prefixed with threeY,
 * fiveY and tenY hold the total, not annualized, growth per share over that many years.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class FinancialGrowth
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date Last day of the fiscal period
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public string $fiscalYear,
		public Period $period,
		public string $reportedCurrency,
		public float $revenueGrowth,
		public float $grossProfitGrowth,
		public float $ebitgrowth,
		public float $operatingIncomeGrowth,
		public float $netIncomeGrowth,
		public float $epsgrowth,
		public float $epsdilutedGrowth,
		public float $weightedAverageSharesGrowth,
		public float $weightedAverageSharesDilutedGrowth,
		public float $dividendsPerShareGrowth,
		public float $operatingCashFlowGrowth,
		public float $receivablesGrowth,
		public float $inventoryGrowth,
		public float $assetGrowth,
		public float $bookValueperShareGrowth,
		public float $debtGrowth,
		public float $rdexpenseGrowth,
		public float $sgaexpensesGrowth,
		public float $freeCashFlowGrowth,
		public float $tenYRevenueGrowthPerShare,
		public float $fiveYRevenueGrowthPerShare,
		public float $threeYRevenueGrowthPerShare,
		public float $tenYOperatingCFGrowthPerShare,
		public float $fiveYOperatingCFGrowthPerShare,
		public float $threeYOperatingCFGrowthPerShare,
		public float $tenYNetIncomeGrowthPerShare,
		public float $fiveYNetIncomeGrowthPerShare,
		public float $threeYNetIncomeGrowthPerShare,
		public float $tenYShareholdersEquityGrowthPerShare,
		public float $fiveYShareholdersEquityGrowthPerShare,
		public float $threeYShareholdersEquityGrowthPerShare,
		public float $tenYDividendperShareGrowthPerShare,
		public float $fiveYDividendperShareGrowthPerShare,
		public float $threeYDividendperShareGrowthPerShare,
		public float $ebitdaGrowth,
		public float $growthCapitalExpenditure,
		public float $tenYBottomLineNetIncomeGrowthPerShare,
		public float $fiveYBottomLineNetIncomeGrowthPerShare,
		public float $threeYBottomLineNetIncomeGrowthPerShare,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     date: non-empty-string,
	 *     fiscalYear: string,
	 *     period: value-of<Period>,
	 *     reportedCurrency: string,
	 *     revenueGrowth: float,
	 *     grossProfitGrowth: float,
	 *     ebitgrowth: float,
	 *     operatingIncomeGrowth: float,
	 *     netIncomeGrowth: float,
	 *     epsgrowth: float,
	 *     epsdilutedGrowth: float,
	 *     weightedAverageSharesGrowth: float,
	 *     weightedAverageSharesDilutedGrowth: float,
	 *     dividendsPerShareGrowth: float,
	 *     operatingCashFlowGrowth: float,
	 *     receivablesGrowth: float,
	 *     inventoryGrowth: float,
	 *     assetGrowth: float,
	 *     bookValueperShareGrowth: float,
	 *     debtGrowth: float,
	 *     rdexpenseGrowth: float,
	 *     sgaexpensesGrowth: float,
	 *     freeCashFlowGrowth: float,
	 *     tenYRevenueGrowthPerShare: float,
	 *     fiveYRevenueGrowthPerShare: float,
	 *     threeYRevenueGrowthPerShare: float,
	 *     tenYOperatingCFGrowthPerShare: float,
	 *     fiveYOperatingCFGrowthPerShare: float,
	 *     threeYOperatingCFGrowthPerShare: float,
	 *     tenYNetIncomeGrowthPerShare: float,
	 *     fiveYNetIncomeGrowthPerShare: float,
	 *     threeYNetIncomeGrowthPerShare: float,
	 *     tenYShareholdersEquityGrowthPerShare: float,
	 *     fiveYShareholdersEquityGrowthPerShare: float,
	 *     threeYShareholdersEquityGrowthPerShare: float,
	 *     tenYDividendperShareGrowthPerShare: float,
	 *     fiveYDividendperShareGrowthPerShare: float,
	 *     threeYDividendperShareGrowthPerShare: float,
	 *     ebitdaGrowth: float,
	 *     growthCapitalExpenditure: float,
	 *     tenYBottomLineNetIncomeGrowthPerShare: float,
	 *     fiveYBottomLineNetIncomeGrowthPerShare: float,
	 *     threeYBottomLineNetIncomeGrowthPerShare: float
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'fiscalYear' => $this->fiscalYear,
			'period' => $this->period->value,
			'reportedCurrency' => $this->reportedCurrency,
			'revenueGrowth' => $this->revenueGrowth,
			'grossProfitGrowth' => $this->grossProfitGrowth,
			'ebitgrowth' => $this->ebitgrowth,
			'operatingIncomeGrowth' => $this->operatingIncomeGrowth,
			'netIncomeGrowth' => $this->netIncomeGrowth,
			'epsgrowth' => $this->epsgrowth,
			'epsdilutedGrowth' => $this->epsdilutedGrowth,
			'weightedAverageSharesGrowth' => $this->weightedAverageSharesGrowth,
			'weightedAverageSharesDilutedGrowth' => $this->weightedAverageSharesDilutedGrowth,
			'dividendsPerShareGrowth' => $this->dividendsPerShareGrowth,
			'operatingCashFlowGrowth' => $this->operatingCashFlowGrowth,
			'receivablesGrowth' => $this->receivablesGrowth,
			'inventoryGrowth' => $this->inventoryGrowth,
			'assetGrowth' => $this->assetGrowth,
			'bookValueperShareGrowth' => $this->bookValueperShareGrowth,
			'debtGrowth' => $this->debtGrowth,
			'rdexpenseGrowth' => $this->rdexpenseGrowth,
			'sgaexpensesGrowth' => $this->sgaexpensesGrowth,
			'freeCashFlowGrowth' => $this->freeCashFlowGrowth,
			'tenYRevenueGrowthPerShare' => $this->tenYRevenueGrowthPerShare,
			'fiveYRevenueGrowthPerShare' => $this->fiveYRevenueGrowthPerShare,
			'threeYRevenueGrowthPerShare' => $this->threeYRevenueGrowthPerShare,
			'tenYOperatingCFGrowthPerShare' => $this->tenYOperatingCFGrowthPerShare,
			'fiveYOperatingCFGrowthPerShare' => $this->fiveYOperatingCFGrowthPerShare,
			'threeYOperatingCFGrowthPerShare' => $this->threeYOperatingCFGrowthPerShare,
			'tenYNetIncomeGrowthPerShare' => $this->tenYNetIncomeGrowthPerShare,
			'fiveYNetIncomeGrowthPerShare' => $this->fiveYNetIncomeGrowthPerShare,
			'threeYNetIncomeGrowthPerShare' => $this->threeYNetIncomeGrowthPerShare,
			'tenYShareholdersEquityGrowthPerShare' => $this->tenYShareholdersEquityGrowthPerShare,
			'fiveYShareholdersEquityGrowthPerShare' => $this->fiveYShareholdersEquityGrowthPerShare,
			'threeYShareholdersEquityGrowthPerShare' => $this->threeYShareholdersEquityGrowthPerShare,
			'tenYDividendperShareGrowthPerShare' => $this->tenYDividendperShareGrowthPerShare,
			'fiveYDividendperShareGrowthPerShare' => $this->fiveYDividendperShareGrowthPerShare,
			'threeYDividendperShareGrowthPerShare' => $this->threeYDividendperShareGrowthPerShare,
			'ebitdaGrowth' => $this->ebitdaGrowth,
			'growthCapitalExpenditure' => $this->growthCapitalExpenditure,
			'tenYBottomLineNetIncomeGrowthPerShare' => $this->tenYBottomLineNetIncomeGrowthPerShare,
			'fiveYBottomLineNetIncomeGrowthPerShare' => $this->fiveYBottomLineNetIncomeGrowthPerShare,
			'threeYBottomLineNetIncomeGrowthPerShare' => $this->threeYBottomLineNetIncomeGrowthPerShare,
		];
	}

}
