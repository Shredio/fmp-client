<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Scores;
use Tests\TestCase;

final class ScoresTest extends TestCase
{

	public function testFinancialScores(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/financial-scores.json');

		$scores = iterator_to_array($client->financialScores('AAPL'));

		$this->assertNotEmpty($scores);
		$this->assertSame((new Scores(
			symbol: 'AAPL',
			reportedCurrency: 'USD',
			altmanZScore: 9.068729692830011,
			piotroskiScore: 8,
			workingCapital: -25897000000,
			totalAssets: 331233000000,
			retainedEarnings: -15552000000,
			ebit: 127364000000,
			marketCap: 2975211360000,
			totalLiabilities: 264437000000,
			revenue: 400366000000,
		))->toArray(), $scores[0]->toArray());
	}

	public function testScoresBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/scores-bulk.csv');

		$scores = iterator_to_array($client->scoresBulk());

		$this->assertNotEmpty($scores);
		$this->assertSame((new Scores(
			symbol: '000001.SZ',
			reportedCurrency: 'CNY',
			altmanZScore: 0.29153682196643543,
			piotroskiScore: 5,
			workingCapital: 746131000000,
			totalAssets: 5777858000000,
			retainedEarnings: 255621000000,
			ebit: 32590000000,
			marketCap: 236751980000,
			totalLiabilities: 5271746000000,
			revenue: 167996000000,
		))->toArray(), $scores[0]->toArray());
	}

}