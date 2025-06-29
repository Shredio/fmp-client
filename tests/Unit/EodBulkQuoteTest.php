<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\EodQuote;
use Tests\TestCase;

final class EodBulkQuoteTest extends TestCase
{

	public function testEodBulkQuotes(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/eod-bulk-2024-10-22.csv');

		$quotes = iterator_to_array($client->eodBulkQuotes(new DateTimeImmutable('2024-10-22')));

		$this->assertNotEmpty($quotes);
		$this->assertSame((new EodQuote(
			symbol: 'EGS745W1C011.CA',
			date: '2024-10-22',
			open: 2.67,
			low: 2.7,
			high: 2.9,
			close: 2.93,
			adjClose: 2.93,
			volume: 920904.0,
		))->toArray(), $quotes[0]->toArray());
	}

}
