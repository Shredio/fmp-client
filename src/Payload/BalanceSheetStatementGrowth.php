<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class BalanceSheetStatementGrowth
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $date,
		public string $symbol,
		public string $calendarYear,
		public Period $period,
		public float $growthCashAndCashEquivalents,
		public float $growthShortTermInvestments,
		public float $growthCashAndShortTermInvestments,
		public float $growthNetReceivables,
		public float $growthInventory,
		public float $growthOtherCurrentAssets,
		public float $growthTotalCurrentAssets,
		public float $growthPropertyPlantEquipmentNet,
		public float $growthGoodwill,
		public float $growthIntangibleAssets,
		public float $growthGoodwillAndIntangibleAssets,
		public float $growthLongTermInvestments,
		public float $growthTaxAssets,
		public float $growthOtherNonCurrentAssets,
		public float $growthTotalNonCurrentAssets,
		public float $growthOtherAssets,
		public float $growthTotalAssets,
		public float $growthAccountPayables,
		public float $growthShortTermDebt,
		public float $growthTaxPayables,
		public float $growthDeferredRevenue,
		public float $growthOtherCurrentLiabilities,
		public float $growthTotalCurrentLiabilities,
		public float $growthLongTermDebt,
		public float $growthDeferredRevenueNonCurrent,
		public float $growthDeferrredTaxLiabilitiesNonCurrent,
		public float $growthOtherNonCurrentLiabilities,
		public float $growthTotalNonCurrentLiabilities,
		public float $growthOtherLiabilities,
		public float $growthTotalLiabilities,
		public float $growthCommonStock,
		public float $growthRetainedEarnings,
		public float $growthAccumulatedOtherComprehensiveIncomeLoss,
		public float $growthOthertotalStockholdersEquity,
		public float $growthTotalStockholdersEquity,
		public float $growthTotalLiabilitiesAndStockholdersEquity,
		public float $growthTotalInvestments,
		public float $growthTotalDebt,
		public float $growthNetDebt,
	)
	{
	}


	/**
	 * @return array{
	 *     date: string,
	 *     symbol: non-empty-string,
	 *     calendarYear: string,
	 *     period: string,
	 *     growthCashAndCashEquivalents: float,
	 *     growthShortTermInvestments: float,
	 *     growthCashAndShortTermInvestments: float,
	 *     growthNetReceivables: float,
	 *     growthInventory: float,
	 *     growthOtherCurrentAssets: float,
	 *     growthTotalCurrentAssets: float,
	 *     growthPropertyPlantEquipmentNet: float,
	 *     growthGoodwill: float,
	 *     growthIntangibleAssets: float,
	 *     growthGoodwillAndIntangibleAssets: float,
	 *     growthLongTermInvestments: float,
	 *     growthTaxAssets: float,
	 *     growthOtherNonCurrentAssets: float,
	 *     growthTotalNonCurrentAssets: float,
	 *     growthOtherAssets: float,
	 *     growthTotalAssets: float,
	 *     growthAccountPayables: float,
	 *     growthShortTermDebt: float,
	 *     growthTaxPayables: float,
	 *     growthDeferredRevenue: float,
	 *     growthOtherCurrentLiabilities: float,
	 *     growthTotalCurrentLiabilities: float,
	 *     growthLongTermDebt: float,
	 *     growthDeferredRevenueNonCurrent: float,
	 *     growthDeferrredTaxLiabilitiesNonCurrent: float,
	 *     growthOtherNonCurrentLiabilities: float,
	 *     growthTotalNonCurrentLiabilities: float,
	 *     growthOtherLiabilities: float,
	 *     growthTotalLiabilities: float,
	 *     growthCommonStock: float,
	 *     growthRetainedEarnings: float,
	 *     growthAccumulatedOtherComprehensiveIncomeLoss: float,
	 *     growthOthertotalStockholdersEquity: float,
	 *     growthTotalStockholdersEquity: float,
	 *     growthTotalLiabilitiesAndStockholdersEquity: float,
	 *     growthTotalInvestments: float,
	 *     growthTotalDebt: float,
	 *     growthNetDebt: float
	 * }
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'symbol' => $this->symbol,
			'calendarYear' => $this->calendarYear,
			'period' => $this->period->value,
			'growthCashAndCashEquivalents' => $this->growthCashAndCashEquivalents,
			'growthShortTermInvestments' => $this->growthShortTermInvestments,
			'growthCashAndShortTermInvestments' => $this->growthCashAndShortTermInvestments,
			'growthNetReceivables' => $this->growthNetReceivables,
			'growthInventory' => $this->growthInventory,
			'growthOtherCurrentAssets' => $this->growthOtherCurrentAssets,
			'growthTotalCurrentAssets' => $this->growthTotalCurrentAssets,
			'growthPropertyPlantEquipmentNet' => $this->growthPropertyPlantEquipmentNet,
			'growthGoodwill' => $this->growthGoodwill,
			'growthIntangibleAssets' => $this->growthIntangibleAssets,
			'growthGoodwillAndIntangibleAssets' => $this->growthGoodwillAndIntangibleAssets,
			'growthLongTermInvestments' => $this->growthLongTermInvestments,
			'growthTaxAssets' => $this->growthTaxAssets,
			'growthOtherNonCurrentAssets' => $this->growthOtherNonCurrentAssets,
			'growthTotalNonCurrentAssets' => $this->growthTotalNonCurrentAssets,
			'growthOtherAssets' => $this->growthOtherAssets,
			'growthTotalAssets' => $this->growthTotalAssets,
			'growthAccountPayables' => $this->growthAccountPayables,
			'growthShortTermDebt' => $this->growthShortTermDebt,
			'growthTaxPayables' => $this->growthTaxPayables,
			'growthDeferredRevenue' => $this->growthDeferredRevenue,
			'growthOtherCurrentLiabilities' => $this->growthOtherCurrentLiabilities,
			'growthTotalCurrentLiabilities' => $this->growthTotalCurrentLiabilities,
			'growthLongTermDebt' => $this->growthLongTermDebt,
			'growthDeferredRevenueNonCurrent' => $this->growthDeferredRevenueNonCurrent,
			'growthDeferrredTaxLiabilitiesNonCurrent' => $this->growthDeferrredTaxLiabilitiesNonCurrent,
			'growthOtherNonCurrentLiabilities' => $this->growthOtherNonCurrentLiabilities,
			'growthTotalNonCurrentLiabilities' => $this->growthTotalNonCurrentLiabilities,
			'growthOtherLiabilities' => $this->growthOtherLiabilities,
			'growthTotalLiabilities' => $this->growthTotalLiabilities,
			'growthCommonStock' => $this->growthCommonStock,
			'growthRetainedEarnings' => $this->growthRetainedEarnings,
			'growthAccumulatedOtherComprehensiveIncomeLoss' => $this->growthAccumulatedOtherComprehensiveIncomeLoss,
			'growthOthertotalStockholdersEquity' => $this->growthOthertotalStockholdersEquity,
			'growthTotalStockholdersEquity' => $this->growthTotalStockholdersEquity,
			'growthTotalLiabilitiesAndStockholdersEquity' => $this->growthTotalLiabilitiesAndStockholdersEquity,
			'growthTotalInvestments' => $this->growthTotalInvestments,
			'growthTotalDebt' => $this->growthTotalDebt,
			'growthNetDebt' => $this->growthNetDebt,
		];
	}

}
