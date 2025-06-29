<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
use Shredio\FmpClient\Payload\KeyMetrics;
use Shredio\FmpClient\Payload\KeyMetricsTtm;
use Tests\TestCase;

final class KeyMetricsTest extends TestCase
{

	public function testKeyMetrics(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/key-metrics.json');

		$metrics = iterator_to_array($client->keyMetrics('AAPL'));

		$this->assertNotEmpty($metrics);
		$this->assertSame((new KeyMetrics(
			symbol: 'AAPL',
			date: '2024-09-28',
			fiscalYear: '2024',
			period: Period::FY,
			reportedCurrency: 'USD',
			marketCap: 3495160329570,
			enterpriseValue: 3584276329570,
			evToSales: 9.166126637180815,
			evToOperatingCashFlow: 30.30997961650346,
			evToFreeCashFlow: 32.94159686022039,
			evToEBITDA: 26.617033362072167,
			netDebtToEBITDA: 0.6617803224393106,
			currentRatio: 0.8673125765340832,
			incomeQuality: 1.2615643936161134,
			grahamNumber: 22.587017267616833,
			grahamNetNet: -12.352478525015636,
			taxBurden: 0.7590881483581001,
			interestBurden: 1.0021831580314244,
			workingCapital: -23405000000,
			investedCapital: 22275000000,
			returnOnAssets: 0.25682503150857583,
			operatingReturnOnAssets: 0.3434290787011036,
			returnOnTangibleAssets: 0.25682503150857583,
			returnOnEquity: 1.6459350307287095,
			returnOnInvestedCapital: 0.4430708117427921,
			returnOnCapitalEmployed: 0.6533607652660827,
			earningsYield: 0.026818798327209237,
			freeCashFlowYield: 0.03113076074921754,
			capexToOperatingCashFlow: 0.07988736110406414,
			capexToDepreciation: 0.8254259501965924,
			capexToRevenue: 0.02415896275269477,
			salesGeneralAndAdministrativeToRevenue: 0.0,
			researchAndDevelopementToRevenue: 0.08022299794136074,
			stockBasedCompensationToRevenue: 0.02988990755303234,
			intangiblesToTotalAssets: 0.0,
			averageReceivables: 63614000000,
			averagePayables: 65785500000,
			averageInventory: 6808500000,
			daysOfSalesOutstanding: 61.83255974529134,
			daysOfPayablesOutstanding: 119.65847721913745,
			daysOfInventoryOutstanding: 12.642570548414087,
			operatingCycle: 74.47513029370543,
			cashConversionCycle: -45.18334692543202,
			freeCashFlowToEquity: 19691000000,
			freeCashFlowToFirm: 117192805288.09166,
			tangibleAssetValue: 56950000000,
			netCurrentAssetValue: -155043000000,
		))->toArray(), $metrics[0]->toArray());
	}

	public function testKeyMetricsWithParameters(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/key-metrics.json');

		$metrics = iterator_to_array($client->keyMetrics('AAPL', 5, PeriodQuery::Annual));

		$this->assertNotEmpty($metrics);
		$this->assertInstanceOf(KeyMetrics::class, $metrics[0]);
	}

	public function testKeyMetricsTtm(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/key-metrics-ttm.json');

		$metrics = iterator_to_array($client->keyMetricsTtm('AAPL'));

		$this->assertNotEmpty($metrics);
		$this->assertSame((new KeyMetricsTtm(
			symbol: 'AAPL',
			marketCap: 3003290664000,
			enterpriseValue: 3073314664000,
			evToSales: 7.676262879465289,
			evToOperatingCashFlow: 28.052454123918363,
			evToFreeCashFlow: 31.2055994151453,
			evToEBITDA: 22.13151285411836,
			netDebtToEBITDA: 0.5042559013725462,
			currentRatio: 0.8208700223419635,
			incomeQuality: 1.126030382140728,
			grahamNumber: 25.50290392541319,
			grahamNetNet: -11.701683370812564,
			taxBurden: 0.7661065528591001,
			interestBurden: 0.9971263465343425,
			workingCapital: -25897000000,
			investedCapital: 20979000000,
			returnOnAssets: 0.29373281043857347,
			operatingReturnOnAssets: 0.37719711306377146,
			returnOnTangibleAssets: 0.29373281043857347,
			returnOnEquity: 1.5130553784426855,
			returnOnInvestedCapital: 0.4730145868197246,
			returnOnCapitalEmployed: 0.682324200962167,
			earningsYield: 0.03226987629353463,
			freeCashFlowYield: 0.03279269675111273,
			capexToOperatingCashFlow: 0.10104421483077147,
			capexToDepreciation: 0.9624413145539906,
			capexToRevenue: 0.02764970052402052,
			salesGeneralAndAdministrativeToRevenue: 0.0,
			researchAndDevelopementToRevenue: 0.08139802081095797,
			stockBasedCompensationToRevenue: 0.030569528881073817,
			intangiblesToTotalAssets: 0.0,
			averageReceivables: 54552000000,
			averagePayables: 58018000000,
			averageInventory: 6590000000,
			daysOfSalesOutstanding: 45.399134791665624,
			daysOfPayablesOutstanding: 92.46158742342055,
			daysOfInventoryOutstanding: 10.709117458475104,
			operatingCycle: 56.10825225014073,
			cashConversionCycle: -36.353335173279824,
			freeCashFlowToEquity: 28462000000,
			freeCashFlowToFirm: 112778394998.34642,
			tangibleAssetValue: 66796000000,
			netCurrentAssetValue: -145763000000,
		))->toArray(), $metrics[0]->toArray());
	}

	public function testKeyMetricsTtmBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/key-metrics-ttm-full.csv');

		$metrics = iterator_to_array($client->keyMetricsTtmBulk());

		$this->assertNotEmpty($metrics);
		$this->assertSame((new KeyMetricsTtm(
			symbol: '000001.SZ',
			marketCap: 236751980000,
			enterpriseValue: -509379020000,
			evToSales: -3.0320901688135433,
			evToOperatingCashFlow: -3.0577357176730477,
			evToFreeCashFlow: -3.104039073259314,
			evToEBITDA: -15.022384687979237,
			netDebtToEBITDA: -22.004571192638906,
			currentRatio: 0.0,
			incomeQuality: 15.217593861331872,
			grahamNumber: 31.017865999534138,
			grahamNetNet: -199.05514330278228,
			taxBurden: 0.8225101702576465,
			interestBurden: 1.4030970878917606,
			workingCapital: 746131000000,
			investedCapital: 772543000000,
			returnOnAssets: 0.007558510437605078,
			operatingReturnOnAssets: 0.013555578495362656,
			returnOnTangibleAssets: 0.007576346366296015,
			returnOnEquity: 0.09082717681735725,
			returnOnInvestedCapital: 0.011141314993384131,
			returnOnCapitalEmployed: 0.013545504233575834,
			earningsYield: 0.1574486890825998,
			freeCashFlowYield: 0.6931388704753388,
			capexToOperatingCashFlow: 0.014917130388325619,
			capexToDepreciation: 1.855862584017924,
			capexToRevenue: 0.014792018857591847,
			salesGeneralAndAdministrativeToRevenue: 0.10163337222314817,
			researchAndDevelopementToRevenue: 0.0,
			stockBasedCompensationToRevenue: 0.0,
			intangiblesToTotalAssets: 0.002354159621091415,
			averageReceivables: 0,
			averagePayables: 0,
			averageInventory: 0,
			daysOfSalesOutstanding: 0.0,
			daysOfPayablesOutstanding: 0.0,
			daysOfInventoryOutstanding: 0.0,
			operatingCycle: 0.0,
			cashConversionCycle: 0.0,
			freeCashFlowToEquity: 910233000000,
			freeCashFlowToFirm: -35237570137.11014,
			tangibleAssetValue: 492510000000,
			netCurrentAssetValue: -4525615000000,
		))->toArray(), $metrics[0]->toArray());
	}

}
