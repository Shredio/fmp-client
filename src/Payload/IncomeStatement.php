<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

final readonly class IncomeStatement
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
		public int $revenue = 0,
		public int $costOfRevenue = 0,
		public int $grossProfit = 0,
		public int $researchAndDevelopmentExpenses = 0,
		public int $generalAndAdministrativeExpenses = 0,
		public int $sellingAndMarketingExpenses = 0,
		public int $sellingGeneralAndAdministrativeExpenses = 0,
		public int $otherExpenses = 0,
		public int $operatingExpenses = 0,
		public int $costAndExpenses = 0,
		public int $netInterestIncome = 0,
		public int $interestIncome = 0,
		public int $interestExpense = 0,
		public int $depreciationAndAmortization = 0,
		public int $ebitda = 0,
		public int $ebit = 0,
		public int $nonOperatingIncomeExcludingInterest = 0,
		public int $operatingIncome = 0,
		public int $totalOtherIncomeExpensesNet = 0,
		public int $incomeBeforeTax = 0,
		public int $incomeTaxExpense = 0,
		public int $netIncomeFromContinuingOperations = 0,
		public int $netIncomeFromDiscontinuedOperations = 0,
		public int $otherAdjustmentsToNetIncome = 0,
		public int $netIncome = 0,
		public int $netIncomeDeductions = 0,
		public int $bottomLineNetIncome = 0,
		public float $eps = 0.0,
		public float $epsDiluted = 0.0,
		public int $weightedAverageShsOut = 0,
		public int $weightedAverageShsOutDil = 0,
	)
	{
	}

	/**
	 * @return array{symbol: string, date: string, reportedCurrency: string, cik: string, filingDate: string, acceptedDate: string, fiscalYear: string, period: string, revenue: int, costOfRevenue: int, grossProfit: int, researchAndDevelopmentExpenses: int, generalAndAdministrativeExpenses: int, sellingAndMarketingExpenses: int, sellingGeneralAndAdministrativeExpenses: int, otherExpenses: int, operatingExpenses: int, costAndExpenses: int, netInterestIncome: int, interestIncome: int, interestExpense: int, depreciationAndAmortization: int, ebitda: int, ebit: int, nonOperatingIncomeExcludingInterest: int, operatingIncome: int, totalOtherIncomeExpensesNet: int, incomeBeforeTax: int, incomeTaxExpense: int, netIncomeFromContinuingOperations: int, netIncomeFromDiscontinuedOperations: int, otherAdjustmentsToNetIncome: int, netIncome: int, netIncomeDeductions: int, bottomLineNetIncome: int, eps: float, epsDiluted: float, weightedAverageShsOut: int, weightedAverageShsOutDil: int}
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
			'revenue' => $this->revenue,
			'costOfRevenue' => $this->costOfRevenue,
			'grossProfit' => $this->grossProfit,
			'researchAndDevelopmentExpenses' => $this->researchAndDevelopmentExpenses,
			'generalAndAdministrativeExpenses' => $this->generalAndAdministrativeExpenses,
			'sellingAndMarketingExpenses' => $this->sellingAndMarketingExpenses,
			'sellingGeneralAndAdministrativeExpenses' => $this->sellingGeneralAndAdministrativeExpenses,
			'otherExpenses' => $this->otherExpenses,
			'operatingExpenses' => $this->operatingExpenses,
			'costAndExpenses' => $this->costAndExpenses,
			'netInterestIncome' => $this->netInterestIncome,
			'interestIncome' => $this->interestIncome,
			'interestExpense' => $this->interestExpense,
			'depreciationAndAmortization' => $this->depreciationAndAmortization,
			'ebitda' => $this->ebitda,
			'ebit' => $this->ebit,
			'nonOperatingIncomeExcludingInterest' => $this->nonOperatingIncomeExcludingInterest,
			'operatingIncome' => $this->operatingIncome,
			'totalOtherIncomeExpensesNet' => $this->totalOtherIncomeExpensesNet,
			'incomeBeforeTax' => $this->incomeBeforeTax,
			'incomeTaxExpense' => $this->incomeTaxExpense,
			'netIncomeFromContinuingOperations' => $this->netIncomeFromContinuingOperations,
			'netIncomeFromDiscontinuedOperations' => $this->netIncomeFromDiscontinuedOperations,
			'otherAdjustmentsToNetIncome' => $this->otherAdjustmentsToNetIncome,
			'netIncome' => $this->netIncome,
			'netIncomeDeductions' => $this->netIncomeDeductions,
			'bottomLineNetIncome' => $this->bottomLineNetIncome,
			'eps' => $this->eps,
			'epsDiluted' => $this->epsDiluted,
			'weightedAverageShsOut' => $this->weightedAverageShsOut,
			'weightedAverageShsOutDil' => $this->weightedAverageShsOutDil,
		];
	}

}