<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\Enum\Period;

use Shredio\FmpClient\TypeSchema\NullAsZeroConversion;
use Shredio\TypeSchema\Context\SourceFormat;
use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol', contextFactory: 'createContext')]
final readonly class BalanceSheetStatement
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
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
		public float $cashAndCashEquivalents = 0.0,
		public float $shortTermInvestments = 0.0,
		public float $cashAndShortTermInvestments = 0.0,
		public float $netReceivables = 0.0,
		public float $accountsReceivables = 0.0,
		public float $otherReceivables = 0.0,
		public float $inventory = 0.0,
		public float $prepaids = 0.0,
		public float $otherCurrentAssets = 0.0,
		public float $totalCurrentAssets = 0.0,
		public float $propertyPlantEquipmentNet = 0.0,
		public float $goodwill = 0.0,
		public float $intangibleAssets = 0.0,
		public float $goodwillAndIntangibleAssets = 0.0,
		public float $longTermInvestments = 0.0,
		public float $taxAssets = 0.0,
		public float $otherNonCurrentAssets = 0.0,
		public float $totalNonCurrentAssets = 0.0,
		public float $otherAssets = 0.0,
		public float $totalAssets = 0.0,
		public float $totalPayables = 0.0,
		public float $accountPayables = 0.0,
		public float $otherPayables = 0.0,
		public float $accruedExpenses = 0.0,
		public float $shortTermDebt = 0.0,
		public float $capitalLeaseObligationsCurrent = 0.0,
		public float $taxPayables = 0.0,
		public float $deferredRevenue = 0.0,
		public float $otherCurrentLiabilities = 0.0,
		public float $totalCurrentLiabilities = 0.0,
		public float $longTermDebt = 0.0,
		public float $capitalLeaseObligationsNonCurrent = 0.0,
		public float $deferredRevenueNonCurrent = 0.0,
		public float $deferredTaxLiabilitiesNonCurrent = 0.0,
		public float $otherNonCurrentLiabilities = 0.0,
		public float $totalNonCurrentLiabilities = 0.0,
		public float $otherLiabilities = 0.0,
		public float $capitalLeaseObligations = 0.0,
		public float $totalLiabilities = 0.0,
		public float $treasuryStock = 0.0,
		public float $preferredStock = 0.0,
		public float $commonStock = 0.0,
		public float $retainedEarnings = 0.0,
		public float $additionalPaidInCapital = 0.0,
		public float $accumulatedOtherComprehensiveIncomeLoss = 0.0,
		public float $otherTotalStockholdersEquity = 0.0,
		public float $totalStockholdersEquity = 0.0,
		public float $totalEquity = 0.0,
		public float $minorityInterest = 0.0,
		public float $totalLiabilitiesAndTotalEquity = 0.0,
		public float $totalInvestments = 0.0,
		public float $totalDebt = 0.0,
		public float $netDebt = 0.0,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     date: non-empty-string,
	 *     reportedCurrency: string,
	 *     cik: string,
	 *     filingDate: string,
	 *     acceptedDate: string,
	 *     fiscalYear: string,
	 *     period: string,
	 *     cashAndCashEquivalents: float,
	 *     shortTermInvestments: float,
	 *     cashAndShortTermInvestments: float,
	 *     netReceivables: float,
	 *     accountsReceivables: float,
	 *     otherReceivables: float,
	 *     inventory: float,
	 *     prepaids: float,
	 *     otherCurrentAssets: float,
	 *     totalCurrentAssets: float,
	 *     propertyPlantEquipmentNet: float,
	 *     goodwill: float,
	 *     intangibleAssets: float,
	 *     goodwillAndIntangibleAssets: float,
	 *     longTermInvestments: float,
	 *     taxAssets: float,
	 *     otherNonCurrentAssets: float,
	 *     totalNonCurrentAssets: float,
	 *     otherAssets: float,
	 *     totalAssets: float,
	 *     totalPayables: float,
	 *     accountPayables: float,
	 *     otherPayables: float,
	 *     accruedExpenses: float,
	 *     shortTermDebt: float,
	 *     capitalLeaseObligationsCurrent: float,
	 *     taxPayables: float,
	 *     deferredRevenue: float,
	 *     otherCurrentLiabilities: float,
	 *     totalCurrentLiabilities: float,
	 *     longTermDebt: float,
	 *     capitalLeaseObligationsNonCurrent: float,
	 *     deferredRevenueNonCurrent: float,
	 *     deferredTaxLiabilitiesNonCurrent: float,
	 *     otherNonCurrentLiabilities: float,
	 *     totalNonCurrentLiabilities: float,
	 *     otherLiabilities: float,
	 *     capitalLeaseObligations: float,
	 *     totalLiabilities: float,
	 *     treasuryStock: float,
	 *     preferredStock: float,
	 *     commonStock: float,
	 *     retainedEarnings: float,
	 *     additionalPaidInCapital: float,
	 *     accumulatedOtherComprehensiveIncomeLoss: float,
	 *     otherTotalStockholdersEquity: float,
	 *     totalStockholdersEquity: float,
	 *     totalEquity: float,
	 *     minorityInterest: float,
	 *     totalLiabilitiesAndTotalEquity: float,
	 *     totalInvestments: float,
	 *     totalDebt: float,
	 *     netDebt: float
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
			'cashAndCashEquivalents' => $this->cashAndCashEquivalents,
			'shortTermInvestments' => $this->shortTermInvestments,
			'cashAndShortTermInvestments' => $this->cashAndShortTermInvestments,
			'netReceivables' => $this->netReceivables,
			'accountsReceivables' => $this->accountsReceivables,
			'otherReceivables' => $this->otherReceivables,
			'inventory' => $this->inventory,
			'prepaids' => $this->prepaids,
			'otherCurrentAssets' => $this->otherCurrentAssets,
			'totalCurrentAssets' => $this->totalCurrentAssets,
			'propertyPlantEquipmentNet' => $this->propertyPlantEquipmentNet,
			'goodwill' => $this->goodwill,
			'intangibleAssets' => $this->intangibleAssets,
			'goodwillAndIntangibleAssets' => $this->goodwillAndIntangibleAssets,
			'longTermInvestments' => $this->longTermInvestments,
			'taxAssets' => $this->taxAssets,
			'otherNonCurrentAssets' => $this->otherNonCurrentAssets,
			'totalNonCurrentAssets' => $this->totalNonCurrentAssets,
			'otherAssets' => $this->otherAssets,
			'totalAssets' => $this->totalAssets,
			'totalPayables' => $this->totalPayables,
			'accountPayables' => $this->accountPayables,
			'otherPayables' => $this->otherPayables,
			'accruedExpenses' => $this->accruedExpenses,
			'shortTermDebt' => $this->shortTermDebt,
			'capitalLeaseObligationsCurrent' => $this->capitalLeaseObligationsCurrent,
			'taxPayables' => $this->taxPayables,
			'deferredRevenue' => $this->deferredRevenue,
			'otherCurrentLiabilities' => $this->otherCurrentLiabilities,
			'totalCurrentLiabilities' => $this->totalCurrentLiabilities,
			'longTermDebt' => $this->longTermDebt,
			'capitalLeaseObligationsNonCurrent' => $this->capitalLeaseObligationsNonCurrent,
			'deferredRevenueNonCurrent' => $this->deferredRevenueNonCurrent,
			'deferredTaxLiabilitiesNonCurrent' => $this->deferredTaxLiabilitiesNonCurrent,
			'otherNonCurrentLiabilities' => $this->otherNonCurrentLiabilities,
			'totalNonCurrentLiabilities' => $this->totalNonCurrentLiabilities,
			'otherLiabilities' => $this->otherLiabilities,
			'capitalLeaseObligations' => $this->capitalLeaseObligations,
			'totalLiabilities' => $this->totalLiabilities,
			'treasuryStock' => $this->treasuryStock,
			'preferredStock' => $this->preferredStock,
			'commonStock' => $this->commonStock,
			'retainedEarnings' => $this->retainedEarnings,
			'additionalPaidInCapital' => $this->additionalPaidInCapital,
			'accumulatedOtherComprehensiveIncomeLoss' => $this->accumulatedOtherComprehensiveIncomeLoss,
			'otherTotalStockholdersEquity' => $this->otherTotalStockholdersEquity,
			'totalStockholdersEquity' => $this->totalStockholdersEquity,
			'totalEquity' => $this->totalEquity,
			'minorityInterest' => $this->minorityInterest,
			'totalLiabilitiesAndTotalEquity' => $this->totalLiabilitiesAndTotalEquity,
			'totalInvestments' => $this->totalInvestments,
			'totalDebt' => $this->totalDebt,
			'netDebt' => $this->netDebt,
		];
	}

	/**
	 * @internal Internal method for object mapper
	 */
	public static function createContext(TypeContext $context): TypeContext
	{
		return $context->withConversionStrategy(new NullAsZeroConversion(
			$context->conversionStrategy,
			$context->getOption(SourceFormat::class)?->is('csv') === true,
		));
	}

}
