<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\MoneyAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * The properties prefixed with "last" hold the value of the previous quarter, the ones prefixed with "changeIn"
 * the difference between the two quarters.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class InstitutionalHolder
{

	/**
	 * @param non-empty-string $date Last day of the reported quarter
	 * @param non-empty-string $filingDate
	 * @param non-empty-string $symbol
	 * @param string $sharesType SH for shares, PRN for a principal amount
	 * @param string $putCallShare Share for a stock position, PUT or CALL for an option position
	 * @param string $investmentDiscretion SOLE, DFND (shared-defined) or OTR (other)
	 * @param string $industryTitle Empty for securities without an SIC industry, e.g. ETFs
	 * @param float $weight Share of the position in the portfolio of the holder in percent
	 * @param float $ownership Share of the company held by the holder in percent
	 * @param int $holdingPeriod Number of quarters the position has been held
	 * @param non-empty-string $firstAdded Last day of the quarter the position was first reported in
	 */
	public function __construct(
		public string $date,
		public string $cik,
		public string $filingDate,
		public string $investorName,
		public string $symbol,
		public string $securityName,
		public string $typeOfSecurity,
		public string $securityCusip,
		public string $sharesType,
		public string $putCallShare,
		public string $investmentDiscretion,
		public string $industryTitle,
		public float $weight,
		public float $lastWeight,
		public float $changeInWeight,
		public float $changeInWeightPercentage,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $marketValue,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $lastMarketValue,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $changeInMarketValue,
		public float $changeInMarketValuePercentage,
		public int $sharesNumber,
		public int $lastSharesNumber,
		public int $changeInSharesNumber,
		public float $changeInSharesNumberPercentage,
		public float $quarterEndPrice,
		public float $avgPricePaid,
		public bool $isNew,
		public bool $isSoldOut,
		public float $ownership,
		public float $lastOwnership,
		public float $changeInOwnership,
		public float $changeInOwnershipPercentage,
		public int $holdingPeriod,
		public string $firstAdded,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $performance,
		public float $performancePercentage,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $lastPerformance,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $changeInPerformance,
		public bool $isCountedForPerformance,
	)
	{
	}

	/**
	 * @return array{
	 *     date: non-empty-string,
	 *     cik: string,
	 *     filingDate: non-empty-string,
	 *     investorName: string,
	 *     symbol: non-empty-string,
	 *     securityName: string,
	 *     typeOfSecurity: string,
	 *     securityCusip: string,
	 *     sharesType: string,
	 *     putCallShare: string,
	 *     investmentDiscretion: string,
	 *     industryTitle: string,
	 *     weight: float,
	 *     lastWeight: float,
	 *     changeInWeight: float,
	 *     changeInWeightPercentage: float,
	 *     marketValue: int,
	 *     lastMarketValue: int,
	 *     changeInMarketValue: int,
	 *     changeInMarketValuePercentage: float,
	 *     sharesNumber: int,
	 *     lastSharesNumber: int,
	 *     changeInSharesNumber: int,
	 *     changeInSharesNumberPercentage: float,
	 *     quarterEndPrice: float,
	 *     avgPricePaid: float,
	 *     isNew: bool,
	 *     isSoldOut: bool,
	 *     ownership: float,
	 *     lastOwnership: float,
	 *     changeInOwnership: float,
	 *     changeInOwnershipPercentage: float,
	 *     holdingPeriod: int,
	 *     firstAdded: non-empty-string,
	 *     performance: int,
	 *     performancePercentage: float,
	 *     lastPerformance: int,
	 *     changeInPerformance: int,
	 *     isCountedForPerformance: bool
	 * }
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'cik' => $this->cik,
			'filingDate' => $this->filingDate,
			'investorName' => $this->investorName,
			'symbol' => $this->symbol,
			'securityName' => $this->securityName,
			'typeOfSecurity' => $this->typeOfSecurity,
			'securityCusip' => $this->securityCusip,
			'sharesType' => $this->sharesType,
			'putCallShare' => $this->putCallShare,
			'investmentDiscretion' => $this->investmentDiscretion,
			'industryTitle' => $this->industryTitle,
			'weight' => $this->weight,
			'lastWeight' => $this->lastWeight,
			'changeInWeight' => $this->changeInWeight,
			'changeInWeightPercentage' => $this->changeInWeightPercentage,
			'marketValue' => $this->marketValue,
			'lastMarketValue' => $this->lastMarketValue,
			'changeInMarketValue' => $this->changeInMarketValue,
			'changeInMarketValuePercentage' => $this->changeInMarketValuePercentage,
			'sharesNumber' => $this->sharesNumber,
			'lastSharesNumber' => $this->lastSharesNumber,
			'changeInSharesNumber' => $this->changeInSharesNumber,
			'changeInSharesNumberPercentage' => $this->changeInSharesNumberPercentage,
			'quarterEndPrice' => $this->quarterEndPrice,
			'avgPricePaid' => $this->avgPricePaid,
			'isNew' => $this->isNew,
			'isSoldOut' => $this->isSoldOut,
			'ownership' => $this->ownership,
			'lastOwnership' => $this->lastOwnership,
			'changeInOwnership' => $this->changeInOwnership,
			'changeInOwnershipPercentage' => $this->changeInOwnershipPercentage,
			'holdingPeriod' => $this->holdingPeriod,
			'firstAdded' => $this->firstAdded,
			'performance' => $this->performance,
			'performancePercentage' => $this->performancePercentage,
			'lastPerformance' => $this->lastPerformance,
			'changeInPerformance' => $this->changeInPerformance,
			'isCountedForPerformance' => $this->isCountedForPerformance,
		];
	}

}
