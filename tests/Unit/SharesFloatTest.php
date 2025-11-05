<?php declare(strict_types = 1);

namespace Tests\Unit;

use LogicException;
use Shredio\FmpClient\Payload\SharesFloat;
use Tests\TestCase;

final class SharesFloatTest extends TestCase
{

	public function testSharesFloat(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/shares-float.json');

		$sharesFloat = $client->sharesFloat('AAPL');

		$this->assertSame((new SharesFloat(
			symbol: 'AAPL',
			date: '2025-10-20 18:44:55',
			freeFloat: 99.82400000269534,
			floatShares: 14814270914,
			outstandingShares: 14840390000,
			source: 'https://www.sec.gov/Archives/edgar/data/320193/000032019324000123/aapl-20240928.htm',
		))->toArray(), $sharesFloat->toArray());
	}

	public function testSharesFloatEmpty(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/shares-float-empty.json');

		$sharesFloat = $client->sharesFloat('INVALID');

		$this->assertNull($sharesFloat);
	}

	public function testSharesFloatMultipleRecordsThrowsException(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/shares-float-multiple.json');

		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('Expected 0 or 1 record for shares float, got more than 1 for symbol AAPL');

		$client->sharesFloat('AAPL');
	}

	public function testSharesFloatAll(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/shares-float-all.json');

		$sharesFloatList = iterator_to_array($client->sharesFloatAll(10, 0));

		$this->assertCount(10, $sharesFloatList);

		$this->assertSame((new SharesFloat(
			symbol: '000001.SZ',
			date: '2025-11-05 02:09:05',
			freeFloat: 41.70506089082711,
			floatShares: 8093250000,
			outstandingShares: 19405918198,
		))->toArray(), $sharesFloatList[0]->toArray());

		$this->assertSame((new SharesFloat(
			symbol: '000005.SZ',
			date: null,
			freeFloat: 79.91881,
			floatShares: 845972573,
			outstandingShares: 1058540032,
		))->toArray(), $sharesFloatList[3]->toArray());
	}

}
