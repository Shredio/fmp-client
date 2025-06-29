<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\IncomeStatement;
use Tests\TestCase;

final class IncomeStatementTest extends TestCase
{

	public function testIncomeStatement(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-aapl.json');

		$statements = iterator_to_array($client->incomeStatement('AAPL'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new IncomeStatement(
			symbol: 'AAPL',
			date: '2024-09-28',
			reportedCurrency: 'USD',
			cik: '0000320193',
			filingDate: '2024-11-01',
			acceptedDate: '2024-11-01 06:01:36',
			fiscalYear: '2024',
			period: Period::FY,
			revenue: 391035000000,
			costOfRevenue: 210352000000,
			grossProfit: 180683000000,
			researchAndDevelopmentExpenses: 31370000000,
			generalAndAdministrativeExpenses: 0,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 26097000000,
			otherExpenses: 0,
			operatingExpenses: 57467000000,
			costAndExpenses: 267819000000,
			netInterestIncome: 0,
			interestIncome: 0,
			interestExpense: 0,
			depreciationAndAmortization: 11445000000,
			ebitda: 134661000000,
			ebit: 123216000000,
			nonOperatingIncomeExcludingInterest: 0,
			operatingIncome: 123216000000,
			totalOtherIncomeExpensesNet: 269000000,
			incomeBeforeTax: 123485000000,
			incomeTaxExpense: 29749000000,
			netIncomeFromContinuingOperations: 93736000000,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 93736000000,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 93736000000,
			eps: 6.11,
			epsDiluted: 6.08,
			weightedAverageShsOut: 15343783000,
			weightedAverageShsOutDil: 15408095000,
		))->toArray(), $statements[0]->toArray());
	}

	public function testIncomeStatementBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-bulk.csv');

		$statements = iterator_to_array($client->incomeStatementBulk('2024'));

		$this->assertNotEmpty($statements);
		$this->assertSame((new IncomeStatement(
			symbol: '000001.SZ',
			date: '2024-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2024-12-31',
			acceptedDate: '2024-12-31 00:00:00',
			fiscalYear: '2024',
			period: Period::FY,
			revenue: 146687000000,
			costOfRevenue: 0,
			grossProfit: 146687000000,
			researchAndDevelopmentExpenses: 0,
			generalAndAdministrativeExpenses: 18449000000,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 18449000000,
			otherExpenses: 23612000000,
			operatingExpenses: 42061000000,
			costAndExpenses: 42061000000,
			netInterestIncome: 93427000000,
			interestIncome: 198381000000,
			interestExpense: 104954000000,
			depreciationAndAmortization: 4667000000,
			ebitda: 0,
			ebit: -4667000000,
			nonOperatingIncomeExcludingInterest: 109293000000,
			operatingIncome: 104626000000,
			totalOtherIncomeExpensesNet: -49888000000,
			incomeBeforeTax: 54738000000,
			incomeTaxExpense: 10230000000,
			netIncomeFromContinuingOperations: 44508000000,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 44508000000,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 44508000000,
			eps: 2.15,
			epsDiluted: 2.15,
			weightedAverageShsOut: 20701395349,
			weightedAverageShsOutDil: 20701395349,
		))->toArray(), $statements[0]->toArray());
	}

}