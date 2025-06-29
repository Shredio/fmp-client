<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\AnalystEstimate;
use Tests\TestCase;

final class AnalystEstimateTest extends TestCase
{

	public function testAnalystEstimate(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/analyst-estimates-aapl.json');

		$estimates = iterator_to_array($client->getAnalystEstimates('AAPL'));

		$this->assertNotEmpty($estimates);
		$this->assertSame((new AnalystEstimate(
			symbol: 'AAPL',
			date: '2029-09-28',
			revenueLow: 472751666666,
			revenueHigh: 493432333333,
			revenueAvg: 483092000000,
			ebitdaLow: 154630365496,
			ebitdaHigh: 161394718265,
			ebitdaAvg: 158012541881,
			ebitLow: 139634510212,
			ebitHigh: 145742864692,
			ebitAvg: 142688687452,
			netIncomeLow: 159591347025,
			netIncomeHigh: 176484782384,
			netIncomeAvg: 166356117054,
			sgaExpenseLow: 31016213130,
			sgaExpenseHigh: 32373026887,
			sgaExpenseAvg: 31694620008,
			epsAvg: 10.79667,
			epsHigh: 11.45403,
			epsLow: 10.35763,
			numAnalystsRevenue: 14,
			numAnalystsEps: 10,
		))->toArray(), $estimates[0]->toArray());
	}

}