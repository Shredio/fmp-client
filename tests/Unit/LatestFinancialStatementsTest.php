<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\LatestFinancialStatement;
use Tests\TestCase;

final class LatestFinancialStatementsTest extends TestCase
{

	public function testLatestFinancialStatements(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/latest-financial-statements.json');

		$statements = [];
		foreach ($client->latestFinancialStatements() as $statement) {
			$statements[] = $statement;
		}

		$this->assertNotEmpty($statements);
		$this->assertGreaterThan(100, count($statements));

		$expectedFirstStatement = new LatestFinancialStatement(
			symbol: '001570.KS',
			calendarYear: 2025,
			period: 'Q1',
			date: '2025-03-31',
			dateAdded: '2025-06-27 18:06:09'
		);

		$this->assertSame($expectedFirstStatement->toArray(), $statements[0]->toArray());
	}

}