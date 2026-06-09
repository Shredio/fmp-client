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

		$statements = iterator_to_array($client->incomeStatementBulk(2024));

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

	public function testIncomeStatementWithNullValues(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-null-values.json');

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
			revenue: 0,
			costOfRevenue: 0,
			grossProfit: 0,
			researchAndDevelopmentExpenses: 0,
			generalAndAdministrativeExpenses: 0,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 0,
			otherExpenses: 0,
			operatingExpenses: 0,
			costAndExpenses: 0,
			netInterestIncome: 0,
			interestIncome: 0,
			interestExpense: 0,
			depreciationAndAmortization: 0,
			ebitda: 0,
			ebit: 0,
			nonOperatingIncomeExcludingInterest: 0,
			operatingIncome: 0,
			totalOtherIncomeExpensesNet: 0,
			incomeBeforeTax: 0,
			incomeTaxExpense: 0,
			netIncomeFromContinuingOperations: 0,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 0,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 0,
			eps: 0.0,
			epsDiluted: 0.0,
			weightedAverageShsOut: null,
			weightedAverageShsOutDil: null,
		))->toArray(), $statements[0]->toArray());
	}

	public function testIncomeStatementBulkWithNanValues(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-bulk-nan.csv');

		$statements = iterator_to_array($client->incomeStatementBulk(2024));

		$this->assertCount(3, $statements);

		// Test "nan" is converted to 0.0
		$this->assertSame((new IncomeStatement(
			symbol: 'NAN1.SZ',
			date: '2024-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2024-12-31',
			acceptedDate: '2024-12-31 00:00:00',
			fiscalYear: '2024',
			period: Period::FY,
			revenue: 100000000,
			costOfRevenue: 0.0,
			grossProfit: 50000000,
			researchAndDevelopmentExpenses: 0,
			generalAndAdministrativeExpenses: 10000000,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 10000000,
			otherExpenses: 5000000,
			operatingExpenses: 15000000,
			costAndExpenses: 65000000,
			netInterestIncome: 0,
			interestIncome: 0,
			interestExpense: 0,
			depreciationAndAmortization: 2000000,
			ebitda: 37000000,
			ebit: 35000000,
			nonOperatingIncomeExcludingInterest: 0,
			operatingIncome: 35000000,
			totalOtherIncomeExpensesNet: 0,
			incomeBeforeTax: 35000000,
			incomeTaxExpense: 7000000,
			netIncomeFromContinuingOperations: 28000000,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 28000000,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 28000000,
			eps: 1.5,
			epsDiluted: 1.5,
			weightedAverageShsOut: 10000000,
			weightedAverageShsOutDil: 10000000,
		))->toArray(), $statements[0]->toArray());

		// Test "N/A" is converted to 0.0
		$this->assertSame((new IncomeStatement(
			symbol: 'NAN2.SZ',
			date: '2024-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2024-12-31',
			acceptedDate: '2024-12-31 00:00:00',
			fiscalYear: '2024',
			period: Period::FY,
			revenue: 200000000,
			costOfRevenue: 0.0,
			grossProfit: 100000000,
			researchAndDevelopmentExpenses: 0,
			generalAndAdministrativeExpenses: 20000000,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 20000000,
			otherExpenses: 10000000,
			operatingExpenses: 30000000,
			costAndExpenses: 130000000,
			netInterestIncome: 0,
			interestIncome: 0,
			interestExpense: 0,
			depreciationAndAmortization: 4000000,
			ebitda: 74000000,
			ebit: 70000000,
			nonOperatingIncomeExcludingInterest: 0,
			operatingIncome: 70000000,
			totalOtherIncomeExpensesNet: 0,
			incomeBeforeTax: 70000000,
			incomeTaxExpense: 14000000,
			netIncomeFromContinuingOperations: 56000000,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 56000000,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 56000000,
			eps: 2.5,
			epsDiluted: 2.5,
			weightedAverageShsOut: 20000000,
			weightedAverageShsOutDil: 20000000,
		))->toArray(), $statements[1]->toArray());

		// Test "NA" is converted to 0.0
		$this->assertSame((new IncomeStatement(
			symbol: 'NAN3.SZ',
			date: '2024-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2024-12-31',
			acceptedDate: '2024-12-31 00:00:00',
			fiscalYear: '2024',
			period: Period::FY,
			revenue: 300000000,
			costOfRevenue: 0.0,
			grossProfit: 150000000,
			researchAndDevelopmentExpenses: 0,
			generalAndAdministrativeExpenses: 30000000,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 30000000,
			otherExpenses: 15000000,
			operatingExpenses: 45000000,
			costAndExpenses: 195000000,
			netInterestIncome: 0,
			interestIncome: 0,
			interestExpense: 0,
			depreciationAndAmortization: 6000000,
			ebitda: 111000000,
			ebit: 105000000,
			nonOperatingIncomeExcludingInterest: 0,
			operatingIncome: 105000000,
			totalOtherIncomeExpensesNet: 0,
			incomeBeforeTax: 105000000,
			incomeTaxExpense: 21000000,
			netIncomeFromContinuingOperations: 84000000,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 84000000,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 84000000,
			eps: 3.5,
			epsDiluted: 3.5,
			weightedAverageShsOut: 30000000,
			weightedAverageShsOutDil: 30000000,
		))->toArray(), $statements[2]->toArray());
	}

	public function testIncomeStatementBulkWithInfinityValues(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/income-statement-bulk-infinity.csv');

		$statements = iterator_to_array($client->incomeStatementBulk(2024));

		$this->assertCount(2, $statements);

		// Test positive Infinity: non-nullable float fields become 0.0, nullable share-count fields become null
		$this->assertSame((new IncomeStatement(
			symbol: 'TEST.SZ',
			date: '2024-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2024-12-31',
			acceptedDate: '2024-12-31 00:00:00',
			fiscalYear: '2024',
			period: Period::FY,
			revenue: 100000000,
			costOfRevenue: 50000000,
			grossProfit: 50000000,
			researchAndDevelopmentExpenses: 0,
			generalAndAdministrativeExpenses: 10000000,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 10000000,
			otherExpenses: 5000000,
			operatingExpenses: 15000000,
			costAndExpenses: 65000000,
			netInterestIncome: 0,
			interestIncome: 0,
			interestExpense: 0,
			depreciationAndAmortization: 2000000,
			ebitda: 37000000,
			ebit: 35000000,
			nonOperatingIncomeExcludingInterest: 0,
			operatingIncome: 35000000,
			totalOtherIncomeExpensesNet: 0,
			incomeBeforeTax: 35000000,
			incomeTaxExpense: 7000000,
			netIncomeFromContinuingOperations: 28000000,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 28000000,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 28000000,
			eps: 1.5,
			epsDiluted: 0.0,
			weightedAverageShsOut: null,
			weightedAverageShsOutDil: null,
		))->toArray(), $statements[0]->toArray());

		// Test negative Infinity: non-nullable float fields become 0.0, nullable share-count fields become null
		$this->assertSame((new IncomeStatement(
			symbol: 'TEST2.SZ',
			date: '2024-12-31',
			reportedCurrency: 'CNY',
			cik: '0000000000',
			filingDate: '2024-12-31',
			acceptedDate: '2024-12-31 00:00:00',
			fiscalYear: '2024',
			period: Period::FY,
			revenue: 200000000,
			costOfRevenue: 100000000,
			grossProfit: 100000000,
			researchAndDevelopmentExpenses: 0,
			generalAndAdministrativeExpenses: 20000000,
			sellingAndMarketingExpenses: 0,
			sellingGeneralAndAdministrativeExpenses: 20000000,
			otherExpenses: 10000000,
			operatingExpenses: 30000000,
			costAndExpenses: 130000000,
			netInterestIncome: 0,
			interestIncome: 0,
			interestExpense: 0,
			depreciationAndAmortization: 4000000,
			ebitda: 74000000,
			ebit: 70000000,
			nonOperatingIncomeExcludingInterest: 0,
			operatingIncome: 70000000,
			totalOtherIncomeExpensesNet: 0,
			incomeBeforeTax: 70000000,
			incomeTaxExpense: 14000000,
			netIncomeFromContinuingOperations: 56000000,
			netIncomeFromDiscontinuedOperations: 0,
			otherAdjustmentsToNetIncome: 0,
			netIncome: 56000000,
			netIncomeDeductions: 0,
			bottomLineNetIncome: 56000000,
			eps: 2.5,
			epsDiluted: 0.0,
			weightedAverageShsOut: null,
			weightedAverageShsOutDil: null,
		))->toArray(), $statements[1]->toArray());
	}

}
