<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\TreasuryRate;
use Tests\TestCase;

final class TreasuryRatesTest extends TestCase
{

	public function testTreasuryRates(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/treasury-rates.json');

		$rates = iterator_to_array($client->treasuryRates());

		$this->assertGreaterThan(50, count($rates));
		$this->assertSame((new TreasuryRate(
			date: '2025-11-05',
			month1: 4.0,
			month2: 4.0,
			month3: 3.96,
			month6: 3.79,
			year1: 3.71,
			year2: 3.63,
			year3: 3.65,
			year5: 3.76,
			year7: 3.95,
			year10: 4.17,
			year20: 4.72,
			year30: 4.74,
		))->toArray(), $rates[0]->toArray());
	}

}
