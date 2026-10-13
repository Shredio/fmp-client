<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\MoneyAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class IpoCalendarItem
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date In the Y-m-d format
	 * @param non-empty-string $daa The date as an ISO 8601 timestamp in UTC, it may fall on the day after $date
	 * @param string $actions Expected, Priced, Filed or Amended, older records use lower case
	 * @param int|null $shares Number of shares offered
	 * @param string|null $priceRange For example "17.00 - 19.00", a single price for a priced IPO
	 * @param int|null $dealSize In the currency of the listing
	 * @param int|null $marketCap In the currency of the listing
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public string $daa,
		public string $company,
		public ?string $exchange,
		public string $actions,
		public ?int $shares,
		public ?string $priceRange,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public ?int $dealSize,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public ?int $marketCap,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     date: non-empty-string,
	 *     daa: non-empty-string,
	 *     company: string,
	 *     exchange: string|null,
	 *     actions: string,
	 *     shares: int|null,
	 *     priceRange: string|null,
	 *     dealSize: int|null,
	 *     marketCap: int|null
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'daa' => $this->daa,
			'company' => $this->company,
			'exchange' => $this->exchange,
			'actions' => $this->actions,
			'shares' => $this->shares,
			'priceRange' => $this->priceRange,
			'dealSize' => $this->dealSize,
			'marketCap' => $this->marketCap,
		];
	}

}
