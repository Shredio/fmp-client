<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\BalanceSheetStatementGrowth;
use Shredio\FmpClient\Payload\BalanceSheetStatementGrowthBulk;
use Tests\TestCase;

final class BalanceSheetStatementGrowthTest extends TestCase
{

	public function testBalanceSheetStatementGrowth(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/balance-sheet-statement-growth-aapl.json');

		$statements = iterator_to_array($client->balanceSheetStatementGrowth('AAPL'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new BalanceSheetStatementGrowth(
			date: '2024-09-30',
			symbol: 'AAPL',
			calendarYear: '2024',
			period: Period::FY,
			growthCashAndCashEquivalents: -0.0007341898882029034,
			growthShortTermInvestments: 0.11516302627413738,
			growthCashAndShortTermInvestments: 0.058744212492892536,
			growthNetReceivables: 0.08621792243994425,
			growthInventory: 0.15084504817564365,
			growthOtherCurrentAssets: -0.02776454576386526,
			growthTotalCurrentAssets: 0.06562138667929733,
			growthPropertyPlantEquipmentNet: -0.15992349565984992,
			growthGoodwill: 0.0,
			growthIntangibleAssets: 0.0,
			growthGoodwillAndIntangibleAssets: 0.0,
			growthLongTermInvestments: -0.09015953214513049,
			growthTaxAssets: 0.09225857046829487,
			growthOtherNonCurrentAssets: 0.5266933370120016,
			growthTotalNonCurrentAssets: 0.014238076328719674,
			growthOtherAssets: 0.0,
			growthTotalAssets: 0.035160515396374756,
			growthAccountPayables: 0.1014039066617687,
			growthShortTermDebt: 0.32087050041121024,
			growthTaxPayables: 2.01632838190271,
			growthDeferredRevenue: 0.023322168465450935,
			growthOtherCurrentLiabilities: 0.03377722721172706,
			growthTotalCurrentLiabilities: 0.21391802240757563,
			growthLongTermDebt: -0.10003043628845205,
			growthDeferredRevenueNonCurrent: 0.0,
			growthDeferrredTaxLiabilitiesNonCurrent: 0.0,
			growthOtherNonCurrentLiabilities: -0.09048495373370312,
			growthTotalNonCurrentLiabilities: -0.09295867814151548,
			growthOtherLiabilities: 0.0,
			growthTotalLiabilities: 0.060574238130816666,
			growthCommonStock: 0.12821763398905328,
			growthRetainedEarnings: -88.50467289719626,
			growthAccumulatedOtherComprehensiveIncomeLoss: 0.3737338456164862,
			growthOthertotalStockholdersEquity: 0.0,
			growthTotalStockholdersEquity: -0.0836095645737457,
			growthTotalLiabilitiesAndStockholdersEquity: 0.035160515396374756,
			growthTotalInvestments: -0.04107194211936368,
			growthTotalDebt: -0.039304446058258696,
			growthNetDebt: -0.051604320757728944,
		))->toArray(), $statements[0]->toArray());
	}

	public function testBalanceSheetStatementGrowthBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/balance-sheet-statement-growth-bulk.csv');

		$statements = iterator_to_array($client->balanceSheetStatementGrowthBulk(2024, Period::FY));

		$this->assertNotEmpty($statements);
		$this->assertSame((new BalanceSheetStatementGrowthBulk(
			symbol: '000001.SZ',
			date: '2024-12-31',
			calendarYear: '2024',
			period: Period::FY,
			growthCashAndCashEquivalents: -0.731867026812128,
			growthShortTermInvestments: 0.0,
			growthCashAndShortTermInvestments: 0.13745663137040692,
			growthNetReceivables: 0.0,
			growthInventory: 0.0,
			growthOtherCurrentAssets: 0.0,
			growthTotalCurrentAssets: 0.13745663137040692,
			growthPropertyPlantEquipmentNet: -0.07025693966506079,
			growthGoodwill: 0.0,
			growthIntangibleAssets: -0.057988523104802174,
			growthGoodwillAndIntangibleAssets: -0.027061310782241013,
			growthLongTermInvestments: 0.16141945165170957,
			growthTaxAssets: 0.12270342516307925,
			growthOtherNonCurrentAssets: -0.20807454962683214,
			growthTotalNonCurrentAssets: -0.11201193539334862,
			growthOtherAssets: 1.0,
			growthTotalAssets: 0.032602509058340653,
			growthAccountPayables: 0.0,
			growthShortTermDebt: -0.06699802309305965,
			growthTaxPayables: 0.5108742004264393,
			growthDeferredRevenue: -1.0,
			growthOtherCurrentLiabilities: 26.950391812223078,
			growthTotalCurrentLiabilities: 2.150532322861338,
			growthLongTermDebt: 4.038626465436829,
			growthDeferredRevenueNonCurrent: -1.0,
			growthDeferredTaxLiabilitiesNonCurrent: -1.0,
			growthOtherNonCurrentLiabilities: 1244.4268829026937,
			growthTotalNonCurrentLiabilities: 1448.8152831225948,
			growthOtherLiabilities: -0.3186507656593404,
			growthTotalLiabilities: 0.031211459790708822,
			growthCommonStock: 0.0,
			growthRetainedEarnings: 0.10101466633522406,
			growthAccumulatedOtherComprehensiveIncomeLoss: -1.0,
			growthOthertotalStockholdersEquity: 0.0016189014379204753,
			growthTotalStockholdersEquity: 0.04766602869192595,
			growthTotalLiabilitiesAndStockholdersEquity: 0.032602509058340653,
			growthTotalInvestments: 0.16141945165170957,
			growthTotalDebt: -0.05928826380436553,
			growthNetDebt: 2.191803190051365,
		))->toArray(), $statements[0]->toArray());
	}

}
