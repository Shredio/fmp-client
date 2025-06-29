<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

final readonly class CashFlowStatement
{

	public function __construct(
		public string $symbol,
		public string $date,
		public string $reportedCurrency,
		public string $cik,
		public string $filingDate,
		public string $acceptedDate,
		public string $fiscalYear,
		public Period $period,
		public int $netIncome = 0,
		public int $depreciationAndAmortization = 0,
		public int $deferredIncomeTax = 0,
		public int $stockBasedCompensation = 0,
		public int $changeInWorkingCapital = 0,
		public int $accountsReceivables = 0,
		public int $inventory = 0,
		public int $accountsPayables = 0,
		public int $otherWorkingCapital = 0,
		public int $otherNonCashItems = 0,
		public int $netCashProvidedByOperatingActivities = 0,
		public int $investmentsInPropertyPlantAndEquipment = 0,
		public int $acquisitionsNet = 0,
		public int $purchasesOfInvestments = 0,
		public int $salesMaturitiesOfInvestments = 0,
		public int $otherInvestingActivities = 0,
		public int $netCashProvidedByInvestingActivities = 0,
		public int $netDebtIssuance = 0,
		public int $longTermNetDebtIssuance = 0,
		public int $shortTermNetDebtIssuance = 0,
		public int $netStockIssuance = 0,
		public int $netCommonStockIssuance = 0,
		public int $commonStockIssuance = 0,
		public int $commonStockRepurchased = 0,
		public int $netPreferredStockIssuance = 0,
		public int $netDividendsPaid = 0,
		public int $commonDividendsPaid = 0,
		public int $preferredDividendsPaid = 0,
		public int $otherFinancingActivities = 0,
		public int $netCashProvidedByFinancingActivities = 0,
		public int $effectOfForexChangesOnCash = 0,
		public int $netChangeInCash = 0,
		public int $cashAtEndOfPeriod = 0,
		public int $cashAtBeginningOfPeriod = 0,
		public int $operatingCashFlow = 0,
		public int $capitalExpenditure = 0,
		public int $freeCashFlow = 0,
		public int $incomeTaxesPaid = 0,
		public int $interestPaid = 0,
	)
	{
	}

	/**
	 * @return array{symbol: string, date: string, reportedCurrency: string, cik: string, filingDate: string, acceptedDate: string, fiscalYear: string, period: string, netIncome: int, depreciationAndAmortization: int, deferredIncomeTax: int, stockBasedCompensation: int, changeInWorkingCapital: int, accountsReceivables: int, inventory: int, accountsPayables: int, otherWorkingCapital: int, otherNonCashItems: int, netCashProvidedByOperatingActivities: int, investmentsInPropertyPlantAndEquipment: int, acquisitionsNet: int, purchasesOfInvestments: int, salesMaturitiesOfInvestments: int, otherInvestingActivities: int, netCashProvidedByInvestingActivities: int, netDebtIssuance: int, longTermNetDebtIssuance: int, shortTermNetDebtIssuance: int, netStockIssuance: int, netCommonStockIssuance: int, commonStockIssuance: int, commonStockRepurchased: int, netPreferredStockIssuance: int, netDividendsPaid: int, commonDividendsPaid: int, preferredDividendsPaid: int, otherFinancingActivities: int, netCashProvidedByFinancingActivities: int, effectOfForexChangesOnCash: int, netChangeInCash: int, cashAtEndOfPeriod: int, cashAtBeginningOfPeriod: int, operatingCashFlow: int, capitalExpenditure: int, freeCashFlow: int, incomeTaxesPaid: int, interestPaid: int}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'reportedCurrency' => $this->reportedCurrency,
			'cik' => $this->cik,
			'filingDate' => $this->filingDate,
			'acceptedDate' => $this->acceptedDate,
			'fiscalYear' => $this->fiscalYear,
			'period' => $this->period->value,
			'netIncome' => $this->netIncome,
			'depreciationAndAmortization' => $this->depreciationAndAmortization,
			'deferredIncomeTax' => $this->deferredIncomeTax,
			'stockBasedCompensation' => $this->stockBasedCompensation,
			'changeInWorkingCapital' => $this->changeInWorkingCapital,
			'accountsReceivables' => $this->accountsReceivables,
			'inventory' => $this->inventory,
			'accountsPayables' => $this->accountsPayables,
			'otherWorkingCapital' => $this->otherWorkingCapital,
			'otherNonCashItems' => $this->otherNonCashItems,
			'netCashProvidedByOperatingActivities' => $this->netCashProvidedByOperatingActivities,
			'investmentsInPropertyPlantAndEquipment' => $this->investmentsInPropertyPlantAndEquipment,
			'acquisitionsNet' => $this->acquisitionsNet,
			'purchasesOfInvestments' => $this->purchasesOfInvestments,
			'salesMaturitiesOfInvestments' => $this->salesMaturitiesOfInvestments,
			'otherInvestingActivities' => $this->otherInvestingActivities,
			'netCashProvidedByInvestingActivities' => $this->netCashProvidedByInvestingActivities,
			'netDebtIssuance' => $this->netDebtIssuance,
			'longTermNetDebtIssuance' => $this->longTermNetDebtIssuance,
			'shortTermNetDebtIssuance' => $this->shortTermNetDebtIssuance,
			'netStockIssuance' => $this->netStockIssuance,
			'netCommonStockIssuance' => $this->netCommonStockIssuance,
			'commonStockIssuance' => $this->commonStockIssuance,
			'commonStockRepurchased' => $this->commonStockRepurchased,
			'netPreferredStockIssuance' => $this->netPreferredStockIssuance,
			'netDividendsPaid' => $this->netDividendsPaid,
			'commonDividendsPaid' => $this->commonDividendsPaid,
			'preferredDividendsPaid' => $this->preferredDividendsPaid,
			'otherFinancingActivities' => $this->otherFinancingActivities,
			'netCashProvidedByFinancingActivities' => $this->netCashProvidedByFinancingActivities,
			'effectOfForexChangesOnCash' => $this->effectOfForexChangesOnCash,
			'netChangeInCash' => $this->netChangeInCash,
			'cashAtEndOfPeriod' => $this->cashAtEndOfPeriod,
			'cashAtBeginningOfPeriod' => $this->cashAtBeginningOfPeriod,
			'operatingCashFlow' => $this->operatingCashFlow,
			'capitalExpenditure' => $this->capitalExpenditure,
			'freeCashFlow' => $this->freeCashFlow,
			'incomeTaxesPaid' => $this->incomeTaxesPaid,
			'interestPaid' => $this->interestPaid,
		];
	}

}