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

	public function testQuoteWithoutExchange(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/quote-snds.json');

		$quote = $client->quote('SNDS');

		$this->assertNotNull($quote);
		$this->assertSame((new Quote(
			symbol: 'SNDS',
			name: 'YieldShares High Income ETF',
			exchange: null,
			price: 21.83,
			changePercentage: -1.7865,
			change: -0.39,
			volume: 18128,
			dayLow: 21.83,
			dayHigh: 22.43,
			yearHigh: 28.83,
			yearLow: 19.65,
			marketCap: 0,
			priceAvg50: 23.34,
			priceAvg200: 22.82,
			open: 22.22,
			previousClose: 21.83,
			timestamp: 1561759658,
		))->toArray(), $quote->toArray());
	}

	public function testQuoteNotFound(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->quote('UNKNOWN'));
	}

	public function testQuoteWithFractionalMarketCap(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/quote-goog.json');

		$quote = $client->quote('GOOG');

		$this->assertNotNull($quote);
		// marketCap is 4216133870848.9995 in the response
		$this->assertSame((new Quote(
			symbol: 'GOOG',
			name: 'Alphabet Inc.',
			exchange: 'NASDAQ',
			price: 347.41,
			changePercentage: -0.98612,
			change: -3.46,
			volume: 19629631,
			dayLow: 345.74,
			dayHigh: 359.98,
			yearHigh: 404.47,
			yearLow: 236.685,
			marketCap: 4216133870849,
			priceAvg50: 343.394,
			priceAvg200: 336.35294,
			open: 353.5,
			previousClose: 350.87,
			timestamp: 1790107201,
		))->toArray(), $quote->toArray());
	}

}
