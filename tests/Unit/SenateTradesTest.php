<?php declare(strict_types = 1);

namespace Tests\Unit;

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

}
