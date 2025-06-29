<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\HistoricalPriceEod;
use Tests\TestCase;

final class HistoricalPriceEodTest extends TestCase
{

	public function testHistoricalPriceEod(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-aapl.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEod(
			'AAPL',
			new DateTimeImmutable('2025-01-01'),
			new DateTimeImmutable('2025-01-10')
		));

		$this->assertNotEmpty($historicalPrices);
		$this->assertCount(6, $historicalPrices);

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2025-01-10',
			open: 240.01,
			high: 240.16,
			low: 233.0,
			close: 236.85,
			volume: 61710900,
			change: -3.16,
			changePercent: -1.32,
			vwap: 237.505,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2025-01-08',
			open: 241.92,
			high: 243.71,
			low: 240.05,
			close: 242.7,
			volume: 37628940,
			change: 0.78,
			changePercent: 0.32242,
			vwap: 242.095,
		))->toArray(), $historicalPrices[1]->toArray());

		$this->assertSame((new HistoricalPriceEod(
			symbol: 'AAPL',
			date: '2025-01-02',
			open: 248.93,
			high: 249.1,
			low: 241.82,
			close: 243.85,
			volume: 55740731,
			change: -5.08,
			changePercent: -2.04,
			vwap: 245.925,
		))->toArray(), $historicalPrices[5]->toArray());
	}

}