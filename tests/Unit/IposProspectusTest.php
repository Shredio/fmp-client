<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\IpoProspectus;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class IposProspectusTest extends TestCase
{

	public function testIposProspectus(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/ipos-prospectus-2026-10-01-to-2026-10-30.json');
		$mockClient = new MockHttpClient([$response]);
		$client = new SymfonyFmpClient($mockClient, 'SECRET', null, strictMode: true);

		$prospectuses = iterator_to_array(
			$client->iposProspectus(new DateTimeImmutable('2026-10-01'), new DateTimeImmutable('2026-10-30')),
			false,
		);

		// one request answers the whole window
		$this->assertCount(82, $prospectuses);
		$this->assertSame(1, $mockClient->getRequestsCount());
		$this->assertSame(
			'https://financialmodelingprep.com/stable/ipos-prospectus?from=2026-10-01&to=2026-10-30&apikey=SECRET',
			$response->getRequestUrl(),
		);
		// a final prospectus of a company listed a year earlier
		$this->assertSame((new IpoProspectus(
			symbol: 'MGN',
			acceptedDate: '2026-10-29',
			filingDate: '2026-10-29',
			ipoDate: '2025-09-25',
			cik: '0001995075',
			pricePublicPerShare: 0.5,
			pricePublicTotal: 7500000,
			discountsAndCommissionsPerShare: 0.03,
			discountsAndCommissionsTotal: 450000,
			proceedsBeforeExpensesPerShare: 0.47,
			proceedsBeforeExpensesTotal: 7050000,
			form: '424B4',
			url: 'https://www.sec.gov/Archives/edgar/data/1995075/000121390026113588/ea0310082-424b4_megan.htm',
		))->toArray(), $prospectuses[0]->toArray());
		// filed on the day after the SEC accepted it
		$this->assertSame(
			['NUOX', 'S-1/A', '2026-10-28', '2026-10-29'],
			[$prospectuses[2]->symbol, $prospectuses[2]->form, $prospectuses[2]->acceptedDate, $prospectuses[2]->filingDate],
		);
	}

	public function testIposProspectusWithoutTheDiscount(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/ipos-prospectus-2026-01-29-to-2026-02-10.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$prospectuses = iterator_to_array(
			$client->iposProspectus(new DateTimeImmutable('2026-01-29'), new DateTimeImmutable('2026-02-10')),
			false,
		);

		$this->assertCount(105, $prospectuses);
		// no discount per share, and the per-share figure sent in the total, rounded
		$this->assertSame((new IpoProspectus(
			symbol: 'SVIVU',
			acceptedDate: '2026-02-10',
			filingDate: '2026-02-10',
			ipoDate: '2026-02-10',
			cik: '0002098242',
			pricePublicPerShare: 10.0,
			pricePublicTotal: 200000000,
			discountsAndCommissionsPerShare: null,
			discountsAndCommissionsTotal: 1,
			proceedsBeforeExpensesPerShare: 9.4,
			proceedsBeforeExpensesTotal: 188000000,
			form: '424B4',
			url: 'https://www.sec.gov/Archives/edgar/data/2098242/000110465926012742/svivu-20260121x424b4.htm',
		))->toArray(), $prospectuses[10]->toArray());
		// no discount total
		$this->assertSame(
			['KRAQU', 0.31, null],
			[$prospectuses[102]->symbol, $prospectuses[102]->discountsAndCommissionsPerShare, $prospectuses[102]->discountsAndCommissionsTotal],
		);
	}

}
