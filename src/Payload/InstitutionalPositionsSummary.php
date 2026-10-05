<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\MoneyAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * The properties prefixed with "last" hold the value of the previous quarter, the ones suffixed with "Change"
 * the difference between the two quarters.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class InstitutionalPositionsSummary
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date Last day of the reported quarter
	 * @param float $ownershipPercentChange Difference between the two quarters in percentage points
	 * @param float $putCallRatio Shares under put options divided by shares under call options
	 * @param float $putCallRatioChange Difference between the two put/call ratios multiplied by 100
	 */
	public function __construct(
		public string $symbol,
		public string $cik,
		public string $date,
		public int $investorsHolding,
		public int $lastInvestorsHolding,
		public int $investorsHoldingChange,
		public int $numberOf13Fshares,
		public int $lastNumberOf13Fshares,
		public int $numberOf13FsharesChange,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $totalInvested,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $lastTotalInvested,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $totalInvestedChange,
		public float $ownershipPercent,
		public float $lastOwnershipPercent,
		public float $ownershipPercentChange,
		public int $newPositions,
		public int $lastNewPositions,
		public int $newPositionsChange,
		public int $increasedPositions,
		public int $lastIncreasedPositions,
		public int $increasedPositionsChange,
		public int $closedPositions,
		public int $lastClosedPositions,
		public int $closedPositionsChange,
		public int $reducedPositions,
		public int $lastReducedPositions,
		public int $reducedPositionsChange,
		public int $totalCalls,
		public int $lastTotalCalls,
		public int $totalCallsChange,
		public int $totalPuts,
		public int $lastTotalPuts,
		public int $totalPutsChange,
		public float $putCallRatio,
		public float $lastPutCallRatio,
		public float $putCallRatioChange,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     cik: string,
	 *     date: non-empty-string,
	 *     investorsHolding: int,
	 *     lastInvestorsHolding: int,
	 *     investorsHoldingChange: int,
	 *     numberOf13Fshares: int,
	 *     lastNumberOf13Fshares: int,
	 *     numberOf13FsharesChange: int,
	 *     totalInvested: int,
	 *     lastTotalInvested: int,
	 *     totalInvestedChange: int,
	 *     ownershipPercent: float,
	 *     lastOwnershipPercent: float,
	 *     ownershipPercentChange: float,
	 *     newPositions: int,
	 *     lastNewPositions: int,
	 *     newPositionsChange: int,
	 *     increasedPositions: int,
	 *     lastIncreasedPositions: int,
	 *     increasedPositionsChange: int,
	 *     closedPositions: int,
	 *     lastClosedPositions: int,
	 *     closedPositionsChange: int,
	 *     reducedPositions: int,
	 *     lastReducedPositions: int,
	 *     reducedPositionsChange: int,
	 *     totalCalls: int,
	 *     lastTotalCalls: int,
	 *     totalCallsChange: int,
	 *     totalPuts: int,
	 *     lastTotalPuts: int,
	 *     totalPutsChange: int,
	 *     putCallRatio: float,
	 *     lastPutCallRatio: float,
	 *     putCallRatioChange: float
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'cik' => $this->cik,
			'date' => $this->date,
			'investorsHolding' => $this->investorsHolding,
			'lastInvestorsHolding' => $this->lastInvestorsHolding,
			'investorsHoldingChange' => $this->investorsHoldingChange,
			'numberOf13Fshares' => $this->numberOf13Fshares,
			'lastNumberOf13Fshares' => $this->lastNumberOf13Fshares,
			'numberOf13FsharesChange' => $this->numberOf13FsharesChange,
			'totalInvested' => $this->totalInvested,
			'lastTotalInvested' => $this->lastTotalInvested,
			'totalInvestedChange' => $this->totalInvestedChange,
			'ownershipPercent' => $this->ownershipPercent,
			'lastOwnershipPercent' => $this->lastOwnershipPercent,
			'ownershipPercentChange' => $this->ownershipPercentChange,
			'newPositions' => $this->newPositions,
			'lastNewPositions' => $this->lastNewPositions,
			'newPositionsChange' => $this->newPositionsChange,
			'increasedPositions' => $this->increasedPositions,
			'lastIncreasedPositions' => $this->lastIncreasedPositions,
			'increasedPositionsChange' => $this->increasedPositionsChange,
			'closedPositions' => $this->closedPositions,
			'lastClosedPositions' => $this->lastClosedPositions,
			'closedPositionsChange' => $this->closedPositionsChange,
			'reducedPositions' => $this->reducedPositions,
			'lastReducedPositions' => $this->lastReducedPositions,
			'reducedPositionsChange' => $this->reducedPositionsChange,
			'totalCalls' => $this->totalCalls,
			'lastTotalCalls' => $this->lastTotalCalls,
			'totalCallsChange' => $this->totalCallsChange,
			'totalPuts' => $this->totalPuts,
			'lastTotalPuts' => $this->lastTotalPuts,
			'totalPutsChange' => $this->totalPutsChange,
			'putCallRatio' => $this->putCallRatio,
			'lastPutCallRatio' => $this->lastPutCallRatio,
			'putCallRatioChange' => $this->putCallRatioChange,
		];
	}

}
