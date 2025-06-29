<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class CompanyProfile
{

	public function __construct(
		public string $symbol,
		public ?float $price = null,
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
	 * @return array{symbol: string, price: float|null, marketCap: int|null, beta: float|null, lastDividend: float|null, range: string|null, change: float|null, changePercentage: float|null, volume: int|float|null, averageVolume: int|float|null, companyName: string|null, currency: string|null, cik: string|null, isin: string|null, cusip: string|null, exchangeFullName: string|null, exchange: string|null, industry: string|null, website: string|null, description: string|null, ceo: string|null, sector: string|null, country: string|null, fullTimeEmployees: string|null, phone: string|null, address: string|null, city: string|null, state: string|null, zip: string|null, image: string|null, ipoDate: string|null, defaultImage: bool|null, isEtf: bool|null, isActivelyTrading: bool|null, isAdr: bool|null, isFund: bool|null}
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

}
