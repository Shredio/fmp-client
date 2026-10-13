<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * A single change of the composition of an index: a company added, removed, or one replacing another.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class HistoricalIndexConstituent
{

	/**
	 * @param string $dateAdded Date of the change in words, for example "October 06, 2026"
	 * @param string|null $addedSecurity Name of the added company, empty or null for a removal
	 * @param string $removedTicker Empty for an addition
	 * @param string $removedSecurity Name of the removed company, empty for an addition
	 * @param non-empty-string $date Date of the change in the Y-m-d format
	 * @param non-empty-string $symbol The added ticker, the removed one for a removal
	 * @param string $reason May be empty
	 * @param non-empty-string $changeType addition, removal or replacement
	 * @param string $addedTicker Empty for a removal
	 */
	public function __construct(
		public string $dateAdded,
		public ?string $addedSecurity,
		public string $removedTicker,
		public string $removedSecurity,
		public string $date,
		public string $symbol,
		public string $reason,
		public string $changeType,
		public string $addedTicker,
	)
	{
	}

	/**
	 * @return array{
	 *     dateAdded: string,
	 *     addedSecurity: string|null,
	 *     removedTicker: string,
	 *     removedSecurity: string,
	 *     date: non-empty-string,
	 *     symbol: non-empty-string,
	 *     reason: string,
	 *     changeType: non-empty-string,
	 *     addedTicker: string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'dateAdded' => $this->dateAdded,
			'addedSecurity' => $this->addedSecurity,
			'removedTicker' => $this->removedTicker,
			'removedSecurity' => $this->removedSecurity,
			'date' => $this->date,
			'symbol' => $this->symbol,
			'reason' => $this->reason,
			'changeType' => $this->changeType,
			'addedTicker' => $this->addedTicker,
		];
	}

}
