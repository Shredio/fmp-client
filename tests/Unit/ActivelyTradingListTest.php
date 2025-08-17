<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\ActivelyTrading;
use Tests\TestCase;

final class ActivelyTradingListTest extends TestCase
{

	public function testActivelyTradingCompaniesList(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/actively-trading.json');

		$companies = iterator_to_array($client->activelyTradingList());

		$this->assertNotEmpty($companies);
		$this->assertSame((new ActivelyTrading(
			symbol: 'MPL.L',
			name: 'Mercantile Ports & Logistics Limited',
		))->toArray(), $companies[0]->toArray());
	}

}
