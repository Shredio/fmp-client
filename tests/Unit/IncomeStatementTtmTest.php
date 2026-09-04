<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\IncomeStatement;
use Tests\TestCase;

final class IncomeStatementTtmTest extends TestCase
{

	public function testIncomeStatementTtm(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-ttm-aapl.json');

		$statements = iterator_to_array($client->incomeStatementTtm('AAPL'));

		$this->assertCount(50, $statements);
		$this->assertSame((new IncomeStatement(
			symbol: 'AAPL',
			date: '2026-06-27',
			reportedCurrency: 'USD',
			cik: '0000320193',
			filingDate: '2026-07-31',
			acceptedDate: '2026-07-31 06:01:02',
			fiscalYear: '2026',
			period: Period::Q3,
			revenue: 466823000000.0,
			costOfRevenue: 239700000000.0,
			grossProfit: 227123000000.0,
			researchAndDevelopmentExpenses: 42901000000.0,
			generalAndAdministrativeExpenses: 2095000000.0,
			sellingAndMarketingExpenses: 5397000000.0,
			sellingGeneralAndAdministrativeExpenses: 29363000000.0,
			otherExpenses: 0.0,
			operatingExpenses: 72264000000.0,
			costAndExpenses: 311964000000.0,
			netInterestIncome: 0.0,
			interestIncome: 0.0,
			interestExpense: 0.0,
			depreciationAndAmortization: 13100000000.0,
			ebitda: 168486000000.0,
			ebit: 155334000000.0,
			nonOperatingIncomeExcludingInterest: -579000000.0,
			operatingIncome: 154859000000.0,
			totalOtherIncomeExpensesNet: 1047000000.0,
			incomeBeforeTax: 155906000000.0,
			incomeTaxExpense: 26976000000.0,
			netIncomeFromContinuingOperations: 128930000000.0,
			netIncomeFromDiscontinuedOperations: 0.0,
			otherAdjustmentsToNetIncome: 0.0,
			netIncome: 128930000000.0,
			netIncomeDeductions: 0.0,
			bottomLineNetIncome: 128930000000.0,
			eps: 8.76,
			epsDiluted: 8.73,
			weightedAverageShsOut: 14692515000,
			weightedAverageShsOutDil: 14750302000,
		))->toArray(), $statements[0]->toArray());
	}

	public function testTrailingRevenueExceedsQuarterlyRevenue(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-ttm-aapl.json');

		$statements = iterator_to_array($client->incomeStatementTtm('AAPL'));

		// The endpoint returns a historical series of trailing twelve months values, not a single snapshot
		$this->assertSame('2026-06-27', $statements[0]->date);
		$this->assertSame('2026-03-28', $statements[1]->date);
		$this->assertSame(Period::Q2, $statements[1]->period);
		$this->assertGreaterThan($statements[1]->revenue, $statements[0]->revenue);
	}

}
