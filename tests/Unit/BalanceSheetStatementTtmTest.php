<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\BalanceSheetStatement;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class BalanceSheetStatementTtmTest extends TestCase
{

	public function testBalanceSheetStatementTtm(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/balance-sheet-statement-ttm-aapl.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$statements = iterator_to_array($client->balanceSheetStatementTtm('AAPL', 1000));

		$this->assertCount(152, $statements);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/balance-sheet-statement-ttm?symbol=AAPL&limit=1000&apikey=SECRET',
			$response->getRequestUrl(),
		);
		// the API leaves capitalLeaseObligationsNonCurrent out of the trailing statements
		$this->assertSame((new BalanceSheetStatement(
			symbol: 'AAPL',
			date: '2026-06-27',
			reportedCurrency: 'USD',
			cik: '0000320193',
			filingDate: '2026-07-31',
			acceptedDate: '2026-07-31 06:01:02',
			fiscalYear: '2026',
			period: Period::Q3,
			cashAndCashEquivalents: 39544000000.0,
			shortTermInvestments: 22855000000.0,
			cashAndShortTermInvestments: 62399000000.0,
			netReceivables: 58907000000.0,
			accountsReceivables: 31398000000.0,
			otherReceivables: 27509000000.0,
			inventory: 11092000000.0,
			prepaids: 0.0,
			otherCurrentAssets: 17420000000.0,
			totalCurrentAssets: 149818000000.0,
			propertyPlantEquipmentNet: 51431000000.0,
			goodwill: 0.0,
			intangibleAssets: 20342000000.0,
			goodwillAndIntangibleAssets: 20342000000.0,
			longTermInvestments: 84118000000.0,
			taxAssets: 0.0,
			otherNonCurrentAssets: 77557000000.0,
			totalNonCurrentAssets: 233448000000.0,
			otherAssets: 0.0,
			totalAssets: 383266000000.0,
			totalPayables: 64525000000.0,
			accountPayables: 64525000000.0,
			otherPayables: 0.0,
			accruedExpenses: 0.0,
			shortTermDebt: 12967000000.0,
			capitalLeaseObligationsCurrent: 0.0,
			taxPayables: 0.0,
			deferredRevenue: 9538000000.0,
			otherCurrentLiabilities: 62296000000.0,
			totalCurrentLiabilities: 149326000000.0,
			longTermDebt: 71340000000.0,
			deferredRevenueNonCurrent: 0.0,
			deferredTaxLiabilitiesNonCurrent: 0.0,
			otherNonCurrentLiabilities: 55080000000.0,
			totalNonCurrentLiabilities: 126420000000.0,
			otherLiabilities: 0.0,
			capitalLeaseObligations: 0.0,
			totalLiabilities: 275746000000.0,
			treasuryStock: 0.0,
			preferredStock: 0.0,
			commonStock: 100702000000.0,
			retainedEarnings: 11326000000.0,
			additionalPaidInCapital: 0.0,
			accumulatedOtherComprehensiveIncomeLoss: -4508000000.0,
			otherTotalStockholdersEquity: 0.0,
			totalStockholdersEquity: 107520000000.0,
			totalEquity: 107520000000.0,
			minorityInterest: 0.0,
			totalLiabilitiesAndTotalEquity: 383266000000.0,
			totalInvestments: 106973000000.0,
			totalDebt: 84307000000.0,
			netDebt: 44763000000.0,
		))->toArray(), $statements[0]->toArray());
	}

	public function testSeriesReachesBackToTheOldestQuarter(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/balance-sheet-statement-ttm-aapl.json');

		$statements = iterator_to_array($client->balanceSheetStatementTtm('AAPL', 1000));

		$this->assertSame('2026-03-28', $statements[1]->date);
		$this->assertSame(Period::Q2, $statements[1]->period);
		$this->assertSame('1985-09-30', $statements[151]->date);
		$this->assertSame(936200000.0, $statements[151]->totalAssets);
	}

	public function testDefaultLimitIsLeftToTheApi(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/empty-response.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$this->assertSame([], iterator_to_array($client->balanceSheetStatementTtm('UNKNOWN')));
		$this->assertSame(
			'https://financialmodelingprep.com/stable/balance-sheet-statement-ttm?symbol=UNKNOWN&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

}
