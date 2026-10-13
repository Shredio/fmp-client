<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\MoneyAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * Funds that are no longer traded keep only a part of the record, the rest of it is null.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EtfInfo
{

	/**
	 * @param non-empty-string $symbol
	 * @param string $assetClass For example Equity, Fixed Income, Commodities, may be empty
	 * @param string|null $domicile Two letter country code
	 * @param float|null $expenseRatio In percent, 0.09 stands for 0.09 %
	 * @param int|null $assetsUnderManagement In the currency of the net asset value
	 * @param string|null $inceptionDate In the Y-m-d format
	 * @param float|null $nav Net asset value per share
	 * @param list<EtfSectorExposure> $sectorsList Empty for funds without a sector breakdown, e.g. bond or commodity funds
	 */
	public function __construct(
		public string $symbol,
		public string $name,
		public ?string $description,
		public ?string $isin,
		public string $assetClass,
		public ?string $securityCusip,
		public ?string $domicile,
		public ?string $website,
		public ?string $etfCompany,
		public ?float $expenseRatio,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public ?int $assetsUnderManagement,
		public float $avgVolume,
		public ?string $inceptionDate,
		public ?float $nav,
		public string $navCurrency,
		public ?int $holdingsCount,
		public bool $isActivelyTrading,
		public string $updatedAt,
		public array $sectorsList,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     name: string,
	 *     description: string|null,
	 *     isin: string|null,
	 *     assetClass: string,
	 *     securityCusip: string|null,
	 *     domicile: string|null,
	 *     website: string|null,
	 *     etfCompany: string|null,
	 *     expenseRatio: float|null,
	 *     assetsUnderManagement: int|null,
	 *     avgVolume: float,
	 *     inceptionDate: string|null,
	 *     nav: float|null,
	 *     navCurrency: string,
	 *     holdingsCount: int|null,
	 *     isActivelyTrading: bool,
	 *     updatedAt: string,
	 *     sectorsList: list<array{industry: string, exposure: float}>
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'name' => $this->name,
			'description' => $this->description,
			'isin' => $this->isin,
			'assetClass' => $this->assetClass,
			'securityCusip' => $this->securityCusip,
			'domicile' => $this->domicile,
			'website' => $this->website,
			'etfCompany' => $this->etfCompany,
			'expenseRatio' => $this->expenseRatio,
			'assetsUnderManagement' => $this->assetsUnderManagement,
			'avgVolume' => $this->avgVolume,
			'inceptionDate' => $this->inceptionDate,
			'nav' => $this->nav,
			'navCurrency' => $this->navCurrency,
			'holdingsCount' => $this->holdingsCount,
			'isActivelyTrading' => $this->isActivelyTrading,
			'updatedAt' => $this->updatedAt,
			'sectorsList' => array_map(
				static fn (EtfSectorExposure $exposure): array => $exposure->toArray(),
				$this->sectorsList,
			),
		];
	}

}
