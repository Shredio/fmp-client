<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Grade;
use Shredio\FmpClient\Payload\GradesConsensus;
use Shredio\FmpClient\Payload\GradesHistorical;
use Tests\TestCase;

final class GradesTest extends TestCase
{

	public function testGradesConsensus(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/grades-consensus-aapl.json');

		$consensus = $client->gradesConsensus('AAPL');

		$this->assertNotNull($consensus);
		$this->assertSame((new GradesConsensus(
			symbol: 'AAPL',
			strongBuy: 1,
			buy: 70,
			hold: 32,
			sell: 9,
			strongSell: 0,
			consensus: 'Buy',
		))->toArray(), $consensus->toArray());
	}

	public function testGradesConsensusWithoutCoverage(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->gradesConsensus('CEZ.PR'));
	}

	public function testGrades(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/grades-aapl.json');

		$grades = iterator_to_array($client->grades('AAPL'));

		$this->assertCount(1794, $grades);
		$this->assertSame((new Grade(
			symbol: 'AAPL',
			date: '2026-09-02',
			gradingCompany: 'DA Davidson',
			previousGrade: 'Neutral',
			newGrade: 'Neutral',
			action: 'maintain',
		))->toArray(), $grades[0]->toArray());

		$this->assertSame((new Grade(
			symbol: 'AAPL',
			date: '2026-08-17',
			gradingCompany: 'Rothschild & Co',
			previousGrade: 'Neutral',
			newGrade: 'Buy',
			action: 'upgrade',
		))->toArray(), $grades[3]->toArray());
	}

	public function testGradesConsensusBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/upgrades-downgrades-consensus-bulk.csv');

		$consensuses = iterator_to_array($client->gradesConsensusBulk());

		$this->assertNotEmpty($consensuses);
		$this->assertSame((new GradesConsensus(
			symbol: '000550.SZ',
			strongBuy: 1,
			buy: 14,
			hold: 4,
			sell: 0,
			strongSell: 0,
			consensus: 'Buy',
		))->toArray(), $consensuses[0]->toArray());
		$this->assertSame((new GradesConsensus(
			symbol: '0220.HK',
			strongBuy: 0,
			buy: 0,
			hold: 0,
			sell: 1,
			strongSell: 0,
			consensus: 'Sell',
		))->toArray(), $consensuses[6]->toArray());
	}

	public function testGradesHistorical(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/grades-historical-aapl.json');

		$snapshots = iterator_to_array($client->gradesHistorical('AAPL'));

		$this->assertCount(94, $snapshots);
		$this->assertSame((new GradesHistorical(
			symbol: 'AAPL',
			date: '2026-10-01',
			analystRatingsStrongBuy: 6,
			analystRatingsBuy: 19,
			analystRatingsHold: 13,
			analystRatingsSell: 3,
			analystRatingsStrongSell: 3,
		))->toArray(), $snapshots[0]->toArray());
		$this->assertSame((new GradesHistorical(
			symbol: 'AAPL',
			date: '2018-12-01',
			analystRatingsStrongBuy: 17,
			analystRatingsBuy: 14,
			analystRatingsHold: 18,
			analystRatingsSell: 0,
			analystRatingsStrongSell: 0,
		))->toArray(), $snapshots[93]->toArray());
	}

	public function testGradesHistoricalWithoutCoverage(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->gradesHistorical('SPY')));
	}

}
