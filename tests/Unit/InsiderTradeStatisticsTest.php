<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\InsiderTradeStatistics;
use Tests\TestCase;

final class InsiderTradeStatisticsTest extends TestCase
{

	public function testInsiderTradeStatistics(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/insider-trading-statistics-aapl.json');

		$statistics = iterator_to_array($client->insiderTradeStatistics('AAPL'));

		$this->assertCount(94, $statistics);
		$this->assertSame((new InsiderTradeStatistics(
			symbol: 'AAPL',
			cik: '0000320193',
			year: 2026,
			quarter: 3,
			acquiredTransactions: 9,
			disposedTransactions: 10,
			acquiredDisposedRatio: 0.9,
			totalAcquired: 455601,
			totalDisposed: 59762,
			averageAcquired: 50622.3333,
			averageDisposed: 5976.2,
			totalPurchases: 0,
			totalSales: 8,
		))->toArray(), $statistics[0]->toArray());
	}

	public function testQuarterWithoutDisposition(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/insider-trading-statistics-aapl.json');

		$statistics = iterator_to_array($client->insiderTradeStatistics('AAPL'));

		$this->assertSame((new InsiderTradeStatistics(
			symbol: 'AAPL',
			cik: '0000320193',
			year: 2003,
			quarter: 2,
			acquiredTransactions: 1,
			disposedTransactions: 0,
			acquiredDisposedRatio: 0.0,
			totalAcquired: 10000,
			totalDisposed: 0,
			averageAcquired: 10000.0,
			averageDisposed: 0.0,
			totalPurchases: 0,
			totalSales: 0,
		))->toArray(), $statistics[93]->toArray());
	}

	public function testFractionalTotals(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/insider-trading-statistics-msft.json');

		$statistics = iterator_to_array($client->insiderTradeStatistics('MSFT'));

		$this->assertCount(108, $statistics);
		$this->assertSame((new InsiderTradeStatistics(
			symbol: 'MSFT',
			cik: '0000789019',
			year: 2026,
			quarter: 3,
			acquiredTransactions: 33,
			disposedTransactions: 28,
			acquiredDisposedRatio: 1.1786,
			totalAcquired: 393000.6620000001,
			totalDisposed: 290450.991,
			averageAcquired: 11909.111,
			averageDisposed: 10373.2497,
			totalPurchases: 0,
			totalSales: 16,
		))->toArray(), $statistics[0]->toArray());
	}

	public function testSymbolWithoutInsiderFilings(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->insiderTradeStatistics('CEZ.PR')));
	}

}
