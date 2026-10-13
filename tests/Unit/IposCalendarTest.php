<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\IpoCalendarItem;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class IposCalendarTest extends TestCase
{

	public function testIposCalendar(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/ipos-calendar-2026-10-01-to-2026-10-31.json');
		$mockClient = new MockHttpClient([$response]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$ipos = iterator_to_array(
			$client->iposCalendar(new DateTimeImmutable('2026-10-01'), new DateTimeImmutable('2026-10-31')),
			false,
		);

		$this->assertCount(39, $ipos);
		$this->assertSame(1, $mockClient->getRequestsCount());
		$this->assertSame(
			'https://financialmodelingprep.com/stable/ipos-calendar?from=2026-10-01&to=2026-10-31&apikey=SECRET',
			$response->getRequestUrl(),
		);
		// an expected IPO
		$this->assertSame((new IpoCalendarItem(
			symbol: 'CTY',
			date: '2026-10-16',
			daa: '2026-10-16T04:00:00.000Z',
			company: 'CITY THERAPEUTICS, INC.',
			exchange: 'NASDAQ',
			actions: 'Expected',
			shares: 9722222,
			priceRange: '17.00 - 19.00',
			dealSize: 212430545,
			marketCap: null,
		))->toArray(), $ipos[0]->toArray());
		// a priced IPO
		$this->assertSame((new IpoCalendarItem(
			symbol: 'TRXB',
			date: '2026-10-09',
			daa: '2026-10-09T04:00:00.000Z',
			company: 'TRex Bio Inc.',
			exchange: 'NASDAQ',
			actions: 'Priced',
			shares: 8333334,
			priceRange: null,
			dealSize: 116666676,
			marketCap: 113287167,
		))->toArray(), $ipos[9]->toArray());
	}

	public function testIposCalendarOfForeignExchanges(): void
	{
		$requested = [];
		$httpClient = new MockHttpClient(static function (string $method, string $url) use (&$requested): MockResponse {
			parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
			$requested[] = sprintf('%s..%s', $query['from'], $query['to']);

			return $query['to'] === '2021-03-31'
				? MockResponse::fromFile(__DIR__ . '/fixtures/ipos-calendar-2021-01-01-to-2021-03-31.json')
				: new MockResponse('[]');
		});
		$client = new SymfonyFmpClient($httpClient, 'SECRET', null, strictMode: true);

		$ipos = iterator_to_array(
			$client->iposCalendar(new DateTimeImmutable('2021-01-01'), new DateTimeImmutable('2021-03-31')),
			false,
		);

		// the oldest record of the first page is dated 2021-01-03, the days before it are requested separately
		$this->assertSame(['2021-01-01..2021-03-31', '2021-01-01..2021-01-02'], $requested);
		$this->assertCount(988, $ipos);
		$this->assertSame((new IpoCalendarItem(
			symbol: '333620.KQ',
			date: '2021-03-31',
			daa: '2021-04-01T00:00:00.000Z',
			company: 'Nsys Co Ltd',
			exchange: 'KOE',
			actions: 'Priced',
			shares: 24395061,
			priceRange: '8100 - 19000',
			dealSize: 197600000000,
			marketCap: 197600000000,
		))->toArray(), $ipos[1]->toArray());
		// marketCap is 25701799775000.004 in the response
		$this->assertSame((new IpoCalendarItem(
			symbol: 'FAPA.JK',
			date: '2021-01-03',
			daa: '2021-01-04T00:00:00.000Z',
			company: 'PT FAP Agri Tbk',
			exchange: 'JSE',
			actions: 'Priced',
			shares: null,
			priceRange: '5,300 - 5,300',
			dealSize: null,
			marketCap: 25701799775000,
		))->toArray(), $ipos[987]->toArray());
	}

	public function testDealSizeWithFractionIsRounded(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/ipos-calendar-2023-04-01-to-2023-06-30.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response, new MockResponse('[]')]), 'SECRET', null, strictMode: true);

		$ipos = iterator_to_array(
			$client->iposCalendar(new DateTimeImmutable('2023-04-01'), new DateTimeImmutable('2023-06-30')),
			false,
		);

		$this->assertCount(438, $ipos);
		// dealSize and marketCap are 1553845.14 in the response
		$this->assertSame((new IpoCalendarItem(
			symbol: 'TGGL.CN',
			date: '2023-06-14',
			daa: '2023-06-14T04:00:00.000Z',
			company: 'Toggle3D.ai Inc',
			exchange: 'CSE',
			actions: 'Priced',
			shares: 8632473,
			priceRange: '0.18 - 0.18',
			dealSize: 1553845,
			marketCap: 1553845,
		))->toArray(), $ipos[121]->toArray());
	}

	public function testIposCalendarWalksPastAnEmptyTailOfTheWindow(): void
	{
		// FMP fills at most ~90 days before `to`, so a window ending far ahead answers with an empty first page;
		// the walk has to step back and fetch the IPOs that sit earlier in the window.
		$requested = [];
		$httpClient = new MockHttpClient(static function (string $method, string $url) use (&$requested): MockResponse {
			parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
			$requested[] = sprintf('%s..%s', $query['from'], $query['to']);

			return $query['to'] === '2026-10-17'
				? MockResponse::fromFile(__DIR__ . '/fixtures/ipos-calendar-2026-10-01-to-2026-10-31.json')
				: new MockResponse('[]');
		});
		$client = new SymfonyFmpClient($httpClient, 'SECRET', null, strictMode: true);

		$ipos = iterator_to_array(
			$client->iposCalendar(new DateTimeImmutable('2026-10-01'), new DateTimeImmutable('2027-01-15')),
			false,
		);

		$this->assertSame(['2026-10-01..2027-01-15', '2026-10-01..2026-10-17'], $requested);
		$this->assertCount(39, $ipos);
		$this->assertSame('CTY', $ipos[0]->symbol);
		$this->assertSame('TDEM', $ipos[38]->symbol);
	}

}
