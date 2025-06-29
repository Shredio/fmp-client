<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
use Shredio\FmpClient\Payload\Ratios;
use Shredio\FmpClient\Payload\RatiosTtm;
use Tests\TestCase;

final class RatiosTest extends TestCase
{

	public function testRatios(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/ratios.json');

		$ratios = iterator_to_array($client->ratios('AAPL'));

		$this->assertNotEmpty($ratios);
		$this->assertSame((new Ratios(
			symbol: 'AAPL',
			date: '2024-09-28',
			fiscalYear: '2024',
			period: Period::FY,
			reportedCurrency: 'USD',
			grossProfitMargin: 0.4620634981523393,
			ebitMargin: 0.31510222870075566,
			ebitdaMargin: 0.3443707085043538,
			operatingProfitMargin: 0.31510222870075566,
			pretaxProfitMargin: 0.3157901466620635,
			continuousOperationsProfitMargin: 0.23971255769943867,
			netProfitMargin: 0.23971255769943867,
			bottomLineProfitMargin: 0.23971255769943867,
			receivablesTurnover: 5.903038811648023,
			payablesTurnover: 3.0503480278422272,
			inventoryTurnover: 28.870710952511665,
			fixedAssetTurnover: 8.560310858143607,
			assetTurnover: 1.0713874732862074,
			currentRatio: 0.8673125765340832,
			quickRatio: 0.8260068483831466,
			solvencyRatio: 0.3414634938155374,
			cashRatio: 0.16975259648963673,
			priceToEarningsRatio: 37.287278415656736,
			priceToEarningsGrowthRatio: -45.93792700808932,
			forwardPriceToEarningsGrowthRatio: -45.93792700808932,
			priceToBookRatio: 61.37243774486391,
			priceToSalesRatio: 8.93822887866815,
			priceToFreeCashFlowRatio: 32.12256867269569,
			priceToOperatingCashFlowRatio: 29.55638142954995,
			debtToAssetsRatio: 0.32620691544742175,
			debtToEquityRatio: 2.090588235294118,
			debtToCapitalRatio: 0.6764370003806623,
			longTermDebtToCapitalRatio: 0.6009110021023125,
			financialLeverageRatio: 6.408779631255487,
			workingCapitalTurnoverRatio: -31.099932397502684,
			operatingCashFlowRatio: 0.6704045534944896,
			operatingCashFlowSalesRatio: 0.3024128274962599,
			freeCashFlowOperatingCashFlowRatio: 0.9201126388959359,
			debtServiceCoverageRatio: 5.024761722304708,
			interestCoverageRatio: 0.0,
			shortTermOperatingCashFlowCoverageRatio: 5.663777000814215,
			operatingCashFlowCoverageRatio: 0.9932386463854056,
			capitalExpenditureCoverageRatio: 12.517624642743728,
			dividendPaidAndCapexCoverageRatio: 4.7912969490701345,
			dividendPayoutRatio: 0.16252026969360758,
			dividendYield: 0.0043585983369965175,
			dividendYieldPercentage: 0.43585983369965176,
			revenuePerShare: 25.484914639368924,
			netIncomePerShare: 6.109054070954992,
			interestDebtPerShare: 7.759429340208995,
			cashPerShare: 4.247388013764271,
			bookValuePerShare: 3.711600978715614,
			tangibleBookValuePerShare: 3.711600978715614,
			shareholdersEquityPerShare: 3.711600978715614,
			operatingCashFlowPerShare: 7.706965094592383,
			capexPerShare: 0.6156891035281195,
			freeCashFlowPerShare: 7.091275991064264,
			netIncomePerEBT: 0.7590881483581001,
			ebtPerEbit: 1.0021831580314244,
			priceToFairValue: 61.37243774486391,
			debtToMarketCap: 0.03050761336980449,
			effectiveTaxRate: 0.24091185164189982,
			enterpriseValueMultiple: 26.617033362072167,
			dividendPerShare: 0.9928451151844366,
		))->toArray(), $ratios[0]->toArray());
	}

	public function testRatiosWithParameters(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/ratios.json');

		$ratios = iterator_to_array($client->ratios('AAPL', 5, PeriodQuery::Annual));

		$this->assertNotEmpty($ratios);
		$this->assertInstanceOf(Ratios::class, $ratios[0]);
	}

	public function testRatiosTtm(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/ratios-ttm.json');

		$ratios = iterator_to_array($client->ratiosTtm('AAPL'));

		$this->assertNotEmpty($ratios);
		$this->assertSame((new RatiosTtm(
			symbol: 'AAPL',
			grossProfitMargin: 0.46632081645294554,
			ebitMargin: 0.31811892118711377,
			ebitdaMargin: 0.3468476344145107,
			operatingProfitMargin: 0.31811892118711377,
			pretaxProfitMargin: 0.3172047576467532,
			continuousOperationsProfitMargin: 0.2430126434312604,
			netProfitMargin: 0.2430126434312604,
			bottomLineProfitMargin: 0.2430126434312604,
			receivablesTurnover: 8.03980079521266,
			payablesTurnover: 3.9475852640135978,
			inventoryTurnover: 34.08310735364492,
			fixedAssetTurnover: 8.540959126205308,
			assetTurnover: 1.2087141075919368,
			currentRatio: 0.8208700223419635,
			quickRatio: 0.7775072455748386,
			solvencyRatio: 0.4114250275112787,
			cashRatio: 0.19479702014926922,
			priceToEarningsRatio: 30.988653036775137,
			priceToEarningsGrowthRatio: 16.294866721837575,
			forwardPriceToEarningsGrowthRatio: 2.8794647599774783,
			priceToBookRatio: 45.1375832169591,
			priceToSalesRatio: 7.501362912934664,
			priceToFreeCashFlowRatio: 30.494594805353046,
			priceToOperatingCashFlowRatio: 27.52026368761182,
			debtToAssetsRatio: 0.2964257788324231,
			debtToEquityRatio: 1.4699383196598599,
			debtToCapitalRatio: 0.5951315901128608,
			longTermDebtToCapitalRatio: 0.5404851336662951,
			financialLeverageRatio: 4.958874782921133,
			workingCapitalTurnoverRatio: -21.628545189346873,
			operatingCashFlowRatio: 0.7578006654169923,
			operatingCashFlowSalesRatio: 0.27363961974793066,
			freeCashFlowOperatingCashFlowRatio: 0.8989557851692285,
			debtServiceCoverageRatio: 5.563812436289501,
			interestCoverageRatio: 0.0,
			shortTermOperatingCashFlowCoverageRatio: 5.5838939857288485,
			operatingCashFlowCoverageRatio: 1.115800623306785,
			capitalExpenditureCoverageRatio: 9.896657633242999,
			dividendPaidAndCapexCoverageRatio: 4.15252245764318,
			dividendPayoutRatio: 0.1573889448475754,
			dividendYield: 0.00502288,
			enterpriseValue: 3073314664000,
			revenuePerShare: 26.70160133844806,
			netIncomePerShare: 6.488826725103944,
			interestDebtPerShare: 6.548316862612863,
			cashPerShare: 3.234476108640729,
			bookValuePerShare: 4.4548242433248,
			tangibleBookValuePerShare: 4.4548242433248,
			shareholdersEquityPerShare: 4.4548242433248,
			operatingCashFlowPerShare: 7.306616036913764,
			capexPerShare: 0.7382912805198745,
			freeCashFlowPerShare: 6.568324756393889,
			netIncomePerEBT: 0.7661065528591001,
			ebtPerEbit: 0.9971263465343425,
			priceToFairValue: 45.1375832169591,
			debtToMarketCap: 0.032692806319728235,
			effectiveTaxRate: 0.23389344714089985,
			enterpriseValueMultiple: 22.13151285411836,
		))->toArray(), $ratios[0]->toArray());
	}

	public function testRatiosTtmBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/ratios-ttm-bulk.csv');

		$ratios = iterator_to_array($client->ratiosTtmBulk());

		$this->assertNotEmpty($ratios);
		$this->assertSame((new RatiosTtm(
			symbol: '000001.SZ',
			grossProfitMargin: 1.1622776732779352,
			ebitMargin: 0.22525536322293388,
			ebitdaMargin: 0.2018381390033096,
			operatingProfitMargin: 0.4658682349579752,
			pretaxProfitMargin: 0.3160551441700993,
			continuousOperationsProfitMargin: 0.25995857044215337,
			netProfitMargin: 0.25995857044215337,
			bottomLineProfitMargin: 0.25995857044215337,
			receivablesTurnover: 0.0,
			payablesTurnover: 0.0,
			inventoryTurnover: 0.0,
			fixedAssetTurnover: 13.114441842310695,
			assetTurnover: 0.029075827062555015,
			currentRatio: 0.0,
			quickRatio: 0.0,
			solvencyRatio: 0.008534174446189174,
			cashRatio: 0.0,
			priceToEarningsRatio: 6.351275490616413,
			priceToEarningsGrowthRatio: -3.42968876493286,
			forwardPriceToEarningsGrowthRatio: 2.428238141051176,
			priceToBookRatio: 0.5480464862050297,
			priceToSalesRatio: 1.4092715302745304,
			priceToFreeCashFlowRatio: 1.44271233744866,
			priceToOperatingCashFlowRatio: 1.6650333052771222,
			debtToAssetsRatio: 0.0,
			debtToEquityRatio: 0.0,
			debtToCapitalRatio: 0.0,
			longTermDebtToCapitalRatio: 0.0,
			financialLeverageRatio: 11.416164801466868,
			workingCapitalTurnoverRatio: 0.23544250931631752,
			operatingCashFlowRatio: 0.0,
			operatingCashFlowSalesRatio: 0.991612895545132,
			freeCashFlowOperatingCashFlowRatio: 0.9850828696116743,
			debtServiceCoverageRatio: 0.24758322210087771,
			interestCoverageRatio: 0.7914088096104842,
			shortTermOperatingCashFlowCoverageRatio: 0.0,
			operatingCashFlowCoverageRatio: 0.0,
			capitalExpenditureCoverageRatio: 67.03702213279678,
			dividendPaidAndCapexCoverageRatio: 6.192364879934577,
			dividendPayoutRatio: 0.5590996519509067,
			dividendYield: 0.23343,
			enterpriseValue: -509379020000,
			revenuePerShare: 7.389154370023568,
			netIncomePerShare: 1.9208740068077172,
			interestDebtPerShare: 4.349676503966586,
			cashPerShare: 32.81790720767194,
			bookValuePerShare: 22.260885357516656,
			tangibleBookValuePerShare: 21.662613507347245,
			shareholdersEquityPerShare: 22.260885357516656,
			operatingCashFlowPerShare: 7.327180760489036,
			capexPerShare: 0.10930051078304583,
			freeCashFlowPerShare: 7.21788024970599,
			netIncomePerEBT: 0.8225101702576465,
			ebtPerEbit: 0.6784217520188082,
			priceToFairValue: 0.5480464862050297,
			debtToMarketCap: 0.0,
			effectiveTaxRate: 0.17748982974235347,
			enterpriseValueMultiple: -15.022384687979237,
		))->toArray(), $ratios[0]->toArray());
	}

}