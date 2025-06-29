<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class KeyMetricsTtm
{

	public function __construct(
		public string $symbol,
		public int|null $marketCap = null,
		public int|null $enterpriseValue = null,
		public float|null $evToSales = null,
		public float|null $evToOperatingCashFlow = null,
		public float|null $evToFreeCashFlow = null,
		public float|null $evToEBITDA = null,
		public float|null $netDebtToEBITDA = null,
		public float|null $currentRatio = null,
		public float|null $incomeQuality = null,
		public float|null $grahamNumber = null,
		public float|null $grahamNetNet = null,
		public float|null $taxBurden = null,
		public float|null $interestBurden = null,
		public int|null $workingCapital = null,
		public int|null $investedCapital = null,
		public float|null $returnOnAssets = null,
		public float|null $operatingReturnOnAssets = null,
		public float|null $returnOnTangibleAssets = null,
		public float|null $returnOnEquity = null,
		public float|null $returnOnInvestedCapital = null,
		public float|null $returnOnCapitalEmployed = null,
		public float|null $earningsYield = null,
		public float|null $freeCashFlowYield = null,
		public float|null $capexToOperatingCashFlow = null,
		public float|null $capexToDepreciation = null,
		public float|null $capexToRevenue = null,
		public float|null $salesGeneralAndAdministrativeToRevenue = null,
		public float|null $researchAndDevelopementToRevenue = null,
		public float|null $stockBasedCompensationToRevenue = null,
		public float|null $intangiblesToTotalAssets = null,
		public int|null $averageReceivables = null,
		public int|null $averagePayables = null,
		public int|null $averageInventory = null,
		public float|null $daysOfSalesOutstanding = null,
		public float|null $daysOfPayablesOutstanding = null,
		public float|null $daysOfInventoryOutstanding = null,
		public float|null $operatingCycle = null,
		public float|null $cashConversionCycle = null,
		public int|null $freeCashFlowToEquity = null,
		public float|null $freeCashFlowToFirm = null,
		public int|null $tangibleAssetValue = null,
		public int|null $netCurrentAssetValue = null,
	)
	{
	}

	/**
	 * @return array{symbol: string, marketCap: int|null, enterpriseValue: int|null, evToSales: float|null, evToOperatingCashFlow: float|null, evToFreeCashFlow: float|null, evToEBITDA: float|null, netDebtToEBITDA: float|null, currentRatio: float|null, incomeQuality: float|null, grahamNumber: float|null, grahamNetNet: float|null, taxBurden: float|null, interestBurden: float|null, workingCapital: int|null, investedCapital: int|null, returnOnAssets: float|null, operatingReturnOnAssets: float|null, returnOnTangibleAssets: float|null, returnOnEquity: float|null, returnOnInvestedCapital: float|null, returnOnCapitalEmployed: float|null, earningsYield: float|null, freeCashFlowYield: float|null, capexToOperatingCashFlow: float|null, capexToDepreciation: float|null, capexToRevenue: float|null, salesGeneralAndAdministrativeToRevenue: float|null, researchAndDevelopementToRevenue: float|null, stockBasedCompensationToRevenue: float|null, intangiblesToTotalAssets: float|null, averageReceivables: int|null, averagePayables: int|null, averageInventory: int|null, daysOfSalesOutstanding: float|null, daysOfPayablesOutstanding: float|null, daysOfInventoryOutstanding: float|null, operatingCycle: float|null, cashConversionCycle: float|null, freeCashFlowToEquity: int|null, freeCashFlowToFirm: float|null, tangibleAssetValue: int|null, netCurrentAssetValue: int|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'marketCap' => $this->marketCap,
			'enterpriseValue' => $this->enterpriseValue,
			'evToSales' => $this->evToSales,
			'evToOperatingCashFlow' => $this->evToOperatingCashFlow,
			'evToFreeCashFlow' => $this->evToFreeCashFlow,
			'evToEBITDA' => $this->evToEBITDA,
			'netDebtToEBITDA' => $this->netDebtToEBITDA,
			'currentRatio' => $this->currentRatio,
			'incomeQuality' => $this->incomeQuality,
			'grahamNumber' => $this->grahamNumber,
			'grahamNetNet' => $this->grahamNetNet,
			'taxBurden' => $this->taxBurden,
			'interestBurden' => $this->interestBurden,
			'workingCapital' => $this->workingCapital,
			'investedCapital' => $this->investedCapital,
			'returnOnAssets' => $this->returnOnAssets,
			'operatingReturnOnAssets' => $this->operatingReturnOnAssets,
			'returnOnTangibleAssets' => $this->returnOnTangibleAssets,
			'returnOnEquity' => $this->returnOnEquity,
			'returnOnInvestedCapital' => $this->returnOnInvestedCapital,
			'returnOnCapitalEmployed' => $this->returnOnCapitalEmployed,
			'earningsYield' => $this->earningsYield,
			'freeCashFlowYield' => $this->freeCashFlowYield,
			'capexToOperatingCashFlow' => $this->capexToOperatingCashFlow,
			'capexToDepreciation' => $this->capexToDepreciation,
			'capexToRevenue' => $this->capexToRevenue,
			'salesGeneralAndAdministrativeToRevenue' => $this->salesGeneralAndAdministrativeToRevenue,
			'researchAndDevelopementToRevenue' => $this->researchAndDevelopementToRevenue,
			'stockBasedCompensationToRevenue' => $this->stockBasedCompensationToRevenue,
			'intangiblesToTotalAssets' => $this->intangiblesToTotalAssets,
			'averageReceivables' => $this->averageReceivables,
			'averagePayables' => $this->averagePayables,
			'averageInventory' => $this->averageInventory,
			'daysOfSalesOutstanding' => $this->daysOfSalesOutstanding,
			'daysOfPayablesOutstanding' => $this->daysOfPayablesOutstanding,
			'daysOfInventoryOutstanding' => $this->daysOfInventoryOutstanding,
			'operatingCycle' => $this->operatingCycle,
			'cashConversionCycle' => $this->cashConversionCycle,
			'freeCashFlowToEquity' => $this->freeCashFlowToEquity,
			'freeCashFlowToFirm' => $this->freeCashFlowToFirm,
			'tangibleAssetValue' => $this->tangibleAssetValue,
			'netCurrentAssetValue' => $this->netCurrentAssetValue,
		];
	}

}