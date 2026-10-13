<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\CashFlowStatement;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class CashFlowStatementTtmTest extends TestCase
{

	public function testCashFlowStatementTtm(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/cash-flow-statement-ttm-aapl.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$statements = iterator_to_array($client->cashFlowStatementTtm('AAPL', 1000));

		$this->assertCount(147, $statements);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/cash-flow-statement-ttm?symbol=AAPL&limit=1000&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new CashFlowStatement(
			symbol: 'AAPL',
			date: '2026-06-27',
			reportedCurrency: 'USD',
			cik: '0000320193',
			filingDate: '2026-07-31',
			acceptedDate: '2026-07-31 06:01:02',
			fiscalYear: '2026',
			period: Period::Q3,
			netIncome: 128930000000.0,
			depreciationAndAmortization: 13100000000.0,
			deferredIncomeTax: 0.0,
			stockBasedCompensation: 13706000000.0,
			changeInWorkingCapital: -8634000000.0,
			accountsReceivables: -12282000000.0,
			inventory: -5284000000.0,
			accountsPayables: 14178000000.0,
			otherWorkingCapital: -5246000000.0,
			otherNonCashItems: -378000000.0,
			netCashProvidedByOperatingActivities: 146724000000.0,
			investmentsInPropertyPlantAndEquipment: -10041000000.0,
			acquisitionsNet: 0.0,
			purchasesOfInvestments: -55568000000.0,
			salesMaturitiesOfInvestments: 46496000000.0,
			otherInvestingActivities: -2285000000.0,
			netCashProvidedByInvestingActivities: -21398000000.0,
			netDebtIssuance: -17274000000.0,
			longTermNetDebtIssuance: -9396000000.0,
			shortTermNetDebtIssuance: -7878000000.0,
			netStockIssuance: -82226000000.0,
			netCommonStockIssuance: -82226000000.0,
			commonStockIssuance: 0.0,
			commonStockRepurchased: -82226000000.0,
			netPreferredStockIssuance: 0.0,
			netDividendsPaid: -15640000000.0,
			commonDividendsPaid: -15640000000.0,
			preferredDividendsPaid: 0.0,
			otherFinancingActivities: -6911000000.0,
			netCashProvidedByFinancingActivities: -122051000000.0,
			effectOfForexChangesOnCash: 0.0,
			netChangeInCash: 3275000000.0,
			cashAtEndOfPeriod: 39544000000.0,
			cashAtBeginningOfPeriod: 36269000000.0,
			operatingCashFlow: 146724000000.0,
			capitalExpenditure: -10041000000.0,
			freeCashFlow: 136683000000.0,
			incomeTaxesPaid: 32592000000.0,
			interestPaid: 0.0,
		))->toArray(), $statements[0]->toArray());
	}

	public function testSeriesReachesBackToTheOldestQuarter(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/cash-flow-statement-ttm-aapl.json');

		$statements = iterator_to_array($client->cashFlowStatementTtm('AAPL', 1000));

		$this->assertSame('2026-03-28', $statements[1]->date);
		$this->assertSame(Period::Q2, $statements[1]->period);
		$this->assertSame('1989-12-31', $statements[146]->date);
		$this->assertSame(261100000.0, $statements[146]->operatingCashFlow);
	}

	public function testDefaultLimitIsLeftToTheApi(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/empty-response.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$this->assertSame([], iterator_to_array($client->cashFlowStatementTtm('UNKNOWN')));
		$this->assertSame(
			'https://financialmodelingprep.com/stable/cash-flow-statement-ttm?symbol=UNKNOWN&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

}
