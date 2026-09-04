<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\DiscountedCashFlow;
use Tests\TestCase;

final class DiscountedCashFlowTest extends TestCase
{

	public function testDiscountedCashFlow(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/discounted-cash-flow-aapl.json');

		$dcf = $client->discountedCashFlow('AAPL');

		$this->assertNotNull($dcf);
		$this->assertSame((new DiscountedCashFlow(
			symbol: 'AAPL',
			date: '2026-09-04',
			dcf: 144.47336055336862,
			stockPrice: 328.21,
		))->toArray(), $dcf->toArray());
	}

	public function testSymbolWithoutValuation(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->discountedCashFlow('SPY'));
	}

}
