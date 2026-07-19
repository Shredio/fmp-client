<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

use Shredio\FmpClient\TypeSchema\NullAsZeroConversion;
use Shredio\TypeSchema\Context\SourceFormat;
use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol', contextFactory: 'createContext')]
final readonly class CashFlowStatement
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
		public float $netIncome = 0.0,
		public float $depreciationAndAmortization = 0.0,
		public float $deferredIncomeTax = 0.0,
		public float $stockBasedCompensation = 0.0,
		public float $changeInWorkingCapital = 0.0,
		public float $accountsReceivables = 0.0,
		public float $inventory = 0.0,
		public float $accountsPayables = 0.0,
		public float $otherWorkingCapital = 0.0,
		public float $otherNonCashItems = 0.0,
		public float $netCashProvidedByOperatingActivities = 0.0,
		public float $investmentsInPropertyPlantAndEquipment = 0.0,
		public float $acquisitionsNet = 0.0,
		public float $purchasesOfInvestments = 0.0,
		public float $salesMaturitiesOfInvestments = 0.0,
		public float $otherInvestingActivities = 0.0,
		public float $netCashProvidedByInvestingActivities = 0.0,
		public float $netDebtIssuance = 0.0,
		public float $longTermNetDebtIssuance = 0.0,
		public float $shortTermNetDebtIssuance = 0.0,
		public float $netStockIssuance = 0.0,
		public float $netCommonStockIssuance = 0.0,
		public float $commonStockIssuance = 0.0,
		public float $commonStockRepurchased = 0.0,
		public float $netPreferredStockIssuance = 0.0,
		public float $netDividendsPaid = 0.0,
		public float $commonDividendsPaid = 0.0,
		public float $preferredDividendsPaid = 0.0,
		public float $otherFinancingActivities = 0.0,
		public float $netCashProvidedByFinancingActivities = 0.0,
		public float $effectOfForexChangesOnCash = 0.0,
		public float $netChangeInCash = 0.0,
		public float $cashAtEndOfPeriod = 0.0,
		public float $cashAtBeginningOfPeriod = 0.0,
		public float $operatingCashFlow = 0.0,
		public float $capitalExpenditure = 0.0,
		public float $freeCashFlow = 0.0,
		public float $incomeTaxesPaid = 0.0,
		public float $interestPaid = 0.0,
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
	 *     period: value-of<Period>,
	 *     netIncome: float,
	 *     depreciationAndAmortization: float,
	 *     deferredIncomeTax: float,
	 *     stockBasedCompensation: float,
	 *     changeInWorkingCapital: float,
	 *     accountsReceivables: float,
	 *     inventory: float,
	 *     accountsPayables: float,
	 *     otherWorkingCapital: float,
	 *     otherNonCashItems: float,
	 *     netCashProvidedByOperatingActivities: float,
	 *     investmentsInPropertyPlantAndEquipment: float,
	 *     acquisitionsNet: float,
	 *     purchasesOfInvestments: float,
	 *     salesMaturitiesOfInvestments: float,
	 *     otherInvestingActivities: float,
	 *     netCashProvidedByInvestingActivities: float,
	 *     netDebtIssuance: float,
	 *     longTermNetDebtIssuance: float,
	 *     shortTermNetDebtIssuance: float,
	 *     netStockIssuance: float,
	 *     netCommonStockIssuance: float,
	 *     commonStockIssuance: float,
	 *     commonStockRepurchased: float,
	 *     netPreferredStockIssuance: float,
	 *     netDividendsPaid: float,
	 *     commonDividendsPaid: float,
	 *     preferredDividendsPaid: float,
	 *     otherFinancingActivities: float,
	 *     netCashProvidedByFinancingActivities: float,
	 *     effectOfForexChangesOnCash: float,
	 *     netChangeInCash: float,
	 *     cashAtEndOfPeriod: float,
	 *     cashAtBeginningOfPeriod: float,
	 *     operatingCashFlow: float,
	 *     capitalExpenditure: float,
	 *     freeCashFlow: float,
	 *     incomeTaxesPaid: float,
	 *     interestPaid: float
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

	/**
	 * @internal Internal method for object mapper
	 */
	public static function createContext(TypeContext $context): TypeContext
	{
		$isCsv = $context->getOption(SourceFormat::class)?->is('csv') === true;

		return $context->withConversionStrategy(new NullAsZeroConversion(
			$context->conversionStrategy,
			handleNaN: $isCsv,
			handleInfinity: $isCsv,
		));
	}

}
