<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EtfHolding
{

	/**
	 * @param non-empty-string $symbol Ticker symbol of the fund
	 * @param string $asset Ticker symbol of the held asset, empty for assets without one, e.g. bonds or cash
	 * @param string $isin May be empty
	 * @param string $securityCusip May be empty
	 * @param float $sharesNumber Number of shares held, the principal amount for bonds
	 * @param float $weightPercentage Share of the fund in percent
	 * @param float $marketValue In the currency of the fund
	 * @param non-empty-string $updatedAt In the Y-m-d H:i:s format
	 */
	public function __construct(
		public string $symbol,
		public string $asset,
		public string $name,
		public string $isin,
		public string $securityCusip,
		public float $sharesNumber,
		public float $weightPercentage,
		public float $marketValue,
		public string $updatedAt,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     asset: string,
	 *     name: string,
	 *     isin: string,
	 *     securityCusip: string,
	 *     sharesNumber: float,
	 *     weightPercentage: float,
	 *     marketValue: float,
	 *     updatedAt: non-empty-string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'asset' => $this->asset,
			'name' => $this->name,
			'isin' => $this->isin,
			'securityCusip' => $this->securityCusip,
			'sharesNumber' => $this->sharesNumber,
			'weightPercentage' => $this->weightPercentage,
			'marketValue' => $this->marketValue,
			'updatedAt' => $this->updatedAt,
		];
	}

}
