<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\CashFlowStatementGrowth;
use Shredio\FmpClient\Payload\CashFlowStatementGrowthBulk;
use Tests\TestCase;

final class CashFlowStatementGrowthTest extends TestCase
{

	public function testCashFlowStatementGrowth(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/cash-flow-statement-growth-aapl.json');

		$statements = iterator_to_array($client->cashFlowStatementGrowth('AAPL'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new CashFlowStatementGrowth(
			date: '2024-09-30',
			symbol: 'AAPL',
			calendarYear: '2024',
			period: Period::FY,
			growthNetIncome: -0.033599670086086914,
			growthDepreciationAndAmortization: -0.006424168764649709,
			growthDeferredIncomeTax: 0.0,
			growthStockBasedCompensation: 0.07892550540016616,
			growthChangeInWorkingCapital: 1.555116314429071,
			growthAccountsReceivables: -11.335731414868105,
			growthInventory: 0.3535228677379481,
			growthAccountsPayables: 4.1868713605082055,
			growthOtherWorkingCapital: 2.4402563136072373,
			growthOtherNonCashItems: -0.017512348450830714,
			growthNetCashProvidedByOperatingActivites: 0.06975566069312394,
			growthInvestmentsInPropertyPlantAndEquipment: 0.13796879277306323,
			growthAcquisitionsNet: 0.0,
			growthPurchasesOfInvestments: -0.6486294175448107,
			growthSalesMaturitiesOfInvestments: 0.3698202750801951,
			growthOtherInvestingActivites: 0.02169035153328347,
			growthNetCashUsedForInvestingActivites: -0.2078272604588394,
			growthDebtRepayment: -0.012662502110417018,
			growthCommonStockIssued: 0.0,
			growthCommonStockRepurchased: -0.2243584784010316,
			growthDividendsPaid: -0.013910149750415973,
			growthOtherFinancingActivites: 0.03493013972055888,
			growthNetCashUsedProvidedByFinancingActivities: -0.12439163778482412,
			growthEffectOfForexChangesOnCash: 0.0,
			growthNetChangeInCash: -1.1378472222222222,
			growthCashAtEndOfPeriod: -0.02583205908188828,
			growthCashAtBeginningOfPeriod: 0.23061216319013492,
			growthOperatingCashFlow: 0.06975566069312394,
			growthCapitalExpenditure: 0.13796879277306323,
			growthFreeCashFlow: 0.092615279562982,
		))->toArray(), $statements[0]->toArray());
	}

	public function testCashFlowStatementGrowthBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/cash-flow-statement-growth-bulk.csv');

		$statements = iterator_to_array($client->cashFlowStatementGrowthBulk(2024, Period::FY));

		$this->assertNotEmpty($statements);
		$this->assertSame((new CashFlowStatementGrowthBulk(
			symbol: '000001.SZ',
			date: '2024-12-31',
			calendarYear: '2024',
			period: Period::FY,
			growthNetIncome: -0.0844257883973738,
			growthDepreciationAndAmortization: -0.2096528365791702,
			growthDeferredIncomeTax: 0.0,
			growthStockBasedCompensation: 0.0,
			growthChangeInWorkingCapital: -1.8135950134770888,
			growthAccountsReceivables: 0.0,
			growthInventory: 0.0,
			growthAccountsPayables: 0.0,
			growthOtherWorkingCapital: 15.284198113207546,
			growthOtherNonCashItems: -0.08622338729675157,
			growthNetCashProvidedByOperatingActivites: -0.31499767469527695,
			growthInvestmentsInPropertyPlantAndEquipment: 0.22868217054263565,
			growthAcquisitionsNet: 0.0,
			growthPurchasesOfInvestments: -0.4583663057845359,
			growthSalesMaturitiesOfInvestments: 0.4677030505827389,
			growthOtherInvestingActivites: 0.5753424657534246,
			growthNetCashUsedForInvestingActivites: -0.21869023028077422,
			growthDebtRepayment: 0.1434209903752942,
			growthCommonStockIssued: 0.0,
			growthCommonStockRepurchased: 0.0,
			growthDividendsPaid: -1.5747016706443915,
			growthOtherFinancingActivites: 0.021323938438716855,
			growthNetCashUsedProvidedByFinancingActivities: -10.139943132649277,
			growthEffectOfForexChangesOnCash: -0.20336700336700336,
			growthNetChangeInCash: -1.5438314469055117,
			growthCashAtEndOfPeriod: -0.13839829118869018,
			growthCashAtBeginningOfPeriod: 0.3413590853071616,
			growthOperatingCashFlow: -0.31499767469527695,
			growthCapitalExpenditure: 0.22868217054263565,
			growthFreeCashFlow: -0.3179880266323505,
		))->toArray(), $statements[0]->toArray());
	}

}
