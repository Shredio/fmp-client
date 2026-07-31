<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\RevenueGeographicSegmentation;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class RevenueGeographicSegmentationTest extends TestCase
{

	public function testRevenueGeographicSegmentation(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/revenue-geographic-segmentation-aapl.json');
		$mockClient = new MockHttpClient([$response]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$segments = iterator_to_array($client->revenueGeographicSegmentation('AAPL'));

		$this->assertCount(16, $segments);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/revenue-geographic-segmentation?symbol=AAPL&period=annual&apikey=SECRET',
			$response->getRequestUrl(),
		);

		$this->assertSame((new RevenueGeographicSegmentation(
			symbol: 'AAPL',
			fiscalYear: 2025,
			period: Period::FY,
			reportedCurrency: 'USD',
			date: '2025-09-27',
			data: [
				'Americas Segment' => 178353000000.0,
				'Europe Segment' => 111032000000.0,
				'Greater China Segment' => 64377000000.0,
				'Japan Segment' => 28703000000.0,
				'Rest of Asia Pacific Segment' => 33696000000.0,
			],
		))->toArray(), $segments[0]->toArray());

		$this->assertSame((new RevenueGeographicSegmentation(
			symbol: 'AAPL',
			fiscalYear: 2010,
			period: Period::FY,
			reportedCurrency: 'USD',
			date: '2010-09-25',
			data: [
				'Americas Segment' => 24498000000.0,
				'Europe Segment' => 18692000000.0,
				'Japan Segment' => 3981000000.0,
				'Rest of Asia Pacific Segment' => 8256000000.0,
				'Other Countries' => 9798000000.0,
			],
		))->toArray(), $segments[15]->toArray());
	}

	public function testRevenueGeographicSegmentationEmptyResponse(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/revenue-geographic-segmentation-empty.json');

		$this->assertSame([], iterator_to_array($client->revenueGeographicSegmentation('BABA')));
	}

}
