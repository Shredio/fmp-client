<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\HouseTrade;
use Shredio\FmpClient\Payload\LatestHouseTrade;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class HouseTradesTest extends TestCase
{

	public function testHouseTrades(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/house-trades-aapl.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$trades = iterator_to_array($client->houseTrades('AAPL', limit: 250));

		$this->assertCount(250, $trades);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/house-trades?symbol=AAPL&page=0&limit=250&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new HouseTrade(
			symbol: 'AAPL',
			senateID: 'K000389',
			disclosureDate: '2026-10-09',
			transactionDate: '2026-09-04',
			firstName: 'Ro',
			lastName: 'Khanna',
			office: 'Ro Khanna',
			district: 'CA17',
			owner: 'Spouse',
			assetDescription: 'Apple Inc',
			assetType: 'Stock',
			type: 'Sale',
			amount: '$1,001 - $15,000',
			capitalGainsOver200USD: 'False',
			comment: '',
			link: 'https://disclosures-clerk.house.gov/public_disc/ptr-pdfs/2026/9116376.pdf',
		))->toArray(), $trades[0]->toArray());
	}

	public function testHouseTradeWithoutDistrictAndOwner(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/house-trades-aapl.json');

		$trades = iterator_to_array($client->houseTrades('AAPL', limit: 250));

		$this->assertSame((new HouseTrade(
			symbol: 'AAPL',
			senateID: 'R000619',
			disclosureDate: '2026-08-10',
			transactionDate: '2025-08-01',
			firstName: 'Michael',
			lastName: 'Rulli',
			office: 'Michael Rulli',
			district: '',
			owner: '',
			assetDescription: 'Apple Inc',
			assetType: 'Stock',
			type: 'Purchase',
			amount: '$1,001 - $15,000',
			capitalGainsOver200USD: 'False',
			comment: '',
			link: 'https://disclosures-clerk.house.gov/public_disc/ptr-pdfs/2026/20035147.pdf',
		))->toArray(), $trades[14]->toArray());
	}

	public function testHouseTradeOfStockOptionAndTradeWithComment(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/house-trades-aapl.json');

		$trades = iterator_to_array($client->houseTrades('AAPL', limit: 250));

		$this->assertSame('Stock Option', $trades[18]->assetType);
		$this->assertSame('Sale (Full)', $trades[84]->type);
		$this->assertSame(
			'This stock was part of the Inherited Beneficiary IRA spouse received upon the death of her father in 2021. Spouse has chosen to divest of all the stocks contained within this IRA. As this is an Inherited Beneficiary IRA, there are no capital gains on this sale.',
			$trades[84]->comment,
		);
	}

	public function testHouseTradesOfForeignListing(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->houseTrades('SAP.DE')));
	}

	public function testDefaultLimitIsLeftToTheApi(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/empty-response.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		iterator_to_array($client->houseTrades('AAPL', 2));

		$this->assertSame(
			'https://financialmodelingprep.com/stable/house-trades?symbol=AAPL&page=2&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

	public function testHouseTradesLatest(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/house-latest.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$trades = iterator_to_array($client->houseTradesLatest(limit: 250));

		$this->assertCount(250, $trades);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/house-latest?page=0&limit=250&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new LatestHouseTrade(
			symbol: 'AMZN',
			senateID: 'K000389',
			disclosureDate: '2026-10-09',
			transactionDate: '2026-09-04',
			firstName: 'Ro',
			lastName: 'Khanna',
			office: 'Ro Khanna',
			district: 'CA17',
			owner: '',
			assetDescription: 'Amazon.com Inc',
			assetType: 'Stock',
			type: 'Purchase',
			amount: '$1,001 - $15,000',
			capitalGainsOver200USD: 'False',
			comment: '',
			link: 'https://disclosures-clerk.house.gov/public_disc/ptr-pdfs/2026/9116376.pdf',
		))->toArray(), $trades[0]->toArray());
	}

	public function testHouseTradesLatestAssetWithoutSymbol(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/house-latest-page-1.json');

		$trades = iterator_to_array($client->houseTradesLatest(1, 250));

		$this->assertCount(250, $trades);
		$this->assertSame((new LatestHouseTrade(
			symbol: '',
			senateID: 'F000480',
			disclosureDate: '2026-10-05',
			transactionDate: '2026-09-18',
			firstName: 'Vince',
			lastName: 'Fong',
			office: 'Vince Fong',
			district: 'CA20',
			owner: '',
			assetDescription: 'U.S. Treasury Bill 0.0% Int Mat 12/10/2026',
			assetType: 'Government Securities',
			type: 'Purchase',
			amount: '$15,001 - $50,000',
			capitalGainsOver200USD: 'False',
			comment: '',
			link: 'https://disclosures-clerk.house.gov/public_disc/ptr-pdfs/2026/20035539.pdf',
		))->toArray(), $trades[240]->toArray());
	}

}
