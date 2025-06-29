<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\BalanceSheetStatement;
use Tests\TestCase;

final class BalanceSheetStatementTest extends TestCase
{

	public function testBalanceSheetStatement(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/balance-sheet-statement-aapl.json');

		$statements = iterator_to_array($client->balanceSheetStatement('AAPL'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new BalanceSheetStatement(
			symbol: 'AAPL',
			date: '2024-09-28',
			reportedCurrency: 'USD',
			cik: '0000320193',
			filingDate: '2024-11-01',
			acceptedDate: '2024-11-01 06:01:36',
			fiscalYear: '2024',
			period: Period::FY,
			cashAndCashEquivalents: 29943000000,
			shortTermInvestments: 35228000000,
			cashAndShortTermInvestments: 65171000000,
			netReceivables: 66243000000,
			accountsReceivables: 33410000000,
			otherReceivables: 32833000000,
			inventory: 7286000000,
			prepaids: 0,
			otherCurrentAssets: 14287000000,
			totalCurrentAssets: 152987000000,
			propertyPlantEquipmentNet: 45680000000,
			goodwill: 0,
			intangibleAssets: 0,
			goodwillAndIntangibleAssets: 0,
			longTermInvestments: 91479000000,
			taxAssets: 19499000000,
			otherNonCurrentAssets: 55335000000,
			totalNonCurrentAssets: 211993000000,
			otherAssets: 0,
			totalAssets: 364980000000,
			totalPayables: 95561000000,
			accountPayables: 68960000000,
			otherPayables: 26601000000,
			accruedExpenses: 0,
			shortTermDebt: 20879000000,
			capitalLeaseObligationsCurrent: 1632000000,
			taxPayables: 26601000000,
			deferredRevenue: 8249000000,
			otherCurrentLiabilities: 50071000000,
			totalCurrentLiabilities: 176392000000,
			longTermDebt: 85750000000,
			capitalLeaseObligationsNonCurrent: 10798000000,
			deferredRevenueNonCurrent: 0,
			deferredTaxLiabilitiesNonCurrent: 0,
			otherNonCurrentLiabilities: 35090000000,
			totalNonCurrentLiabilities: 131638000000,
			otherLiabilities: 0,
			capitalLeaseObligations: 12430000000,
			totalLiabilities: 308030000000,
			treasuryStock: 0,
			preferredStock: 0,
			commonStock: 83276000000,
			retainedEarnings: -19154000000,
			additionalPaidInCapital: 0,
			accumulatedOtherComprehensiveIncomeLoss: -7172000000,
			otherTotalStockholdersEquity: 0,
			totalStockholdersEquity: 56950000000,
			totalEquity: 56950000000,
			minorityInterest: 0,
			totalLiabilitiesAndTotalEquity: 364980000000,
			totalInvestments: 126707000000,
			totalDebt: 119059000000,
			netDebt: 89116000000,
		))->toArray(), $statements[0]->toArray());
	}

	public function testBalanceSheetStatementBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/balance-sheet-statement-bulk.csv');

		$statements = iterator_to_array($client->balanceSheetStatementBulk('2023'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new BalanceSheetStatement(
			symbol: '000001.SZ',
			date: '2023-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2023-12-31',
			acceptedDate: '2023-12-30 19:00:00',
			fiscalYear: '2023',
			period: Period::FY,
			cashAndCashEquivalents: 598647000000,
			shortTermInvestments: 0,
			cashAndShortTermInvestments: 598647000000,
			netReceivables: 0,
			accountsReceivables: 0,
			otherReceivables: 0,
			inventory: 0,
			prepaids: 0,
			otherCurrentAssets: 0,
			totalCurrentAssets: 598647000000,
			propertyPlantEquipmentNet: 17436000000,
			goodwill: 7568000000,
			intangibleAssets: 6622000000,
			goodwillAndIntangibleAssets: 14190000000,
			longTermInvestments: 1431426000000,
			taxAssets: 50129000000,
			otherNonCurrentAssets: 4217003000000,
			totalNonCurrentAssets: 5730184000000,
			otherAssets: -741715000000,
			totalAssets: 5587116000000,
			totalPayables: 26569000000,
			accountPayables: 0,
			otherPayables: 26569000000,
			accruedExpenses: 0,
			shortTermDebt: 0,
			capitalLeaseObligationsCurrent: 0,
			taxPayables: 9380000000,
			deferredRevenue: 1905000000,
			otherCurrentLiabilities: 1297507000000,
			totalCurrentLiabilities: 1325981000000,
			longTermDebt: 0,
			capitalLeaseObligationsNonCurrent: 3638000000,
			deferredRevenueNonCurrent: 0,
			deferredTaxLiabilitiesNonCurrent: 0,
			otherNonCurrentLiabilities: 0,
			totalNonCurrentLiabilities: 3638000000,
			otherLiabilities: 3785169000000,
			capitalLeaseObligations: 3638000000,
			totalLiabilities: 5114788000000,
			treasuryStock: 0,
			preferredStock: 19953000000,
			commonStock: 19406000000,
			retainedEarnings: 221255000000,
			additionalPaidInCapital: 80761000000,
			accumulatedOtherComprehensiveIncomeLoss: 0,
			otherTotalStockholdersEquity: 130953000000,
			totalStockholdersEquity: 472328000000,
			totalEquity: 472328000000,
			minorityInterest: 0,
			totalLiabilitiesAndTotalEquity: 5587116000000,
			totalInvestments: 1431426000000,
			totalDebt: 783597000000,
			netDebt: -598647000000,
		))->toArray(), $statements[0]->toArray());
	}

}
