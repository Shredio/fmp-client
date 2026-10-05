<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Exception\UnexpectedResponseContentException;
use Shredio\FmpClient\Payload\PriceTargetSummary;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class PriceTargetSummaryTest extends TestCase
{

	public function testPriceTargetSummary(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/price-target-summary-aapl.json');

		$summary = $client->priceTargetSummary('AAPL');

		$this->assertNotNull($summary);
		$this->assertSame((new PriceTargetSummary(
			symbol: 'AAPL',
			lastMonthCount: 4,
			lastMonthAvgPriceTarget: 354.75,
			lastQuarterCount: 17,
			lastQuarterAvgPriceTarget: 335.74,
			lastYearCount: 65,
			lastYearAvgPriceTarget: 317.68,
			allTimeCount: 263,
			allTimeAvgPriceTarget: 234.17,
			publishers: ['StreetInsider', 'Benzinga', 'Pulse 2.0', 'MarketWatch', 'Investing', 'Barrons', 'Investor\'s Business Daily'],
		))->toArray(), $summary->toArray());
	}

	public function testSymbolWithoutCoverage(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->priceTargetSummary('CEZ.PR'));
	}

	public function testPriceTargetSummaryBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/price-target-summary-bulk.csv');

		$summaries = iterator_to_array($client->priceTargetSummaryBulk());

		$this->assertNotEmpty($summaries);
		$this->assertSame((new PriceTargetSummary(
			symbol: 'A',
			lastMonthCount: 1,
			lastMonthAvgPriceTarget: 165.0,
			lastQuarterCount: 11,
			lastQuarterAvgPriceTarget: 172.27,
			lastYearCount: 31,
			lastYearAvgPriceTarget: 169.42,
			allTimeCount: 53,
			allTimeAvgPriceTarget: 159.49,
			publishers: ['StreetInsider', 'Benzinga', 'Pulse 2.0'],
		))->toArray(), $summaries[0]->toArray());
	}

	public function testPriceTargetSummaryBulkWithoutRecentTargetsAndPublishers(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/price-target-summary-bulk.csv');

		$summaries = iterator_to_array($client->priceTargetSummaryBulk());

		$this->assertSame((new PriceTargetSummary(
			symbol: 'AADI',
			lastMonthCount: 0,
			lastMonthAvgPriceTarget: 0.0,
			lastQuarterCount: 0,
			lastQuarterAvgPriceTarget: 0.0,
			lastYearCount: 0,
			lastYearAvgPriceTarget: 0.0,
			allTimeCount: 2,
			allTimeAvgPriceTarget: 1.63,
			publishers: [],
		))->toArray(), $summaries[2]->toArray());
	}

	public function testPublishersThatAreNotJsonListAreRejected(): void
	{
		$client = new SymfonyFmpClient(new MockHttpClient([
			new MockResponse('[{"symbol":"AAPL","lastMonthCount":4,"lastMonthAvgPriceTarget":354.75,"lastQuarterCount":17,"lastQuarterAvgPriceTarget":335.74,"lastYearCount":65,"lastYearAvgPriceTarget":317.68,"allTimeCount":263,"allTimeAvgPriceTarget":234.17,"publishers":"StreetInsider"}]'),
		]), 'SECRET', strictMode: true);

		$this->expectException(UnexpectedResponseContentException::class);
		$this->expectExceptionMessage('at publishers');

		$client->priceTargetSummary('AAPL');
	}

}
