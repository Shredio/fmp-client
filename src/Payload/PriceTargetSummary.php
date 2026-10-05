<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class PriceTargetSummary
{

	/**
	 * @param non-empty-string $symbol
	 * @param float $lastMonthAvgPriceTarget 0 when no price target was published in the last month
	 * @param float $lastQuarterAvgPriceTarget 0 when no price target was published in the last quarter
	 * @param float $lastYearAvgPriceTarget 0 when no price target was published in the last year
	 * @param list<non-empty-string> $publishers
	 */
	public function __construct(
		public string $symbol,
		public int $lastMonthCount,
		public float $lastMonthAvgPriceTarget,
		public int $lastQuarterCount,
		public float $lastQuarterAvgPriceTarget,
		public int $lastYearCount,
		public float $lastYearAvgPriceTarget,
		public int $allTimeCount,
		public float $allTimeAvgPriceTarget,
		#[CompilePropertyOptions(before: [self::class, 'decodePublishers'])]
		public array $publishers,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     lastMonthCount: int,
	 *     lastMonthAvgPriceTarget: float,
	 *     lastQuarterCount: int,
	 *     lastQuarterAvgPriceTarget: float,
	 *     lastYearCount: int,
	 *     lastYearAvgPriceTarget: float,
	 *     allTimeCount: int,
	 *     allTimeAvgPriceTarget: float,
	 *     publishers: list<non-empty-string>
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'lastMonthCount' => $this->lastMonthCount,
			'lastMonthAvgPriceTarget' => $this->lastMonthAvgPriceTarget,
			'lastQuarterCount' => $this->lastQuarterCount,
			'lastQuarterAvgPriceTarget' => $this->lastQuarterAvgPriceTarget,
			'lastYearCount' => $this->lastYearCount,
			'lastYearAvgPriceTarget' => $this->lastYearAvgPriceTarget,
			'allTimeCount' => $this->allTimeCount,
			'allTimeAvgPriceTarget' => $this->allTimeAvgPriceTarget,
			'publishers' => $this->publishers,
		];
	}

	/**
	 * The API returns the publishers as a JSON document wrapped in a string, both in the JSON and in the CSV
	 * response (e.g. "[\"StreetInsider\",\"Benzinga\"]"), so it is decoded before the list is validated.
	 */
	public static function decodePublishers(mixed $value, TypeContext $context): mixed
	{
		if (!is_string($value)) {
			return $value;
		}

		$decoded = json_decode($value, true);

		return is_array($decoded) ? $decoded : $value;
	}

}
