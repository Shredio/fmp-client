<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\BatchExchangeDetailedQuote;
use Shredio\FmpClient\Payload\BatchExchangeQuote;
use Tests\TestCase;

final class BatchExchangeQuoteTest extends TestCase
{

	public function testBatchExchangeQuoteShort(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/batch-exchange-quote-short.json');

		$quotes = iterator_to_array($client->batchExchangeQuote('NASDAQ'));

		$this->assertNotEmpty($quotes);
		$this->assertSame((new BatchExchangeQuote(
			symbol: 'AAACX',
			price: 6.39,
			change: -0.09,
			volume: 0,
		))->toArray(), $quotes[0]->toArray());
	}

	public function testBatchExchangeQuoteDetailed(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/batch-exchange-quote-detailed.json');

		$quotes = iterator_to_array($client->batchExchangeQuoteDetailed('NASDAQ'));

		$this->assertNotEmpty($quotes);
		$this->assertSame((new BatchExchangeDetailedQuote(
			symbol: 'AAACX',
			name: 'Alpha Alternative Assets Fund',
			exchange: 'NASDAQ',
			price: 6.39,
			changePercentage: -1.38889,
			change: -0.09,
			volume: 0,
			dayLow: 6.39,
			dayHigh: 6.39,
			yearHigh: 6.48,
			yearLow: 6.19,
			marketCap: 0,
			priceAvg50: 6.4402,
			priceAvg200: 6.4027,
			open: 6.39,
			previousClose: 6.48,
			timestamp: 1751054400,
		))->toArray(), $quotes[0]->toArray());
	}

}