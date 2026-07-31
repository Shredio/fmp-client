<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
use Shredio\FmpClient\Exception\UnexpectedResponseContentException;
use Shredio\FmpClient\Payload\RevenueProductSegmentation;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class RevenueProductSegmentationTest extends TestCase
{

	public function testRevenueProductSegmentation(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/revenue-product-segmentation-aapl.json');
		$mockClient = new MockHttpClient([$response]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$segments = iterator_to_array($client->revenueProductSegmentation('AAPL'));

		$this->assertCount(16, $segments);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/revenue-product-segmentation?symbol=AAPL&period=annual&apikey=SECRET',
			$response->getRequestUrl(),
		);

		$this->assertSame((new RevenueProductSegmentation(
			symbol: 'AAPL',
			fiscalYear: 2025,
			period: Period::FY,
			reportedCurrency: 'USD',
			date: '2025-09-27',
			data: [
				'Mac' => 33708000000.0,
				'Service' => 109158000000.0,
				'Wearables, Home and Accessories' => 35686000000.0,
				'iPad' => 28023000000.0,
				'iPhone' => 209586000000.0,
			],
		))->toArray(), $segments[0]->toArray());

		$this->assertSame((new RevenueProductSegmentation(
			symbol: 'AAPL',
			fiscalYear: 2010,
			period: Period::FY,
			reportedCurrency: 'USD',
			date: '2010-09-25',
			data: ['Retail' => 9798000000.0],
		))->toArray(), $segments[15]->toArray());
	}

	public function testRevenueProductSegmentationQuarter(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/revenue-product-segmentation-aapl-quarter.json');
		$mockClient = new MockHttpClient([$response]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$segments = iterator_to_array($client->revenueProductSegmentation('AAPL', PeriodQuery::Quarter));

		$this->assertCount(46, $segments);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/revenue-product-segmentation?symbol=AAPL&period=quarter&apikey=SECRET',
			$response->getRequestUrl(),
		);

		$this->assertSame((new RevenueProductSegmentation(
			symbol: 'AAPL',
			fiscalYear: 2026,
			period: Period::Q3,
			reportedCurrency: 'USD',
			date: '2026-06-27',
			data: [
				'Mac' => 10352000000.0,
				'Service' => 30739000000.0,
				'Wearables, Home and Accessories' => 7883000000.0,
				'iPad' => 6191000000.0,
				'iPhone' => 54252000000.0,
			],
		))->toArray(), $segments[0]->toArray());
	}

	public function testRevenueProductSegmentationWithFractionalValues(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/revenue-product-segmentation-asml.json');

		$segments = iterator_to_array($client->revenueProductSegmentation('ASML'));

		$this->assertCount(15, $segments);
		$this->assertSame('EUR', $segments[0]->reportedCurrency);
		$this->assertSame(513700000.00000006, $segments[4]->data['Metrology and inspection']);
	}

	public function testRevenueProductSegmentationInvalidData(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/revenue-product-segmentation-invalid.json');

		$this->expectException(UnexpectedResponseContentException::class);

		iterator_to_array($client->revenueProductSegmentation('AAPL'));
	}

}
