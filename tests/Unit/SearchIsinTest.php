<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\IsinSearchResult;
use Tests\TestCase;

final class SearchIsinTest extends TestCase
{

	public function testSearchIsin(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/search-isin.json');

		$results = iterator_to_array($client->searchIsin('US0378331005'));

		$this->assertNotEmpty($results);
		$this->assertContainsOnlyInstancesOf(IsinSearchResult::class, $results);
		$this->assertCount(5, $results);

		$this->assertSame((new IsinSearchResult(
			symbol: 'AAPL',
			name: 'Apple Inc.',
			isin: 'US0378331005',
			marketCap: 3888777002850.0,
		))->toArray(), $results[0]->toArray());

		$this->assertSame('AAPL.MX', $results[1]->symbol);
		$this->assertSame('US0378331005', $results[1]->isin);
	}

}
