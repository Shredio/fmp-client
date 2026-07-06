<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\StockSplit;
use Tests\TestCase;

final class SplitsTest extends TestCase
{

	public function testSplits(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/splits-aapl.json');

		$splits = iterator_to_array($client->splits('AAPL'));

		$this->assertCount(5, $splits);

		$this->assertSame((new StockSplit(
			symbol: 'AAPL',
			date: '2020-08-31',
			numerator: 4,
			denominator: 1,
			splitType: 'stock-split',
		))->toArray(), $splits[0]->toArray());

		$this->assertSame((new StockSplit(
			symbol: 'AAPL',
			date: '1987-06-16',
			numerator: 2,
			denominator: 1,
			splitType: 'stock-split',
		))->toArray(), $splits[4]->toArray());
	}

}
