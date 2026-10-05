<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\LatestSenateTrade;
use Shredio\FmpClient\Payload\SenateTrade;
use Tests\TestCase;

final class SenateTradesTest extends TestCase
{

	public function testSenateTrades(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/senate-trades-aapl.json');

		$trades = iterator_to_array($client->senateTrades('AAPL'));

		$this->assertCount(250, $trades);
		$this->assertSame((new SenateTrade(
			symbol: 'AAPL',
			senateID: 'T000278',
			disclosureDate: '2026-08-05',
			transactionDate: '2025-01-10',
			firstName: 'Tommy',
			lastName: 'Tuberville',
			office: 'Tommy Tuberville',
			district: 'AL',
			owner: 'Joint',
			assetDescription: 'Apple Inc',
			assetType: 'Stock',
			type: 'Sale',
			amount: '$15,001 - $50,000',
			comment: '',
			link: 'https://efdsearch.senate.gov/search/view/ptr/d1afafd0-a78f-44ef-ae47-da99dfb01317/',
			capitalGainsOver200USD: 'False',
		))->toArray(), $trades[0]->toArray());
	}

	public function testFormerSenatorHasNoSenateId(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/senate-trades-aapl.json');

		$trades = iterator_to_array($client->senateTrades('AAPL'));

		$this->assertNull($trades[34]->senateID);
		$this->assertSame('Schiff,  Adam B. (Senator)', $trades[34]->office);
		$this->assertSame('', $trades[34]->district);
	}

	public function testSenateTradesLatest(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/senate-latest.json');

		$trades = iterator_to_array($client->senateTradesLatest(limit: 100));

		$this->assertCount(100, $trades);
		$this->assertSame((new LatestSenateTrade(
			symbol: 'ADI',
			senateID: 'W000802',
			disclosureDate: '2026-09-30',
			transactionDate: '2026-09-04',
			firstName: 'Sheldon',
			lastName: 'Whitehouse',
			office: 'Sheldon Whitehouse',
			district: 'RI',
			owner: 'Spouse',
			assetDescription: 'Analog Devices, Inc. - Common Stock',
			assetType: 'Stock',
			type: 'Sale (Partial)',
			amount: '$1,001 - $15,000',
			comment: '',
			link: 'https://efdsearch.senate.gov/search/view/ptr/6bf3b6f7-9e1b-499a-bd5a-990292ce2e72/',
		))->toArray(), $trades[0]->toArray());
	}

	public function testSenateTradesLatestAssetWithoutSymbol(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/senate-latest.json');

		$trades = iterator_to_array($client->senateTradesLatest(limit: 100));

		$this->assertSame((new LatestSenateTrade(
			symbol: '',
			senateID: 'B001277',
			disclosureDate: '2026-09-27',
			transactionDate: '2026-08-27',
			firstName: 'Richard',
			lastName: 'Blumenthal',
			office: 'Blumenthal, Richard (Senator)',
			district: '',
			owner: 'Spouse',
			assetDescription: 'MH Built to Last LLC Company: MH Built to Last LLC (New York, NY) Description: Partnership',
			assetType: 'Other',
			type: 'Purchase',
			amount: '$1,001 - $15,000',
			comment: 'Underlying Asset of Peter L. Malkin Family 9 LLC',
			link: 'https://efdsearch.senate.gov/search/view/ptr/9e2ff733-aeac-4ce8-872c-3d6b7913da88/',
		))->toArray(), $trades[5]->toArray());
	}

}
