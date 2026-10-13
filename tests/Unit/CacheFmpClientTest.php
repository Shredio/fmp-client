<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\CacheFmpClient;
use Shredio\FmpClient\Enum\StockIndex;
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

		$this->assertTrue($cache->has('fmp-client.v2.availableExchanges'), 'Cache should have the key after first request');

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

		$this->assertTrue($cache->has('fmp-client.v2.companyProfile.AAPL'), 'Cache should have the key after first request');
	}

	public function testQuoteIsCachedPerSymbol(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/quote-aapl.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$quote = $client->quote('AAPL');

		$this->assertNotNull($quote);
		$this->assertSame(328.21, $quote->price);
		$this->assertTrue($cache->has('fmp-client.v2.quote.AAPL'), 'Cache should have the key after first request');

		// Second request is served from the cache, the mock client has no response left
		$this->assertSame($quote->toArray(), $client->quote('AAPL')?->toArray());
	}

	public function testGradesAreCachedPerSymbolAndLimit(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/grades-consensus-aapl.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		$consensus = $client->gradesConsensus('AAPL');

		$this->assertNotNull($consensus);
		$this->assertSame('Buy', $consensus->consensus);
		$this->assertTrue($cache->has('fmp-client.v2.gradesConsensus.AAPL'), 'Cache should have the key after first request');
	}

	public function testInstitutionalHoldersAreCachedPerQuarterPageAndLimit(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/institutional-ownership-extract-analytics-holder-aapl-2026-q2.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$holders = iterator_to_array($client->institutionalHolders('AAPL', 2026, 2, limit: 100));

		$this->assertCount(100, $holders);
		$this->assertTrue($cache->has('fmp-client.v2.institutionalHolders.AAPL.2026.2.0.100'), 'Cache should have the key after first request');

		// Second request is served from the cache, the mock client has no response left
		$cachedHolders = iterator_to_array($client->institutionalHolders('AAPL', 2026, 2, limit: 100));

		$this->assertCount(100, $cachedHolders);
		$this->assertSame($holders[0]->toArray(), $cachedHolders[0]->toArray());
	}

	public function testInstitutionalPositionsSummaryIsCachedPerQuarter(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/institutional-ownership-symbol-positions-summary-aapl-2026-q2.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$summary = $client->institutionalPositionsSummary('AAPL', 2026, 2);

		$this->assertNotNull($summary);
		$this->assertTrue($cache->has('fmp-client.v2.institutionalPositionsSummary.AAPL.2026.2'), 'Cache should have the key after first request');

		// Second request is served from the cache, the mock client has no response left
		$this->assertSame($summary->toArray(), $client->institutionalPositionsSummary('AAPL', 2026, 2)?->toArray());
	}

	public function testBulkEndpointIsNotCached(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/upgrades-downgrades-consensus-bulk.csv'),
			new Psr16Cache($adapter = new ArrayAdapter()),
			3600,
		);

		$this->assertNotEmpty(iterator_to_array($client->gradesConsensusBulk()));
		$this->assertSame([], $adapter->getValues());
	}

	public function testBulkEndpointSplitIntoPartsIsNotCached(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/etf-holder-bulk-part-0-excerpt.csv', responses: [
				new MockResponse('', [
					'http_code' => 400,
				]),
			]),
			new Psr16Cache($adapter = new ArrayAdapter()),
			3600,
		);

		$this->assertNotEmpty(iterator_to_array($client->etfHoldingsBulk(), false));
		$this->assertSame([], $adapter->getValues());
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

		$this->assertTrue($cache->has('fmp-client.v2.companyProfile.BTCUSDX'), 'Cache should have the key after first request');
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

		$this->assertTrue($cache->has('fmp-client.v2.availableExchanges'), 'Cache should have the key after first request');
		$this->assertTrue($cache->has('fmp-client.v2.companyProfile.AAPL'), 'Cache should have the key after first request');

		sleep(1);

		$this->assertFalse($cache->has('fmp-client.v2.availableExchanges'), 'Cache should have expired the key after TTL');
		$this->assertFalse($cache->has('fmp-client.v2.companyProfile.AAPL'), 'Cache should have expired the key after TTL');
	}

	public function testLatestFeedIsCachedPerPageLimitAndDate(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/insider-trading-latest.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$trades = iterator_to_array($client->insiderTradesLatest(limit: 100, from: new DateTimeImmutable('2026-10-02')));

		$this->assertCount(100, $trades);
		$this->assertTrue($cache->has('fmp-client.v2.insiderTradesLatest.0.100.2026-10-02'), 'Cache should have the key after first request');

		// Second request is served from the cache, the mock client has no response left
		$this->assertCount(100, iterator_to_array($client->insiderTradesLatest(limit: 100, from: new DateTimeImmutable('2026-10-02'))));
	}

	public function testNewsOfSymbolIsCachedPerSymbolPageAndLimit(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/price-target-news-aapl.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		$this->assertCount(100, iterator_to_array($client->priceTargetNews('AAPL', limit: 100)));
		$this->assertTrue($cache->has('fmp-client.v2.priceTargetNews.AAPL.0.100'), 'Cache should have the key after first request');
	}

	public function testBatchQuoteIsCachedPerListOfSymbols(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/batch-quote-delisted.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$quotes = iterator_to_array($client->batchQuote(['MTOR', 'MRO', 'RVI']));

		$this->assertCount(3, $quotes);
		// the key holds a hash of the list, a caret of an index symbol or a long list would not make a valid key
		$this->assertTrue(
			$cache->has(sprintf('fmp-client.v2.batchQuote.%s', hash('xxh128', 'MTOR,MRO,RVI'))),
			'Cache should have the key after first request',
		);

		// Second request is served from the cache, the mock client has no response left
		$cachedQuotes = iterator_to_array($client->batchQuote(['MTOR', 'MRO', 'RVI']));

		$this->assertCount(3, $cachedQuotes);
		$this->assertSame($quotes[2]->toArray(), $cachedQuotes[2]->toArray());
	}

	public function testHouseTradesAreCachedPerSymbolPageAndLimit(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/house-trades-aapl.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		$this->assertCount(250, iterator_to_array($client->houseTrades('AAPL', limit: 250)));
		$this->assertTrue($cache->has('fmp-client.v2.houseTrades.AAPL.0.250'), 'Cache should have the key after first request');

		// Second request is served from the cache, the mock client has no response left
		$this->assertCount(250, iterator_to_array($client->houseTrades('AAPL', limit: 250)));
	}

	public function testEtfInfoIsCachedPerSymbol(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/etf-info-spy.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		// First request
		$info = $client->etfInfo('SPY');

		$this->assertNotNull($info);
		$this->assertTrue($cache->has('fmp-client.v2.etfInfo.SPY'), 'Cache should have the key after first request');

		// Second request is served from the cache with the nested sector exposures, the mock client has no response left
		$this->assertSame($info->toArray(), $client->etfInfo('SPY')?->toArray());
	}

	public function testIndexConstituentsAreCachedPerIndex(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/dowjones-constituent.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		$this->assertCount(30, iterator_to_array($client->indexConstituents(StockIndex::DowJones)));
		$this->assertTrue($cache->has('fmp-client.v2.indexConstituents.dowjones'), 'Cache should have the key after first request');
		$this->assertFalse($cache->has('fmp-client.v2.indexConstituents.sp500'));
	}

	public function testCalendarAndNewBulkEndpointsAreNotCached(): void
	{
		$client = new CacheFmpClient(
			$this->createClient(__DIR__ . '/fixtures/rating-bulk.csv', responses: [
				MockResponse::fromFile(__DIR__ . '/fixtures/ipos-calendar-2026-10-01-to-2026-10-31.json'),
			]),
			new Psr16Cache($adapter = new ArrayAdapter()),
			3600,
		);

		$this->assertNotEmpty(iterator_to_array($client->ratingBulk()));
		$this->assertCount(39, iterator_to_array(
			$client->iposCalendar(new DateTimeImmutable('2026-10-01'), new DateTimeImmutable('2026-10-31')),
			false,
		));
		$this->assertSame([], $adapter->getValues());
	}

}
