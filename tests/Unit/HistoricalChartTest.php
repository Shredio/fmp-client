<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Enum\TimeInterval;
use Shredio\FmpClient\Payload\HistoricalChart;
use Tests\TestCase;

final class HistoricalChartTest extends TestCase
{

	public function testHistoricalChart(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/historical-chart-5min.json');

		$historicalCharts = iterator_to_array($client->historicalChart(
			'AAPL',
			TimeInterval::FiveMin,
			new DateTimeImmutable('2025-01-01'),
			new DateTimeImmutable('2025-01-02')
		));

		$this->assertNotEmpty($historicalCharts);
		$this->assertCount(3, $historicalCharts);

		$this->assertSame((new HistoricalChart(
			date: '2025-01-02 15:55:00',
			open: 243.46,
			high: 243.95,
			low: 243.38,
			close: 243.82,
			volume: 2319157,
		))->toArray(), $historicalCharts[0]->toArray());

		$this->assertSame((new HistoricalChart(
			date: '2025-01-02 15:50:00',
			open: 243.51,
			high: 243.78,
			low: 243.32,
			close: 243.46,
			volume: 752087,
		))->toArray(), $historicalCharts[1]->toArray());

		$this->assertSame((new HistoricalChart(
			date: '2025-01-02 15:45:00',
			open: 243.53,
			high: 243.67,
			low: 243.34,
			close: 243.5,
			volume: 517232,
		))->toArray(), $historicalCharts[2]->toArray());
	}

	public function testTimeIntervalValues(): void
	{
		$this->assertSame('1min', TimeInterval::OneMin->value);
		$this->assertSame('5min', TimeInterval::FiveMin->value);
		$this->assertSame('15min', TimeInterval::FifteenMin->value);
		$this->assertSame('30min', TimeInterval::ThirtyMin->value);
		$this->assertSame('1hour', TimeInterval::OneHour->value);
		$this->assertSame('4hour', TimeInterval::FourHour->value);
	}

}