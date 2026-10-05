<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class GradesHistorical
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date First day of the month the snapshot belongs to
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public int $analystRatingsStrongBuy,
		public int $analystRatingsBuy,
		public int $analystRatingsHold,
		public int $analystRatingsSell,
		public int $analystRatingsStrongSell,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     date: non-empty-string,
	 *     analystRatingsStrongBuy: int,
	 *     analystRatingsBuy: int,
	 *     analystRatingsHold: int,
	 *     analystRatingsSell: int,
	 *     analystRatingsStrongSell: int
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'analystRatingsStrongBuy' => $this->analystRatingsStrongBuy,
			'analystRatingsBuy' => $this->analystRatingsBuy,
			'analystRatingsHold' => $this->analystRatingsHold,
			'analystRatingsSell' => $this->analystRatingsSell,
			'analystRatingsStrongSell' => $this->analystRatingsStrongSell,
		];
	}

}
