<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\IncomeStatementGrowth;
use Shredio\FmpClient\Payload\IncomeStatementGrowthBulk;
use Tests\TestCase;

final class IncomeStatementGrowthTest extends TestCase
{

	public function testIncomeStatementGrowth(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-growth-aapl.json');

		$statements = iterator_to_array($client->incomeStatementGrowth('AAPL'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new IncomeStatementGrowth(
			date: '2024-09-30',
			symbol: 'AAPL',
			calendarYear: '2024',
			period: Period::FY,
			growthRevenue: 0.020219940775141214,
			growthCostOfRevenue: -0.017675600199872046,
			growthGrossProfit: 0.06819471705252206,
			growthGrossProfitRatio: 0.04702395474011335,
			growthResearchAndDevelopmentExpenses: 0.04863780712017383,
			growthGeneralAndAdministrativeExpenses: 0.0,
			growthSellingAndMarketingExpenses: 0.0,
			growthOtherExpenses: 0.0,
			growthOperatingExpenses: 0.04776924900176856,
			growthCostAndExpenses: -0.004331112631234571,
			growthInterestExpense: -1.0,
			growthDepreciationAndAmortization: -0.006424168764649709,
			growthEBITDA: 0.07026704816404387,
			growthEBITDARatio: 0.049055213868137715,
			growthOperatingIncome: 0.07799581805933456,
			growthOperatingIncomeRatio: 0.05663080556714364,
			growthTotalOtherIncomeExpensesNet: 1.4761061946902654,
			growthIncomeBeforeTax: 0.08571604417246959,
			growthIncomeBeforeTaxRatio: 0.06419802344984436,
			growthIncomeTaxExpense: 0.7770145152619318,
			growthNetIncome: -0.033599670086086914,
			growthNetIncomeRatio: -0.052752949185731576,
			growthEPS: -0.008116883116883088,
			growthEPSDiluted: -0.008156606851549727,
			growthWeightedAverageShsOut: -0.02543458616683152,
			growthWeightedAverageShsOutDil: -0.02557791606880283,
		))->toArray(), $statements[0]->toArray());
	}

	public function testIncomeStatementGrowthBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-growth-bulk.csv');

		$statements = iterator_to_array($client->incomeStatementGrowthBulk(2024, Period::FY));

		$this->assertNotEmpty($statements);
		$this->assertSame((new IncomeStatementGrowthBulk(
			symbol: '000001.SZ',
			date: '2024-12-31',
			calendarYear: '2024',
			period: Period::FY,
			growthRevenue: -0.07499858064760319,
			growthCostOfRevenue: -1.0,
			growthGrossProfit: -0.4059372246361399,
			growthGrossProfitRatio: -0.35777095805875836,
			growthResearchAndDevelopmentExpenses: 0.0,
			growthGeneralAndAdministrativeExpenses: -0.1517311140742103,
			growthSellingAndMarketingExpenses: 0.0,
			growthOtherExpenses: -0.02470053696819496,
			growthOperatingExpenses: -0.0848147261689767,
			growthCostAndExpenses: -0.11699558301964795,
			growthInterestIncome: -0.12844383328134543,
			growthInterestExpense: -0.04261762720522504,
			growthDepreciationAndAmortization: -0.09851265211512458,
			growthEBITDA: -1.0,
			growthOperatingIncome: 1.031020693403735,
			growthIncomeBeforeTax: -0.051630340621643164,
			growthIncomeTaxExpense: -0.09171623901269645,
			growthNetIncome: 0.020685226803650873,
			growthEPS: -0.04444444444444448,
			growthEPSDiluted: -0.04444444444444448,
			growthWeightedAverageShsOut: 0.0026506941774185637,
			growthWeightedAverageShsOutDil: 0.0667522931052252,
		))->toArray(), $statements[0]->toArray());
	}

}
