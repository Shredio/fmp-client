<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\InsiderTrade;
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

}
