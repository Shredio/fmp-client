<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * Rating computed by FMP from the fundamentals of the company, it is not an analyst rating.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class Rating
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 * @param non-empty-string $rating Letter grade from S+ (best) through A, B and C down to D, with an optional + or -
	 * @param int $discountedCashFlowScore From 1 (worst) to 5 (best), as all the scores
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public string $rating,
		public int $discountedCashFlowScore,
		public int $returnOnEquityScore,
		public int $returnOnAssetsScore,
		public int $debtToEquityScore,
		public int $priceToEarningsScore,
		public int $priceToBookScore,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     date: non-empty-string,
	 *     rating: non-empty-string,
	 *     discountedCashFlowScore: int,
	 *     returnOnEquityScore: int,
	 *     returnOnAssetsScore: int,
	 *     debtToEquityScore: int,
	 *     priceToEarningsScore: int,
	 *     priceToBookScore: int
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'rating' => $this->rating,
			'discountedCashFlowScore' => $this->discountedCashFlowScore,
			'returnOnEquityScore' => $this->returnOnEquityScore,
			'returnOnAssetsScore' => $this->returnOnAssetsScore,
			'debtToEquityScore' => $this->debtToEquityScore,
			'priceToEarningsScore' => $this->priceToEarningsScore,
			'priceToBookScore' => $this->priceToBookScore,
		];
	}

}
