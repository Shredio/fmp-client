<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\HistoricalPriceEodNonSplitAdjusted;
use Tests\TestCase;

final class HistoricalPriceEodNonSplitAdjustedTest extends TestCase
{

	public function testHistoricalPriceEodNonSplitAdjusted(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-price-eod-non-split-adjusted-aapl.json');

		$historicalPrices = iterator_to_array($client->historicalPriceEodNonSplitAdjusted(
			'AAPL',
			new DateTimeImmutable('2026-05-25'),
			new DateTimeImmutable('2026-06-10')
		));

		$this->assertCount(12, $historicalPrices);

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2026-06-10',
			adjOpen: 290.74,
			adjHigh: 294.75,
			adjLow: 287.38,
			adjClose: 291.58,
			volume: 52793300,
		))->toArray(), $historicalPrices[0]->toArray());

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2026-05-29',
			adjOpen: 311.77,
			adjHigh: 315.0,
			adjLow: 309.53,
			adjClose: 312.06,
			volume: 70026800,
		))->toArray(), $historicalPrices[8]->toArray());

		$this->assertSame((new HistoricalPriceEodNonSplitAdjusted(
			symbol: 'AAPL',
			date: '2026-05-26',
			adjOpen: 309.56,
			adjHigh: 311.82,
			adjLow: 307.67,
			adjClose: 308.33,
			volume: 48000500,
		))->toArray(), $historicalPrices[11]->toArray());
	}

}
