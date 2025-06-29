<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\CompanyProfile;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class CompanyProfileTest extends TestCase
{

	public function testCompanyProfile(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/company-profile-aapl.json');

		$profiles = iterator_to_array($client->companyProfile('AAPL'));

		$this->assertNotEmpty($profiles);
		$this->assertSame((new CompanyProfile(
			symbol: 'AAPL',
			price: 200.3,
			marketCap: 2991640740000,
			beta: 1.211,
			lastDividend: 1.01,
			range: '169.21-260.1',
			change: -1.2,
			changePercentage: -0.59553,
			volume: 53899923,
			averageVolume: 61159585,
			companyName: 'Apple Inc.',
			currency: 'USD',
			cik: '0000320193',
			isin: 'US0378331005',
			cusip: '037833100',
			exchangeFullName: 'NASDAQ Global Select',
			exchange: 'NASDAQ',
			industry: 'Consumer Electronics',
			website: 'https://www.apple.com',
			description: 'Apple Inc. designs, manufactures, and markets smartphones, personal computers, tablets, wearables, and accessories worldwide. The company offers iPhone, a line of smartphones; Mac, a line of personal computers; iPad, a line of multi-purpose tablets; and wearables, home, and accessories comprising AirPods, Apple TV, Apple Watch, Beats products, and HomePod. It also provides AppleCare support and cloud services; and operates various platforms, including the App Store that allow customers to discover and download applications and digital content, such as books, music, video, games, and podcasts, as well as advertising services include third-party licensing arrangements and its own advertising platforms. In addition, the company offers various subscription-based services, such as Apple Arcade, a game subscription service; Apple Fitness+, a personalized fitness service; Apple Music, which offers users a curated listening experience with on-demand radio stations; Apple News+, a subscription news and magazine service; Apple TV+, which offers exclusive original content; Apple Card, a co-branded credit card; and Apple Pay, a cashless payment service, as well as licenses its intellectual property. The company serves consumers, and small and mid-sized businesses; and the education, enterprise, and government markets. It distributes third-party applications for its products through the App Store. The company also sells its products through its retail and online stores, and direct sales force; and third-party cellular network carriers, wholesalers, retailers, and resellers. Apple Inc. was founded in 1976 and is headquartered in Cupertino, California.',
			ceo: 'Mr. Timothy D. Cook',
			sector: 'Technology',
			country: 'US',
			fullTimeEmployees: '164000',
			phone: '(408) 996-1010',
			address: 'One Apple Park Way',
			city: 'Cupertino',
			state: 'CA',
			zip: '95014',
			image: 'https://images.financialmodelingprep.com/symbol/AAPL.png',
			ipoDate: '1980-12-12',
			defaultImage: false,
			isEtf: false,
			isActivelyTrading: true,
			isAdr: false,
			isFund: false,
		))->toArray(), $profiles[0]->toArray());
	}

	public function testCompanyProfileEmpty(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/company-profile-empty.json');

		$profiles = iterator_to_array($client->companyProfile('BTCUSDX'));

		$this->assertEmpty($profiles);
	}

	public function testCompanyProfileBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/company-profile-bulk.csv', responses: [
			new MockResponse('', [
				'http_code' => 400,
			]),
		]);

		$profiles = iterator_to_array($client->companyProfileBulk());

		$this->assertNotEmpty($profiles);
		$this->assertSame((new CompanyProfile(
			symbol: 'SSAB-B.ST',
			price: 54.98,
			marketCap: 53725437496,
			beta: 1.078,
			lastDividend: 2.6,
			range: '42.1-72.22',
			change: 0.02,
			changePercentage: 0.0363901,
			volume: 802965,
			averageVolume: 3729699,
			companyName: 'SSAB AB (publ)',
			currency: 'SEK',
			cik: null,
			isin: 'SE0000120669',
			cusip: null,
			exchangeFullName: 'Stockholm Stock Exchange',
			exchange: 'STO',
			industry: 'Steel',
			website: 'https://www.ssab.com',
			description: 'SSAB AB (publ) produces and sells steel products in the United States, Sweden, Finland, Germany, Denmark, and internationally. It operates through five segments: SSAB Special Steels, SSAB Europe, SSAB Americas, Tibnor, and Ruukki Construction. The SSAB Special Steels segment offers quenched and tempered steels, and hot-rolled advanced high-strength steel products. The SSAB Europe segment provides strip, plate, and tubular products. The SSAB Americas segment offers heavy steel plates. The Tibnor segment distributes a range of steel and non-ferrous metals in the Nordic region and the Baltics. The Ruukki Construction segment produces and sells building and construction products and services for residential and non-residential construction. The company markets its steel products under the Strenx, Hardox, Docol, GreenCoat, Toolox, Armox, Duroxite, SSAB Boron, SSAB Domex, SSAB Form, SSAB Laser, SSAB Weathering and Cor-Ten, and SSAB Multisteel brands. It serves the heavy transport, construction, automotive, industrial, construction machinery, energy, material handling, and service center industries. The company has a collaboration agreement with Faurecia S.E. to deliver fossil-free steel for automotive seat structures. SSAB AB (publ) was founded in 1878 and is headquartered in Stockholm, Sweden.',
			ceo: 'Mr. Johnny  Sjöström',
			sector: 'Basic Materials',
			country: 'SE',
			fullTimeEmployees: '14637',
			phone: '46 84 54 57 00',
			address: 'Klarabergsviadukten 70, D6',
			city: 'Stockholm',
			state: null,
			zip: '101 21',
			image: 'https://images.financialmodelingprep.com/symbol/SSAB-B.ST.png',
			ipoDate: '2000-01-03',
			defaultImage: false,
			isEtf: false,
			isActivelyTrading: true,
			isAdr: false,
			isFund: false,
		))->toArray(), $profiles[0]->toArray());
	}

}
