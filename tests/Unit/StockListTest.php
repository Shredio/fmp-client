<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Stock;
use Tests\TestCase;

final class StockListTest extends TestCase
{

	public function testStockList(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/stock-list.json');

		$stocks = iterator_to_array($client->stockList());

		$this->assertNotEmpty($stocks);
		$this->assertSame((new Stock(
			symbol: 'SMMV',
			companyName: 'iShares MSCI USA Small-Cap Min Vol Factor ETF',
		))->toArray(), $stocks[0]->toArray());
	}

}