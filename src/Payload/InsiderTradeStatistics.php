<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class InsiderTradeStatistics
{

	/**
	 * @param non-empty-string $symbol
	 * @param int<1, 4> $quarter Calendar quarter of the transaction date
	 * @param float $acquiredDisposedRatio Acquisitions divided by dispositions, 0 when the quarter has no disposition
	 * @param int|float $totalAcquired Number of securities, fractional for some filings
	 * @param int|float $totalDisposed Number of securities, fractional for some filings
	 */
	public function __construct(
		public string $symbol,
		public string $cik,
		public int $year,
		public int $quarter,
		public int $acquiredTransactions,
		public int $disposedTransactions,
		public float $acquiredDisposedRatio,
		public int|float $totalAcquired,
		public int|float $totalDisposed,
		public float $averageAcquired,
		public float $averageDisposed,
		public int $totalPurchases,
		public int $totalSales,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     cik: string,
	 *     year: int,
	 *     quarter: int<1, 4>,
	 *     acquiredTransactions: int,
	 *     disposedTransactions: int,
	 *     acquiredDisposedRatio: float,
	 *     totalAcquired: int|float,
	 *     totalDisposed: int|float,
	 *     averageAcquired: float,
	 *     averageDisposed: float,
	 *     totalPurchases: int,
	 *     totalSales: int
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'cik' => $this->cik,
			'year' => $this->year,
			'quarter' => $this->quarter,
			'acquiredTransactions' => $this->acquiredTransactions,
			'disposedTransactions' => $this->disposedTransactions,
			'acquiredDisposedRatio' => $this->acquiredDisposedRatio,
			'totalAcquired' => $this->totalAcquired,
			'totalDisposed' => $this->totalDisposed,
			'averageAcquired' => $this->averageAcquired,
			'averageDisposed' => $this->averageDisposed,
			'totalPurchases' => $this->totalPurchases,
			'totalSales' => $this->totalSales,
		];
	}

}
