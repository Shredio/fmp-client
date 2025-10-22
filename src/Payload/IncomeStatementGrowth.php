<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class IncomeStatementGrowth
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $date,
		public string $symbol,
		public string $calendarYear,
		public Period $period,
		public float $growthRevenue,
		public float $growthCostOfRevenue,
		public float $growthGrossProfit,
		public float $growthGrossProfitRatio,
		public float $growthResearchAndDevelopmentExpenses,
		public float $growthGeneralAndAdministrativeExpenses,
		public float $growthSellingAndMarketingExpenses,
		public float $growthOtherExpenses,
		public float $growthOperatingExpenses,
		public float $growthCostAndExpenses,
		public float $growthInterestExpense,
		public float $growthDepreciationAndAmortization,
		public float $growthEBITDA,
		public float $growthEBITDARatio,
		public float $growthOperatingIncome,
		public float $growthOperatingIncomeRatio,
		public float $growthTotalOtherIncomeExpensesNet,
		public float $growthIncomeBeforeTax,
		public float $growthIncomeBeforeTaxRatio,
		public float $growthIncomeTaxExpense,
		public float $growthNetIncome,
		public float $growthNetIncomeRatio,
		public float $growthEPS,
		public float $growthEPSDiluted,
		public float $growthWeightedAverageShsOut,
		public float $growthWeightedAverageShsOutDil,
	)
	{
	}


	/**
	 * @return array{
	 *     date: string,
	 *     symbol: non-empty-string,
	 *     calendarYear: string,
	 *     period: string,
	 *     growthRevenue: float,
	 *     growthCostOfRevenue: float,
	 *     growthGrossProfit: float,
	 *     growthGrossProfitRatio: float,
	 *     growthResearchAndDevelopmentExpenses: float,
	 *     growthGeneralAndAdministrativeExpenses: float,
	 *     growthSellingAndMarketingExpenses: float,
	 *     growthOtherExpenses: float,
	 *     growthOperatingExpenses: float,
	 *     growthCostAndExpenses: float,
	 *     growthInterestExpense: float,
	 *     growthDepreciationAndAmortization: float,
	 *     growthEBITDA: float,
	 *     growthEBITDARatio: float,
	 *     growthOperatingIncome: float,
	 *     growthOperatingIncomeRatio: float,
	 *     growthTotalOtherIncomeExpensesNet: float,
	 *     growthIncomeBeforeTax: float,
	 *     growthIncomeBeforeTaxRatio: float,
	 *     growthIncomeTaxExpense: float,
	 *     growthNetIncome: float,
	 *     growthNetIncomeRatio: float,
	 *     growthEPS: float,
	 *     growthEPSDiluted: float,
	 *     growthWeightedAverageShsOut: float,
	 *     growthWeightedAverageShsOutDil: float
	 * }
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'symbol' => $this->symbol,
			'calendarYear' => $this->calendarYear,
			'period' => $this->period->value,
			'growthRevenue' => $this->growthRevenue,
			'growthCostOfRevenue' => $this->growthCostOfRevenue,
			'growthGrossProfit' => $this->growthGrossProfit,
			'growthGrossProfitRatio' => $this->growthGrossProfitRatio,
			'growthResearchAndDevelopmentExpenses' => $this->growthResearchAndDevelopmentExpenses,
			'growthGeneralAndAdministrativeExpenses' => $this->growthGeneralAndAdministrativeExpenses,
			'growthSellingAndMarketingExpenses' => $this->growthSellingAndMarketingExpenses,
			'growthOtherExpenses' => $this->growthOtherExpenses,
			'growthOperatingExpenses' => $this->growthOperatingExpenses,
			'growthCostAndExpenses' => $this->growthCostAndExpenses,
			'growthInterestExpense' => $this->growthInterestExpense,
			'growthDepreciationAndAmortization' => $this->growthDepreciationAndAmortization,
			'growthEBITDA' => $this->growthEBITDA,
			'growthEBITDARatio' => $this->growthEBITDARatio,
			'growthOperatingIncome' => $this->growthOperatingIncome,
			'growthOperatingIncomeRatio' => $this->growthOperatingIncomeRatio,
			'growthTotalOtherIncomeExpensesNet' => $this->growthTotalOtherIncomeExpensesNet,
			'growthIncomeBeforeTax' => $this->growthIncomeBeforeTax,
			'growthIncomeBeforeTaxRatio' => $this->growthIncomeBeforeTaxRatio,
			'growthIncomeTaxExpense' => $this->growthIncomeTaxExpense,
			'growthNetIncome' => $this->growthNetIncome,
			'growthNetIncomeRatio' => $this->growthNetIncomeRatio,
			'growthEPS' => $this->growthEPS,
			'growthEPSDiluted' => $this->growthEPSDiluted,
			'growthWeightedAverageShsOut' => $this->growthWeightedAverageShsOut,
			'growthWeightedAverageShsOutDil' => $this->growthWeightedAverageShsOutDil,
		];
	}

}
