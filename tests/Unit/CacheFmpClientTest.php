<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\CacheFmpClient;
use Shredio\FmpClient\Payload\AvailableExchange;
use Shredio\FmpClient\Payload\CompanyProfile;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class CacheFmpClientTest extends TestCase
{

	public function testHit(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/available-exchanges.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$exchanges = iterator_to_array($client->availableExchanges());
		$this->assertCount(71, $exchanges);
		$this->assertSame((new AvailableExchange(
			exchange: 'AMEX',
			name: 'New York Stock Exchange Arca',
			countryName: 'United States of America',
			countryCode: 'US',
			symbolSuffix: null,
			delay: 'Real-time',
		))->toArray(), $exchanges[0]->toArray());

		$this->assertTrue($cache->has('fmp-client.availableExchanges'), 'Cache should have the key after first request');

		// Second request
		$exchanges = iterator_to_array($client->availableExchanges());
		$this->assertCount(71, $exchanges);
		$this->assertSame((new AvailableExchange(
			exchange: 'AMEX',
			name: 'New York Stock Exchange Arca',
			countryName: 'United States of America',
			countryCode: 'US',
			symbolSuffix: null,
			delay: 'Real-time',
		))->toArray(), $exchanges[0]->toArray());
	}

	public function testHitNullable(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/company-profile-aapl.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$profile = $client->companyProfile('AAPL');

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
		))->toArray(), $profile->toArray());

		$this->assertTrue($cache->has('fmp-client.companyProfile.AAPL'), 'Cache should have the key after first request');
	}

	public function testMissingNullable(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/company-profile-empty.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$profile = $client->companyProfile('BTCUSDX');

		$this->assertNull($profile);

		$this->assertTrue($cache->has('fmp-client.companyProfile.BTCUSDX'), 'Cache should have the key after first request');
	}

	public function testExpiration(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/available-exchanges.json', responses: [
				MockResponse::fromFile(__DIR__ . '/fixtures/company-profile-aapl.json'),
			]),
			$cache = new Psr16Cache(new ArrayAdapter()),
			1,
		);

		$client->availableExchanges();
		$client->companyProfile('AAPL');

		$this->assertTrue($cache->has('fmp-client.availableExchanges'), 'Cache should have the key after first request');
		$this->assertTrue($cache->has('fmp-client.companyProfile.AAPL'), 'Cache should have the key after first request');

		sleep(1);

		$this->assertFalse($cache->has('fmp-client.availableExchanges'), 'Cache should have expired the key after TTL');
		$this->assertFalse($cache->has('fmp-client.companyProfile.AAPL'), 'Cache should have expired the key after TTL');
	}

}
