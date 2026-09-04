<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Quote;
use Tests\TestCase;

final class QuoteTest extends TestCase
{

	public function testQuote(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/quote-aapl.json');

		$quote = $client->quote('AAPL');

		$this->assertNotNull($quote);
		$this->assertSame((new Quote(
			symbol: 'AAPL',
			name: 'Apple Inc.',
			exchange: 'NASDAQ',
			price: 328.21,
			changePercentage: 1.00012,
			change: 3.25,
			volume: 37197362,
			dayLow: 324.11,
			dayHigh: 330.81,
			yearHigh: 344.57,
			yearLow: 225.95,
			marketCap: 4820537112760,
			priceAvg50: 313.5524,
			priceAvg200: 283.3285,
			open: 324.9477,
			previousClose: 324.96,
			timestamp: 1788465601,
		))->toArray(), $quote->toArray());
	}

	public function testQuoteWithoutMarketCap(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/quote-eurusd.json');

		$quote = $client->quote('EURUSD');

		$this->assertNotNull($quote);
		$this->assertSame('EURUSD', $quote->symbol);
		$this->assertSame('FOREX', $quote->exchange);
		$this->assertNull($quote->marketCap);
		$this->assertSame(1.162, $quote->price);
	}

	public function testQuoteNotFound(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->quote('UNKNOWN'));
	}

}
