<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class CompanyProfile
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $range
	 * @param non-empty-string|null $companyName
	 * @param non-empty-string|null $currency
	 * @param non-empty-string|null $cik
	 * @param non-empty-string|null $isin
	 * @param non-empty-string|null $cusip
	 * @param non-empty-string|null $exchangeFullName
	 * @param non-empty-string|null $exchange
	 * @param non-empty-string|null $industry
	 * @param non-empty-string|null $website
	 * @param non-empty-string|null $description
	 * @param non-empty-string|null $ceo
	 * @param non-empty-string|null $sector
	 * @param non-empty-string|null $country
	 * @param non-empty-string|null $fullTimeEmployees
	 * @param non-empty-string|null $phone
	 * @param non-empty-string|null $address
	 * @param non-empty-string|null $city
	 * @param non-empty-string|null $state
	 * @param non-empty-string|null $zip
	 * @param non-empty-string|null $image
	 * @param non-empty-string|null $ipoDate
	 */
	public function __construct(
		public string $symbol,
		public ?float $price = null,
		#[CompilePropertyOptions(before: [self::class, 'castDecimalStringToInt'])]
		public ?int $marketCap = null,
		public ?float $beta = null,
		public ?float $lastDividend = null,
		public ?string $range = null,
		public ?float $change = null,
		public ?float $changePercentage = null,
		public int|float|null $volume = null, // float for cryptocurrencies
		public int|float|null $averageVolume = null, // float for cryptocurrencies
		public ?string $companyName = null,
		public ?string $currency = null,
		public ?string $cik = null,
		public ?string $isin = null,
		public ?string $cusip = null,
		public ?string $exchangeFullName = null,
		public ?string $exchange = null,
		public ?string $industry = null,
		public ?string $website = null,
		public ?string $description = null,
		public ?string $ceo = null,
		public ?string $sector = null,
		public ?string $country = null,
		public ?string $fullTimeEmployees = null,
		public ?string $phone = null,
		public ?string $address = null,
		public ?string $city = null,
		public ?string $state = null,
		public ?string $zip = null,
		public ?string $image = null,
		public ?string $ipoDate = null,
		public ?bool $defaultImage = null,
		public ?bool $isEtf = null,
		public ?bool $isActivelyTrading = null,
		public ?bool $isAdr = null,
		public ?bool $isFund = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, price: float|null, marketCap: int|null, beta: float|null, lastDividend: float|null, range: non-empty-string|null, change: float|null, changePercentage: float|null, volume: int|float|null, averageVolume: int|float|null, companyName: non-empty-string|null, currency: non-empty-string|null, cik: non-empty-string|null, isin: non-empty-string|null, cusip: non-empty-string|null, exchangeFullName: non-empty-string|null, exchange: non-empty-string|null, industry: non-empty-string|null, website: non-empty-string|null, description: non-empty-string|null, ceo: non-empty-string|null, sector: non-empty-string|null, country: non-empty-string|null, fullTimeEmployees: non-empty-string|null, phone: non-empty-string|null, address: non-empty-string|null, city: non-empty-string|null, state: non-empty-string|null, zip: non-empty-string|null, image: non-empty-string|null, ipoDate: non-empty-string|null, defaultImage: bool|null, isEtf: bool|null, isActivelyTrading: bool|null, isAdr: bool|null, isFund: bool|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'price' => $this->price,
			'marketCap' => $this->marketCap,
			'beta' => $this->beta,
			'lastDividend' => $this->lastDividend,
			'range' => $this->range,
			'change' => $this->change,
			'changePercentage' => $this->changePercentage,
			'volume' => $this->volume,
			'averageVolume' => $this->averageVolume,
			'companyName' => $this->companyName,
			'currency' => $this->currency,
			'cik' => $this->cik,
			'isin' => $this->isin,
			'cusip' => $this->cusip,
			'exchangeFullName' => $this->exchangeFullName,
			'exchange' => $this->exchange,
			'industry' => $this->industry,
			'website' => $this->website,
			'description' => $this->description,
			'ceo' => $this->ceo,
			'sector' => $this->sector,
			'country' => $this->country,
			'fullTimeEmployees' => $this->fullTimeEmployees,
			'phone' => $this->phone,
			'address' => $this->address,
			'city' => $this->city,
			'state' => $this->state,
			'zip' => $this->zip,
			'image' => $this->image,
			'ipoDate' => $this->ipoDate,
			'defaultImage' => $this->defaultImage,
			'isEtf' => $this->isEtf,
			'isActivelyTrading' => $this->isActivelyTrading,
			'isAdr' => $this->isAdr,
			'isFund' => $this->isFund,
		];
	}

	/**
	 * The CSV bulk endpoint returns marketCap as a decimal string (e.g. "4367820508911.0005"),
	 * which the int type rejects. Round such values to the nearest integer.
	 */
	public static function castDecimalStringToInt(mixed $value, TypeContext $context): mixed
	{
		if (is_string($value) && str_contains($value, '.')) {
			$float = filter_var($value, FILTER_VALIDATE_FLOAT);
			if ($float !== false) {
				return (int) $float;
			}
		}

		return $value;
	}

}
