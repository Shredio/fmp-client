<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Index;
use Tests\TestCase;

final class IndexListTest extends TestCase
{

	public function testIndexList(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/index-list.json');

		$indexes = iterator_to_array($client->indexList());

		$this->assertNotEmpty($indexes);
		$this->assertSame((new Index(
			symbol: '^TTIN',
			name: 'S&P/TSX Capped Industrials Index',
			exchange: 'TSX',
			currency: 'CAD',
		))->toArray(), $indexes[0]->toArray());
	}

}