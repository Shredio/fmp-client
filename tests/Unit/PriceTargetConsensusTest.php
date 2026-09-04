<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\PriceTargetConsensus;
use Tests\TestCase;

final class PriceTargetConsensusTest extends TestCase
{

	public function testPriceTargetConsensus(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/price-target-consensus-aapl.json');

		$consensus = $client->priceTargetConsensus('AAPL');

		$this->assertNotNull($consensus);
		$this->assertSame((new PriceTargetConsensus(
			symbol: 'AAPL',
			targetHigh: 400.0,
			targetLow: 245.0,
			targetConsensus: 341.31,
			targetMedian: 362.0,
		))->toArray(), $consensus->toArray());
	}

	public function testSymbolWithoutCoverage(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->priceTargetConsensus('CEZ.PR'));
	}

}
