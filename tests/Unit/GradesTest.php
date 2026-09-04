<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Grade;
use Shredio\FmpClient\Payload\GradesConsensus;
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

}
