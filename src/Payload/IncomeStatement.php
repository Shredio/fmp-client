<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

use Shredio\FmpClient\TypeSchema\NullAsZeroConversion;
use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol', contextFactory: 'createContext')]
final readonly class IncomeStatement
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public string $reportedCurrency,
		public string $cik,
		public string $filingDate,
		public string $acceptedDate,
		public string $fiscalYear,
		public Period $period,
		public float $revenue = 0.0,
		public float $costOfRevenue = 0.0,
		public float $grossProfit = 0.0,
		public float $researchAndDevelopmentExpenses = 0.0,
		public float $generalAndAdministrativeExpenses = 0.0,
		public float $sellingAndMarketingExpenses = 0.0,
		public float $sellingGeneralAndAdministrativeExpenses = 0.0,
		public float $otherExpenses = 0.0,
		public float $operatingExpenses = 0.0,
		public float $costAndExpenses = 0.0,
		public float $netInterestIncome = 0.0,
		public float $interestIncome = 0.0,
		public float $interestExpense = 0.0,
		public float $depreciationAndAmortization = 0.0,
		public float $ebitda = 0.0,
		public float $ebit = 0.0,
		public float $nonOperatingIncomeExcludingInterest = 0.0,
		public float $operatingIncome = 0.0,
		public float $totalOtherIncomeExpensesNet = 0.0,
		public float $incomeBeforeTax = 0.0,
		public float $incomeTaxExpense = 0.0,
		public float $netIncomeFromContinuingOperations = 0.0,
		public float $netIncomeFromDiscontinuedOperations = 0.0,
		public float $otherAdjustmentsToNetIncome = 0.0,
		public float $netIncome = 0.0,
		public float $netIncomeDeductions = 0.0,
		public float $bottomLineNetIncome = 0.0,
		public float $eps = 0.0,
		public float $epsDiluted = 0.0,
		public int|float $weightedAverageShsOut = 0,
		public int|float $weightedAverageShsOutDil = 0,
	)
	{
	}


	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     date: string,
	 *     reportedCurrency: string,
	 *     cik: string,
	 *     filingDate: string,
	 *     acceptedDate: string,
	 *     fiscalYear: string,
	 *     period: string,
	 *     revenue: float,
	 *     costOfRevenue: float,
	 *     grossProfit: float,
	 *     researchAndDevelopmentExpenses: float,
	 *     generalAndAdministrativeExpenses: float,
	 *     sellingAndMarketingExpenses: float,
	 *     sellingGeneralAndAdministrativeExpenses: float,
	 *     otherExpenses: float,
	 *     operatingExpenses: float,
	 *     costAndExpenses: float,
	 *     netInterestIncome: float,
	 *     interestIncome: float,
	 *     interestExpense: float,
	 *     depreciationAndAmortization: float,
	 *     ebitda: float,
	 *     ebit: float,
	 *     nonOperatingIncomeExcludingInterest: float,
	 *     operatingIncome: float,
	 *     totalOtherIncomeExpensesNet: float,
	 *     incomeBeforeTax: float,
	 *     incomeTaxExpense: float,
	 *     netIncomeFromContinuingOperations: float,
	 *     netIncomeFromDiscontinuedOperations: float,
	 *     otherAdjustmentsToNetIncome: float,
	 *     netIncome: float,
	 *     netIncomeDeductions: float,
	 *     bottomLineNetIncome: float,
	 *     eps: float,
	 *     epsDiluted: float,
	 *     weightedAverageShsOut: int|float,
	 *     weightedAverageShsOutDil: int|float
	 * }
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

	/**
	 * @internal Internal method for object mapper
	 */
	public static function createContext(TypeContext $context): TypeContext
	{
		return $context->withConversionStrategy(new NullAsZeroConversion($context->conversionStrategy));
	}

}
