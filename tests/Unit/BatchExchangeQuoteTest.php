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

	/**
	 * On 2026-11-11 AMEX, NYSE and NASDAQ each carried one record last quoted in 2019 with a null exchange (SNDS on
	 * AMEX). The fixture keeps that record and one regular record of the same response.
	 */
	public function testBatchExchangeQuoteDetailedWithoutExchange(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/batch-exchange-quote-detailed-without-exchange.json');

		$quotes = iterator_to_array($client->batchExchangeQuoteDetailed('AMEX'));

		$this->assertSame([
			(new BatchExchangeDetailedQuote(
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
			))->toArray(),
			(new BatchExchangeDetailedQuote(
				symbol: 'SPY',
				name: 'State Street SPDR S&P 500 ETF',
				exchange: 'AMEX',
				price: 760.89,
				changePercentage: 1.46282,
				change: 10.97,
				volume: 13407552,
				dayLow: 754.91,
				dayHigh: 761.15002,
				yearHigh: 781.62,
				yearLow: 629.28,
				marketCap: 800514406670,
				priceAvg50: 765.004,
				priceAvg200: 730.9858,
				open: 756.8,
				previousClose: 749.92,
				timestamp: 1794417913,
			))->toArray(),
		], array_map(static fn (BatchExchangeDetailedQuote $quote): array => $quote->toArray(), $quotes));
	}

	/**
	 * On 2026-11-11 BSE, MCX and OTC sent the timestamp of some records with milliseconds as a float (KZMS.ME on
	 * MCX). The fixture keeps that record and one regular record of the same response.
	 */
	public function testBatchExchangeQuoteDetailedWithFractionalTimestamp(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/batch-exchange-quote-detailed-fractional-timestamp.json');

		$quotes = iterator_to_array($client->batchExchangeQuoteDetailed('MCX'));

		$this->assertSame([
			(new BatchExchangeDetailedQuote(
				symbol: 'KZMS.ME',
				name: 'The Open Joint Stock Company Krasnokamsk Metal Mesh Works',
				exchange: 'MCX',
				price: 226.0,
				changePercentage: 0.0,
				change: 0.0,
				volume: 0,
				dayLow: 226.0,
				dayHigh: 226.0,
				yearHigh: 1552.0,
				yearLow: 226.0,
				marketCap: 199955760,
				priceAvg50: 408.68,
				priceAvg200: 412.78,
				open: 226.0,
				previousClose: 226.0,
				timestamp: 1794420487,
			))->toArray(),
			(new BatchExchangeDetailedQuote(
				symbol: 'ABRD.ME',
				name: 'Public Joint Stock Company Abrau-Durso',
				exchange: 'MCX',
				price: 129.4,
				changePercentage: 1.41066,
				change: 1.8,
				volume: 30,
				dayLow: 126.0,
				dayHigh: 131.0,
				yearHigh: 187.8,
				yearLow: 98.2,
				marketCap: 12681225880,
				priceAvg50: 179.0,
				priceAvg200: 179.0,
				open: 127.6,
				previousClose: 127.6,
				timestamp: 1794419512,
			))->toArray(),
		], array_map(static fn (BatchExchangeDetailedQuote $quote): array => $quote->toArray(), $quotes));
	}

}