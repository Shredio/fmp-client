<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class Scores
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $reportedCurrency,
		public float|null $altmanZScore = null,
		public int|null $piotroskiScore = null,
		public int|float|null $workingCapital = null,
		public int|float|null $totalAssets = null,
		public int|float|null $retainedEarnings = null,
		public int|float|null $ebit = null,
		public int|float|null $marketCap = null,
		public int|float|null $totalLiabilities = null,
		public int|float|null $revenue = null,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     reportedCurrency: string,
	 *     altmanZScore: float|null,
	 *     piotroskiScore: int|null,
	 *     workingCapital: int|float|null,
	 *     totalAssets: int|float|null,
	 *     retainedEarnings: int|float|null,
	 *     ebit: int|float|null,
	 *     marketCap: int|float|null,
	 *     totalLiabilities: int|float|null,
	 *     revenue: int|float|null
	 * }
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
