<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\CashFlowStatement;
use Tests\TestCase;

final class CashFlowStatementTest extends TestCase
{

	public function testCashFlowStatement(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/cash-flow-statement-aapl.json');

		$statements = iterator_to_array($client->cashFlowStatement('AAPL'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new CashFlowStatement(
			symbol: 'AAPL',
			date: '2024-09-28',
			reportedCurrency: 'USD',
			cik: '0000320193',
			filingDate: '2024-11-01',
			acceptedDate: '2024-11-01 06:01:36',
			fiscalYear: '2024',
			period: Period::FY,
			netIncome: 93736000000,
			depreciationAndAmortization: 11445000000,
			deferredIncomeTax: 0,
			stockBasedCompensation: 11688000000,
			changeInWorkingCapital: 3651000000,
			accountsReceivables: -5144000000,
			inventory: -1046000000,
			accountsPayables: 6020000000,
			otherWorkingCapital: 3821000000,
			otherNonCashItems: -2266000000,
			netCashProvidedByOperatingActivities: 118254000000,
			investmentsInPropertyPlantAndEquipment: -9447000000,
			acquisitionsNet: 0,
			purchasesOfInvestments: -48656000000,
			salesMaturitiesOfInvestments: 62346000000,
			otherInvestingActivities: -1308000000,
			netCashProvidedByInvestingActivities: 2935000000,
			netDebtIssuance: -5998000000,
			longTermNetDebtIssuance: -9958000000,
			shortTermNetDebtIssuance: 3960000000,
			netStockIssuance: -94949000000,
			netCommonStockIssuance: -94949000000,
			commonStockIssuance: 0,
			commonStockRepurchased: -94949000000,
			netPreferredStockIssuance: 0,
			netDividendsPaid: -15234000000,
			commonDividendsPaid: -15234000000,
			preferredDividendsPaid: 0,
			otherFinancingActivities: -5802000000,
			netCashProvidedByFinancingActivities: -121983000000,
			effectOfForexChangesOnCash: 0,
			netChangeInCash: -794000000,
			cashAtEndOfPeriod: 29943000000,
			cashAtBeginningOfPeriod: 30737000000,
			operatingCashFlow: 118254000000,
			capitalExpenditure: -9447000000,
			freeCashFlow: 108807000000,
			incomeTaxesPaid: 26102000000,
			interestPaid: 0,
		))->toArray(), $statements[0]->toArray());
	}

	public function testCashFlowStatementBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/cash-flow-statement-bulk.csv');

		$statements = iterator_to_array($client->cashFlowStatementBulk('2024'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new CashFlowStatement(
			symbol: '000001.SZ',
			date: '2024-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2024-12-31',
			acceptedDate: '2024-12-31 00:00:00',
			fiscalYear: '2024',
			period: Period::FY,
			netIncome: 42533000000,
			depreciationAndAmortization: 4667000000,
			deferredIncomeTax: 0,
			stockBasedCompensation: 0,
			changeInWorkingCapital: -9659000000,
			accountsReceivables: -202985000000,
			inventory: 0,
			accountsPayables: 0,
			otherWorkingCapital: 193326000000,
			otherNonCashItems: 25795000000,
			netCashProvidedByOperatingActivities: 63336000000,
			investmentsInPropertyPlantAndEquipment: -2388000000,
			acquisitionsNet: 0,
			purchasesOfInvestments: -676348000000,
			salesMaturitiesOfInvestments: 646532000000,
			otherInvestingActivities: 345000000,
			netCashProvidedByInvestingActivities: -31859000000,
			netDebtIssuance: -778465000000,
			longTermNetDebtIssuance: -778465000000,
			shortTermNetDebtIssuance: 0,
			netStockIssuance: 0,
			netCommonStockIssuance: 0,
			commonStockIssuance: 0,
			commonStockRepurchased: 0,
			netPreferredStockIssuance: 0,
			netDividendsPaid: -26807000000,
			commonDividendsPaid: -26807000000,
			preferredDividendsPaid: 0,
			otherFinancingActivities: 731339000000,
			netCashProvidedByFinancingActivities: -73933000000,
			effectOfForexChangesOnCash: 1183000000,
			netChangeInCash: -41273000000,
			cashAtEndOfPeriod: 256946000000,
			cashAtBeginningOfPeriod: 298219000000,
			operatingCashFlow: 63336000000,
			capitalExpenditure: -2388000000,
			freeCashFlow: 60948000000,
			incomeTaxesPaid: 0,
			interestPaid: 0,
		))->toArray(), $statements[0]->toArray());
	}

}