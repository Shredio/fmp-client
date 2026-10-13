<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\StockIndex;
use Shredio\FmpClient\Payload\HistoricalIndexConstituent;
use Shredio\FmpClient\Payload\IndexConstituent;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class IndexConstituentsTest extends TestCase
{

	public function testSp500Constituents(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/sp500-constituent.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$constituents = iterator_to_array($client->indexConstituents(StockIndex::Sp500));

		$this->assertCount(503, $constituents);
		$this->assertSame('https://financialmodelingprep.com/stable/sp500-constituent?apikey=SECRET', $response->getRequestUrl());
		$this->assertSame((new IndexConstituent(
			symbol: 'TWLO',
			name: 'Twilio Inc.',
			sector: 'Technology',
			subSector: 'Software - Infrastructure',
			headQuarter: 'San Francisco, California',
			dateFirstAdded: '2026-10-06',
			cik: '0001447669',
			founded: '2007-12-31',
		))->toArray(), $constituents[0]->toArray());
	}

	public function testNasdaqConstituentWithoutDateFirstAdded(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/nasdaq-constituent.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$constituents = iterator_to_array($client->indexConstituents(StockIndex::Nasdaq));

		// both share classes of Alphabet are members
		$this->assertCount(101, $constituents);
		$this->assertSame('https://financialmodelingprep.com/stable/nasdaq-constituent?apikey=SECRET', $response->getRequestUrl());
		$this->assertSame((new IndexConstituent(
			symbol: 'ADBE',
			name: 'Adobe Inc.',
			sector: 'Technology',
			subSector: 'Software - Infrastructure',
			headQuarter: 'San Jose, CA',
			dateFirstAdded: null,
			cik: '0000796343',
			founded: '1982-12-01',
		))->toArray(), $constituents[0]->toArray());
	}

	public function testDowJonesConstituents(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/dowjones-constituent.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$constituents = iterator_to_array($client->indexConstituents(StockIndex::DowJones));

		$this->assertCount(30, $constituents);
		$this->assertSame('https://financialmodelingprep.com/stable/dowjones-constituent?apikey=SECRET', $response->getRequestUrl());
		$this->assertSame((new IndexConstituent(
			symbol: 'GOOGL',
			name: 'Alphabet Inc.',
			sector: 'Communication Services',
			subSector: 'Internet Content & Information',
			headQuarter: 'Mountain View, California',
			dateFirstAdded: '2026-06-29',
			cik: '0001652044',
			founded: '1998-09-04',
		))->toArray(), $constituents[0]->toArray());
	}

	public function testHistoricalSp500Constituents(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/historical-sp500-constituent.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$changes = iterator_to_array($client->historicalIndexConstituents(StockIndex::Sp500));

		$this->assertCount(1531, $changes);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-sp500-constituent?apikey=SECRET',
			$response->getRequestUrl(),
		);
		// a replacement
		$this->assertSame((new HistoricalIndexConstituent(
			dateAdded: 'October 06, 2026',
			addedSecurity: 'Twilio Inc.',
			removedTicker: 'WBD',
			removedSecurity: 'Warner Bros. Discovery',
			date: '2026-10-06',
			symbol: 'TWLO',
			reason: 'Paramount Skydance acquired Warner Bros. Discovery',
			changeType: 'replacement',
			addedTicker: 'TWLO',
		))->toArray(), $changes[0]->toArray());
		// a removal has no added security
		$this->assertSame((new HistoricalIndexConstituent(
			dateAdded: 'October 06, 2026',
			addedSecurity: null,
			removedTicker: 'CTVA',
			removedSecurity: 'Corteva Inc.',
			date: '2026-10-06',
			symbol: 'CTVA',
			reason: 'Market capitalization changes.',
			changeType: 'removal',
			addedTicker: '',
		))->toArray(), $changes[1]->toArray());
		// an addition has no removed security
		$this->assertSame((new HistoricalIndexConstituent(
			dateAdded: 'October 01, 2026',
			addedSecurity: 'Vylor Inc.',
			removedTicker: '',
			removedSecurity: '',
			date: '2026-10-01',
			symbol: 'VYLR',
			reason: 'Corteva Inc. spun off Vylor.',
			changeType: 'addition',
			addedTicker: 'VYLR',
		))->toArray(), $changes[2]->toArray());
		$this->assertSame('1957-03-04', $changes[1530]->date);
	}

	public function testHistoricalNasdaqConstituents(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/historical-nasdaq-constituent.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$changes = iterator_to_array($client->historicalIndexConstituents(StockIndex::Nasdaq));

		$this->assertCount(452, $changes);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-nasdaq-constituent?apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new HistoricalIndexConstituent(
			dateAdded: 'October 09, 2026',
			addedSecurity: 'Moderna, Inc.',
			removedTicker: 'WBD',
			removedSecurity: 'Warner Bros. Discovery',
			date: '2026-10-09',
			symbol: 'MRNA',
			reason: 'Warner Bros. Discovery was acquired by Paramount Skydance. The combined entity, Skydance Corporation, is listed on the NYSE.',
			changeType: 'replacement',
			addedTicker: 'MRNA',
		))->toArray(), $changes[0]->toArray());
	}

	public function testHistoricalDowJonesConstituents(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/historical-dowjones-constituent.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$changes = iterator_to_array($client->historicalIndexConstituents(StockIndex::DowJones));

		$this->assertCount(86, $changes);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/historical-dowjones-constituent?apikey=SECRET',
			$response->getRequestUrl(),
		);
		// a removal with an empty added security instead of null
		$this->assertSame((new HistoricalIndexConstituent(
			dateAdded: 'August 31, 2020',
			addedSecurity: '',
			removedTicker: 'PFE',
			removedSecurity: 'Pfizer Inc',
			date: '2020-08-31',
			symbol: 'PFE',
			reason: 'Market capitalization change',
			changeType: 'removal',
			addedTicker: '',
		))->toArray(), $changes[4]->toArray());
	}

}
