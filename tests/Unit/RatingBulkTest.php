<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Rating;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class RatingBulkTest extends TestCase
{

	public function testRatingBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/rating-bulk.csv');

		$ratings = iterator_to_array($client->ratingBulk());

		$this->assertNotEmpty($ratings);
		$this->assertSame((new Rating(
			symbol: '000001.SZ',
			date: '2026-10-13',
			rating: 'B',
			discountedCashFlowScore: 1,
			returnOnEquityScore: 3,
			returnOnAssetsScore: 3,
			debtToEquityScore: 1,
			priceToEarningsScore: 4,
			priceToBookScore: 5,
		))->toArray(), $ratings[0]->toArray());
		$this->assertSame((new Rating(
			symbol: '000002.SZ',
			date: '2026-10-13',
			rating: 'B-',
			discountedCashFlowScore: 5,
			returnOnEquityScore: 1,
			returnOnAssetsScore: 1,
			debtToEquityScore: 1,
			priceToEarningsScore: 1,
			priceToBookScore: 5,
		))->toArray(), $ratings[1]->toArray());
	}

	public function testScoreThatIsNotANumberIsRejected(): void
	{
		$response = new MockResponse(<<<'CSV'
"symbol","date","rating","discountedCashFlowScore","returnOnEquityScore","returnOnAssetsScore","debtToEquityScore","priceToEarningsScore","priceToBookScore"
"AAPL","2026-10-12","B",n/a,5,5,1,2,1
"MSFT","2026-10-12","A-",3,5,5,3,3,2
CSV);
		$client = (new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true))->withStrictMode(false);

		$ratings = iterator_to_array($client->ratingBulk(), false);

		$this->assertCount(1, $ratings);
		$this->assertSame('MSFT', $ratings[0]->symbol);
		$this->assertSame('A-', $ratings[0]->rating);
	}

}
