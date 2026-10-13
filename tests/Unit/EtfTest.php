<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Exception\UnexpectedResponseContentException;
use Shredio\FmpClient\Payload\Etf;
use Shredio\FmpClient\Payload\EtfCountryWeighting;
use Shredio\FmpClient\Payload\EtfHolding;
use Shredio\FmpClient\Payload\EtfInfo;
use Shredio\FmpClient\Payload\EtfSectorExposure;
use Shredio\FmpClient\Payload\EtfSectorWeighting;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class EtfTest extends TestCase
{

	public function testEtfList(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/etf-list.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$etfs = iterator_to_array($client->etfList());

		$this->assertCount(15684, $etfs);
		$this->assertSame('https://financialmodelingprep.com/stable/etf-list?apikey=SECRET', $response->getRequestUrl());
		$this->assertSame((new Etf(
			symbol: 'VJPE.DE',
			name: 'Vanguard FTSE Japan UCITS ETF EUR Hedged Accumulating',
		))->toArray(), $etfs[0]->toArray());
	}

	public function testEtfInfo(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/etf-info-spy.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$info = $client->etfInfo('SPY');

		$this->assertNotNull($info);
		$this->assertSame('https://financialmodelingprep.com/stable/etf/info?symbol=SPY&apikey=SECRET', $response->getRequestUrl());
		$this->assertSame((new EtfInfo(
			symbol: 'SPY',
			name: 'State Street SPDR S&P 500 ETF',
			description: 'SPY is the best-recognized and oldest US listed ETF and typically tops rankings for largest AUM and greatest trading volume. The fund tracks the massively popular US index, the S&P 500. Few realize that S&P\'s index committee chooses 500 securities to represent the US large-cap space - not necessarily the 500 largest by market cap, which can lead to some omissions of single names. Still, the index offers outstanding exposure to the US large-cap space. It\'s important to note, SPY is a unit investment trust, an older but entirely viable structure. As a UIT, SPY must fully replicate its index (it probably would anyway) and forgo the small risk and reward of securities lending. It also can`t reinvest portfolio dividends between distributions, the resulting cash drag will slightly hurt performance in up markets and help in downtrends. SPY is a favored vanilla trading vehicle.',
			isin: 'US78462F1030',
			assetClass: 'Equity',
			securityCusip: '78462F103',
			domicile: 'US',
			website: 'https://www.ssga.com/us/en/institutional/etfs/state-street-spdr-sp-500-etf-trust-spy',
			etfCompany: 'SPDR',
			expenseRatio: 0.09,
			assetsUnderManagement: 821678530000,
			avgVolume: 47955083.0,
			inceptionDate: '1993-01-22',
			nav: 778.45,
			navCurrency: 'USD',
			holdingsCount: 504,
			isActivelyTrading: true,
			updatedAt: '2026-10-13T00:45:00.075Z',
			sectorsList: [
				new EtfSectorExposure(industry: 'Technology', exposure: 40.521748525421046),
				new EtfSectorExposure(industry: 'Financial Services', exposure: 11.202262718765407),
				new EtfSectorExposure(industry: 'Communication Services', exposure: 9.679881455159519),
				new EtfSectorExposure(industry: 'Healthcare', exposure: 9.027732966625294),
				new EtfSectorExposure(industry: 'Consumer Cyclical', exposure: 8.673836422237091),
				new EtfSectorExposure(industry: 'Industrials', exposure: 7.309433658065359),
				new EtfSectorExposure(industry: 'Consumer Defensive', exposure: 4.421783535410661),
				new EtfSectorExposure(industry: 'Energy', exposure: 3.5685324063217836),
				new EtfSectorExposure(industry: 'Utilities', exposure: 1.92325447482935),
				new EtfSectorExposure(industry: 'Basic Materials', exposure: 1.7327794570117931),
				new EtfSectorExposure(industry: 'Real Estate', exposure: 1.6612298425522316),
				new EtfSectorExposure(industry: 'Cash & Others', exposure: 0.2775245376003763),
			],
		))->toArray(), $info->toArray());
	}

	public function testEtfInfoOfFundNoLongerTraded(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/etf-info-udbi.json');

		$info = $client->etfInfo('UDBI');

		$this->assertNotNull($info);
		$this->assertSame((new EtfInfo(
			symbol: 'UDBI',
			name: 'Legg Mason US Diversified Core ETF',
			description: null,
			isin: 'US52468L3078',
			assetClass: 'Equity',
			securityCusip: '52468L307',
			domicile: 'US',
			website: null,
			etfCompany: null,
			expenseRatio: null,
			assetsUnderManagement: null,
			avgVolume: 64.0,
			inceptionDate: '2015-12-29',
			nav: null,
			navCurrency: 'USD',
			holdingsCount: null,
			isActivelyTrading: false,
			updatedAt: '2025-02-07T14:39:53.161Z',
			sectorsList: [],
		))->toArray(), $info->toArray());
	}

	public function testEtfInfoWithFractionalAssetsUnderManagement(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/etf-info-gcln.json');

		$info = $client->etfInfo('GCLN');

		$this->assertNotNull($info);
		// assetsUnderManagement is 10196463.71 in the response
		$this->assertSame(10196464, $info->assetsUnderManagement);
		$this->assertFalse($info->isActivelyTrading);
		$this->assertCount(5, $info->sectorsList);
	}

	public function testEtfInfoOfSymbolThatIsNotAFund(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->etfInfo('AAPL'));
	}

	public function testEtfHoldings(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/etf-holdings-spy.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$holdings = iterator_to_array($client->etfHoldings('SPY'));

		$this->assertCount(505, $holdings);
		$this->assertSame('https://financialmodelingprep.com/stable/etf/holdings?symbol=SPY&apikey=SECRET', $response->getRequestUrl());
		$this->assertSame((new EtfHolding(
			symbol: 'SPY',
			asset: 'NVDA',
			name: 'NVIDIA CORP',
			isin: 'US67066G1040',
			securityCusip: '67066G104',
			sharesNumber: 295361471.0,
			weightPercentage: 8.22490936,
			marketValue: 67360960124.0,
			updatedAt: '2026-10-12 17:02:32',
		))->toArray(), $holdings[0]->toArray());
	}

	public function testEtfHoldingWithoutSymbolAndIsin(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/etf-holdings-spy.json');

		$holdings = iterator_to_array($client->etfHoldings('SPY'));

		// the cash position
		$this->assertSame((new EtfHolding(
			symbol: 'SPY',
			asset: '',
			name: 'US DOLLAR',
			isin: '',
			securityCusip: '999USDZ92',
			sharesNumber: 2271611167.47,
			weightPercentage: 0.27589566,
			marketValue: 2259550298.0,
			updatedAt: '2026-10-12 17:02:32',
		))->toArray(), $holdings[61]->toArray());
	}

	public function testEtfHoldingsOfBondFund(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/etf-holdings-tlt.json');

		$holdings = iterator_to_array($client->etfHoldings('TLT'));

		$this->assertCount(49, $holdings);
		// bonds have no ticker symbol and a fractional principal amount
		$this->assertSame((new EtfHolding(
			symbol: 'TLT',
			asset: '',
			name: 'TREASURY BOND 4.75% 05/15/2055',
			isin: 'US912810UK24',
			securityCusip: '912810UK2',
			sharesNumber: 23966443.766195256,
			weightPercentage: 4.32647563,
			marketValue: 2101377789.42,
			updatedAt: '2026-10-12 13:55:48',
		))->toArray(), $holdings[0]->toArray());
	}

	public function testEtfHoldingsOfSymbolThatIsNotAFund(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->etfHoldings('AAPL')));
	}

	public function testEtfSectorWeightings(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/etf-sector-weightings-spy.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$weightings = iterator_to_array($client->etfSectorWeightings('SPY'));

		$this->assertCount(12, $weightings);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/etf/sector-weightings?symbol=SPY&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new EtfSectorWeighting(
			symbol: 'SPY',
			sector: 'Technology',
			weightPercentage: 40.521748525421046,
		))->toArray(), $weightings[0]->toArray());
		$this->assertSame((new EtfSectorWeighting(
			symbol: 'SPY',
			sector: 'Cash & Others',
			weightPercentage: 0.2775245376003763,
		))->toArray(), $weightings[11]->toArray());
	}

	public function testEtfCountryWeightings(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/etf-country-weightings-spy.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$weightings = iterator_to_array($client->etfCountryWeightings('SPY'));

		$this->assertSame(
			'https://financialmodelingprep.com/stable/etf/country-weightings?symbol=SPY&apikey=SECRET',
			$response->getRequestUrl(),
		);
		// the API sends the weights as strings such as "97.7%"
		$this->assertSame([
			(new EtfCountryWeighting(country: 'United States', weightPercentage: 97.7))->toArray(),
			(new EtfCountryWeighting(country: 'Ireland', weightPercentage: 1.16))->toArray(),
			(new EtfCountryWeighting(country: 'United Kingdom', weightPercentage: 0.41))->toArray(),
			(new EtfCountryWeighting(country: 'Switzerland', weightPercentage: 0.29))->toArray(),
			(new EtfCountryWeighting(country: 'Other', weightPercentage: 0.27))->toArray(),
			(new EtfCountryWeighting(country: 'Netherlands', weightPercentage: 0.09))->toArray(),
			(new EtfCountryWeighting(country: 'Bermuda', weightPercentage: 0.07))->toArray(),
			(new EtfCountryWeighting(country: 'Canada', weightPercentage: 0.01))->toArray(),
		], array_map(static fn (EtfCountryWeighting $weighting): array => $weighting->toArray(), $weightings));
	}

	public function testEtfCountryWeightingsOfWorldwideFund(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/etf-country-weightings-vwce-de.json');

		$weightings = iterator_to_array($client->etfCountryWeightings('VWCE.DE'));

		$this->assertCount(49, $weightings);
		$this->assertSame((new EtfCountryWeighting(country: 'United States', weightPercentage: 61.68))->toArray(), $weightings[0]->toArray());
		$this->assertSame((new EtfCountryWeighting(country: 'Other', weightPercentage: 0.0))->toArray(), $weightings[48]->toArray());
	}

	public function testCountryWeightThatIsNotAPercentageIsRejected(): void
	{
		$response = new MockResponse('[{"country":"United States","weightPercentage":"n/a"}]');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$this->expectException(UnexpectedResponseContentException::class);

		iterator_to_array($client->etfCountryWeightings('SPY'));
	}

}
