<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\BatchForexQuote;
use Tests\TestCase;

final class BatchForexQuoteTest extends TestCase
{

	public function testBatchForexQuotes(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/batch-forex-quotes.json');

		$quotes = iterator_to_array($client->batchForexQuotes());

		$this->assertNotEmpty($quotes);
		$this->assertSame((new BatchForexQuote(
			symbol: 'AEDAUD',
			price: 0.41687,
			change: -0.000036340187,
			volume: 0,
		))->toArray(), $quotes[0]->toArray());
	}

}