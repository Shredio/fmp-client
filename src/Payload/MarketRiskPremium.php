<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type MarketRiskPremiumArray array{country: non-empty-string, continent: non-empty-string, countryRiskPremium: float, totalEquityRiskPremium: float}
 */
#[CompileObjectMapper(identifier: 'country')]
final readonly class MarketRiskPremium
{
	/**
	 * @param non-empty-string $country (United States, Canada, Germany, etc.)
	 * @param non-empty-string $continent (Africa, Asia, Europe, etc.)
	 */
	public function __construct(
		public string $country,
		public string $continent,
		public float $countryRiskPremium,
		public float $totalEquityRiskPremium,
	) {}

	/**
	 * @return array{country: non-empty-string, continent: non-empty-string, countryRiskPremium: float, totalEquityRiskPremium: float}
	 */
	public function toArray(): array
	{
		return [
			'country' => $this->country,
			'continent' => $this->continent,
			'countryRiskPremium' => $this->countryRiskPremium,
			'totalEquityRiskPremium' => $this->totalEquityRiskPremium,
		];
	}
}
