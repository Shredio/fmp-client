<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\NotANumber;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * The bulk endpoint returns the same positions as the per-symbol one (see EtfHolding), but it names the CUSIP and
 * the date of the last update differently and sends the date without the time.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EtfHoldingBulk
{

	/**
	 * @param non-empty-string $symbol Ticker symbol of the fund
	 * @param string $asset Ticker symbol of the held asset, empty for assets without one, e.g. bonds or cash
	 * @param string $name May be empty
	 * @param string $isin May be empty
	 * @param string $cusip May be empty
	 * @param float|null $sharesNumber Number of shares held, the principal amount for bonds, null when the API does not know it
	 * @param float|null $weightPercentage Share of the fund in percent, null when the API does not know it
	 * @param float|null $marketValue In the currency of the fund, null when the API does not know it
	 * @param non-empty-string $lastUpdated In the Y-m-d format
	 */
	public function __construct(
		public string $symbol,
		public string $asset,
		public string $name,
		public string $isin,
		public string $cusip,
		#[CompilePropertyOptions(before: [NotANumber::class, 'toNull'])]
		public ?float $sharesNumber,
		#[CompilePropertyOptions(before: [NotANumber::class, 'toNull'])]
		public ?float $weightPercentage,
		#[CompilePropertyOptions(before: [NotANumber::class, 'toNull'])]
		public ?float $marketValue,
		public string $lastUpdated,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     asset: string,
	 *     name: string,
	 *     isin: string,
	 *     cusip: string,
	 *     sharesNumber: float|null,
	 *     weightPercentage: float|null,
	 *     marketValue: float|null,
	 *     lastUpdated: non-empty-string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'asset' => $this->asset,
			'name' => $this->name,
			'isin' => $this->isin,
			'cusip' => $this->cusip,
			'sharesNumber' => $this->sharesNumber,
			'weightPercentage' => $this->weightPercentage,
			'marketValue' => $this->marketValue,
			'lastUpdated' => $this->lastUpdated,
		];
	}

}
