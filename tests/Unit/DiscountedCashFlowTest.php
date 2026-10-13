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

	public function testDiscountedCashFlowBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/dcf-bulk.csv');

		$valuations = iterator_to_array($client->discountedCashFlowBulk());

		$this->assertNotEmpty($valuations);
		$this->assertSame((new DiscountedCashFlow(
			symbol: '000006.SZ',
			date: '2026-10-13',
			dcf: 2.7887209944052156,
			stockPrice: 7.19,
		))->toArray(), $valuations[0]->toArray());
	}

	public function testDiscountedCashFlowBulkWithoutValuation(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/dcf-bulk.csv');

		$valuations = iterator_to_array($client->discountedCashFlowBulk());

		$this->assertSame((new DiscountedCashFlow(
			symbol: '000023.SZ',
			date: '2024-07-29',
			dcf: null,
			stockPrice: 1.72,
		))->toArray(), $valuations[6]->toArray());
	}

}
