<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\InsiderTrade;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class InsiderTradesTest extends TestCase
{

	public function testInsiderTrades(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/insider-trading-search-aapl.json');

		$trades = iterator_to_array($client->insiderTrades('AAPL'));

		$this->assertCount(100, $trades);
		$this->assertSame((new InsiderTrade(
			symbol: 'AAPL',
			filingDate: '2026-09-03',
			transactionDate: '2026-09-01',
			reportingCik: '0001780525',
			companyCik: '0000320193',
			transactionType: 'S-Sale',
			securitiesOwned: 35790,
			reportingName: 'Newstead Jennifer',
			typeOfOwner: 'officer: SVP, GC and Government Affairs',
			acquisitionOrDisposition: 'D',
			directOrIndirect: 'D',
			formType: '4',
			securitiesTransacted: 1439,
			price: 317.01,
			securityName: 'Common Stock',
			url: 'https://www.sec.gov/Archives/edgar/data/320193/000114036126035636/0001140361-26-035636-index.htm',
		))->toArray(), $trades[0]->toArray());
	}

	public function testFormThreeHasEmptyTransactionFields(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/insider-trading-search-aapl.json');

		$trades = iterator_to_array($client->insiderTrades('AAPL'));

		$this->assertSame('3', $trades[2]->formType);
		$this->assertSame('', $trades[2]->transactionType);
		$this->assertSame('', $trades[2]->acquisitionOrDisposition);
		$this->assertSame(0.0, $trades[2]->price);
	}

	public function testInsiderTradeWithoutDirectOrIndirect(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/insider-trading-search-msft.json');

		$trades = iterator_to_array($client->insiderTrades('MSFT'));

		$this->assertCount(100, $trades);
		$this->assertSame((new InsiderTrade(
			symbol: 'MSFT',
			filingDate: '2026-07-01',
			transactionDate: '2026-07-01',
			reportingCik: '0001626431',
			companyCik: '0000789019',
			transactionType: '',
			securitiesOwned: 0,
			reportingName: 'Hogan Kathleen T',
			typeOfOwner: 'officer',
			acquisitionOrDisposition: '',
			directOrIndirect: null,
			formType: '4',
			securitiesTransacted: 0,
			price: 0.0,
			securityName: '',
			url: 'https://www.sec.gov/Archives/edgar/data/789019/000078901926000137/0000789019-26-000137-index.htm',
		))->toArray(), $trades[61]->toArray());
	}

	public function testInsiderTradesLatest(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/insider-trading-latest.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$trades = iterator_to_array($client->insiderTradesLatest(limit: 100));

		$this->assertCount(100, $trades);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/insider-trading/latest?page=0&limit=100&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new InsiderTrade(
			symbol: 'SON',
			filingDate: '2026-10-05',
			transactionDate: '2026-10-01',
			reportingCik: '0001502922',
			companyCik: '0000091767',
			transactionType: 'A-Award',
			securitiesOwned: 30728.3,
			reportingName: 'Guillemot Philippe',
			typeOfOwner: 'director',
			acquisitionOrDisposition: 'A',
			directOrIndirect: 'D',
			formType: '4',
			securitiesTransacted: 747.4,
			price: 0.0,
			securityName: 'Phantom Stock Units',
			url: 'https://www.sec.gov/Archives/edgar/data/91767/000122520826008147/0001225208-26-008147-index.htm',
		))->toArray(), $trades[0]->toArray());
	}

	public function testInsiderTradesLatestFiledFromDate(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/insider-trading-latest.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		iterator_to_array($client->insiderTradesLatest(page: 2, limit: 1000, from: new DateTimeImmutable('2026-10-02')));

		$this->assertSame(
			'https://financialmodelingprep.com/stable/insider-trading/latest?date=2026-10-02&page=2&limit=1000&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

}
