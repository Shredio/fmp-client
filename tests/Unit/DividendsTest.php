<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Dividend;
use Tests\TestCase;

final class DividendsTest extends TestCase
{

	public function testDividends(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/dividends_AAPL.json');

		$dividends = [];
		foreach ($client->dividends('AAPL') as $dividend) {
			$dividends[] = $dividend;
		}

		$this->assertNotEmpty($dividends);
		$this->assertCount(88, $dividends);

		$expectedFirstDividend = new Dividend(
			symbol: 'AAPL',
			date: '2025-08-11',
			recordDate: '2025-08-11',
			paymentDate: '2025-08-14',
			declarationDate: '2025-07-31',
			adjDividend: 0.26,
			dividend: 0.26,
			yield: 0.44898318513953694,
			frequency: 'Quarterly'
		);

		$this->assertSame($expectedFirstDividend->toArray(), $dividends[0]->toArray());

		$expectedSecondDividend = new Dividend(
			symbol: 'AAPL',
			date: '2025-05-12',
			recordDate: '2025-05-12',
			paymentDate: '2025-05-15',
			declarationDate: '2025-05-01',
			adjDividend: 0.26,
			dividend: 0.26,
			yield: 0.4791498647943451,
			frequency: 'Quarterly'
		);

		$this->assertSame($expectedSecondDividend->toArray(), $dividends[1]->toArray());
	}

	public function testDividendsWithUnconfirmedRecord(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/dividends_POMRY.PA_unconfirmed.json');

		$dividends = iterator_to_array($client->dividends('POMRY.PA'));

		$this->assertCount(1, $dividends);

		$expectedDividend = new Dividend(
			symbol: 'POMRY.PA',
			date: '2026-09-18',
			recordDate: null,
			paymentDate: null,
			declarationDate: null,
			adjDividend: null,
			dividend: null,
			yield: 0.0,
			frequency: 'Annual'
		);

		$this->assertSame($expectedDividend->toArray(), $dividends[0]->toArray());
	}

}
