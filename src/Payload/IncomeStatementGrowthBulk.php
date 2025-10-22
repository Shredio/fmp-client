<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol', discardExtraItems: true)]
final readonly class IncomeStatementGrowthBulk
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
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
		public float $growthInterestIncome,
		public float $growthInterestExpense,
		public float $growthDepreciationAndAmortization,
		public float $growthEBITDA,
		public float $growthOperatingIncome,
		public float $growthIncomeBeforeTax,
		public float $growthIncomeTaxExpense,
		public float $growthNetIncome,
		public float $growthEPS,
		public float $growthEPSDiluted,
		public float $growthWeightedAverageShsOut,
		public float $growthWeightedAverageShsOutDil,
	)
	{
	}


	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     date: string,
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
	 *     growthInterestIncome: float,
	 *     growthInterestExpense: float,
	 *     growthDepreciationAndAmortization: float,
	 *     growthEBITDA: float,
	 *     growthOperatingIncome: float,
	 *     growthIncomeBeforeTax: float,
	 *     growthIncomeTaxExpense: float,
	 *     growthNetIncome: float,
	 *     growthEPS: float,
	 *     growthEPSDiluted: float,
	 *     growthWeightedAverageShsOut: float,
	 *     growthWeightedAverageShsOutDil: float
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
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
			'growthInterestIncome' => $this->growthInterestIncome,
			'growthInterestExpense' => $this->growthInterestExpense,
			'growthDepreciationAndAmortization' => $this->growthDepreciationAndAmortization,
			'growthEBITDA' => $this->growthEBITDA,
			'growthOperatingIncome' => $this->growthOperatingIncome,
			'growthIncomeBeforeTax' => $this->growthIncomeBeforeTax,
			'growthIncomeTaxExpense' => $this->growthIncomeTaxExpense,
			'growthNetIncome' => $this->growthNetIncome,
			'growthEPS' => $this->growthEPS,
			'growthEPSDiluted' => $this->growthEPSDiluted,
			'growthWeightedAverageShsOut' => $this->growthWeightedAverageShsOut,
			'growthWeightedAverageShsOutDil' => $this->growthWeightedAverageShsOutDil,
		];
	}

}
