<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class CashFlowStatementGrowth
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $date,
		public string $symbol,
		public string $calendarYear,
		public Period $period,
		public float $growthNetIncome,
		public float $growthDepreciationAndAmortization,
		public float $growthDeferredIncomeTax,
		public float $growthStockBasedCompensation,
		public float $growthChangeInWorkingCapital,
		public float $growthAccountsReceivables,
		public float $growthInventory,
		public float $growthAccountsPayables,
		public float $growthOtherWorkingCapital,
		public float $growthOtherNonCashItems,
		public float $growthNetCashProvidedByOperatingActivites,
		public float $growthInvestmentsInPropertyPlantAndEquipment,
		public float $growthAcquisitionsNet,
		public float $growthPurchasesOfInvestments,
		public float $growthSalesMaturitiesOfInvestments,
		public float $growthOtherInvestingActivites,
		public float $growthNetCashUsedForInvestingActivites,
		public float $growthDebtRepayment,
		public float $growthCommonStockIssued,
		public float $growthCommonStockRepurchased,
		public float $growthDividendsPaid,
		public float $growthOtherFinancingActivites,
		public float $growthNetCashUsedProvidedByFinancingActivities,
		public float $growthEffectOfForexChangesOnCash,
		public float $growthNetChangeInCash,
		public float $growthCashAtEndOfPeriod,
		public float $growthCashAtBeginningOfPeriod,
		public float $growthOperatingCashFlow,
		public float $growthCapitalExpenditure,
		public float $growthFreeCashFlow,
	)
	{
	}


	/**
	 * @return array{
	 *     date: string,
	 *     symbol: non-empty-string,
	 *     calendarYear: string,
	 *     period: value-of<Period>,
	 *     growthNetIncome: float,
	 *     growthDepreciationAndAmortization: float,
	 *     growthDeferredIncomeTax: float,
	 *     growthStockBasedCompensation: float,
	 *     growthChangeInWorkingCapital: float,
	 *     growthAccountsReceivables: float,
	 *     growthInventory: float,
	 *     growthAccountsPayables: float,
	 *     growthOtherWorkingCapital: float,
	 *     growthOtherNonCashItems: float,
	 *     growthNetCashProvidedByOperatingActivites: float,
	 *     growthInvestmentsInPropertyPlantAndEquipment: float,
	 *     growthAcquisitionsNet: float,
	 *     growthPurchasesOfInvestments: float,
	 *     growthSalesMaturitiesOfInvestments: float,
	 *     growthOtherInvestingActivites: float,
	 *     growthNetCashUsedForInvestingActivites: float,
	 *     growthDebtRepayment: float,
	 *     growthCommonStockIssued: float,
	 *     growthCommonStockRepurchased: float,
	 *     growthDividendsPaid: float,
	 *     growthOtherFinancingActivites: float,
	 *     growthNetCashUsedProvidedByFinancingActivities: float,
	 *     growthEffectOfForexChangesOnCash: float,
	 *     growthNetChangeInCash: float,
	 *     growthCashAtEndOfPeriod: float,
	 *     growthCashAtBeginningOfPeriod: float,
	 *     growthOperatingCashFlow: float,
	 *     growthCapitalExpenditure: float,
	 *     growthFreeCashFlow: float
	 * }
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'symbol' => $this->symbol,
			'calendarYear' => $this->calendarYear,
			'period' => $this->period->value,
			'growthNetIncome' => $this->growthNetIncome,
			'growthDepreciationAndAmortization' => $this->growthDepreciationAndAmortization,
			'growthDeferredIncomeTax' => $this->growthDeferredIncomeTax,
			'growthStockBasedCompensation' => $this->growthStockBasedCompensation,
			'growthChangeInWorkingCapital' => $this->growthChangeInWorkingCapital,
			'growthAccountsReceivables' => $this->growthAccountsReceivables,
			'growthInventory' => $this->growthInventory,
			'growthAccountsPayables' => $this->growthAccountsPayables,
			'growthOtherWorkingCapital' => $this->growthOtherWorkingCapital,
			'growthOtherNonCashItems' => $this->growthOtherNonCashItems,
			'growthNetCashProvidedByOperatingActivites' => $this->growthNetCashProvidedByOperatingActivites,
			'growthInvestmentsInPropertyPlantAndEquipment' => $this->growthInvestmentsInPropertyPlantAndEquipment,
			'growthAcquisitionsNet' => $this->growthAcquisitionsNet,
			'growthPurchasesOfInvestments' => $this->growthPurchasesOfInvestments,
			'growthSalesMaturitiesOfInvestments' => $this->growthSalesMaturitiesOfInvestments,
			'growthOtherInvestingActivites' => $this->growthOtherInvestingActivites,
			'growthNetCashUsedForInvestingActivites' => $this->growthNetCashUsedForInvestingActivites,
			'growthDebtRepayment' => $this->growthDebtRepayment,
			'growthCommonStockIssued' => $this->growthCommonStockIssued,
			'growthCommonStockRepurchased' => $this->growthCommonStockRepurchased,
			'growthDividendsPaid' => $this->growthDividendsPaid,
			'growthOtherFinancingActivites' => $this->growthOtherFinancingActivites,
			'growthNetCashUsedProvidedByFinancingActivities' => $this->growthNetCashUsedProvidedByFinancingActivities,
			'growthEffectOfForexChangesOnCash' => $this->growthEffectOfForexChangesOnCash,
			'growthNetChangeInCash' => $this->growthNetChangeInCash,
			'growthCashAtEndOfPeriod' => $this->growthCashAtEndOfPeriod,
			'growthCashAtBeginningOfPeriod' => $this->growthCashAtBeginningOfPeriod,
			'growthOperatingCashFlow' => $this->growthOperatingCashFlow,
			'growthCapitalExpenditure' => $this->growthCapitalExpenditure,
			'growthFreeCashFlow' => $this->growthFreeCashFlow,
		];
	}

}
