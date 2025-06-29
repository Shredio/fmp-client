<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class Scores
{

	public function __construct(
		public string $symbol,
		public string $reportedCurrency,
		public float|null $altmanZScore = null,
		public int|null $piotroskiScore = null,
		public int|null $workingCapital = null,
		public int|null $totalAssets = null,
		public int|null $retainedEarnings = null,
		public int|null $ebit = null,
		public int|null $marketCap = null,
		public int|null $totalLiabilities = null,
		public int|null $revenue = null,
	)
	{
	}

	/**
	 * @return array{symbol: string, reportedCurrency: string, altmanZScore: float|null, piotroskiScore: int|null, workingCapital: int|null, totalAssets: int|null, retainedEarnings: int|null, ebit: int|null, marketCap: int|null, totalLiabilities: int|null, revenue: int|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'reportedCurrency' => $this->reportedCurrency,
			'altmanZScore' => $this->altmanZScore,
			'piotroskiScore' => $this->piotroskiScore,
			'workingCapital' => $this->workingCapital,
			'totalAssets' => $this->totalAssets,
			'retainedEarnings' => $this->retainedEarnings,
			'ebit' => $this->ebit,
			'marketCap' => $this->marketCap,
			'totalLiabilities' => $this->totalLiabilities,
			'revenue' => $this->revenue,
		];
	}

}