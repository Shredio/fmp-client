<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\EarningsSurprise;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class EarningsSurprisesBulkTest extends TestCase
{

	public function testEarningsSurprisesBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/earnings-surprises-bulk-2026.csv');

		$surprises = iterator_to_array($client->earningsSurprisesBulk(2026));

		$this->assertNotEmpty($surprises);
		$this->assertSame((new EarningsSurprise(
			symbol: 'SGMOQ',
			date: '2026-10-13',
			epsActual: -0.08,
			epsEstimated: 0.005,
			lastUpdated: '2026-10-13',
		))->toArray(), $surprises[0]->toArray());
	}

	public function testYearIsSentToTheApi(): void
	{
		$response = new MockResponse('"symbol","date","epsActual","epsEstimated","lastUpdated"' . "\n");
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$this->assertSame([], iterator_to_array($client->earningsSurprisesBulk(2025)));
		$this->assertSame(
			'https://financialmodelingprep.com/stable/earnings-surprises-bulk?year=2025&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

	public function testReportWithoutActualEarnings(): void
	{
		// the row of CMPO in the response of the year 2026
		$response = new MockResponse(<<<'CSV'
"symbol","date","epsActual","epsEstimated","lastUpdated"
"CMPO","2026-03-12",,0.2962,"2026-03-26"
CSV);
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$surprises = iterator_to_array($client->earningsSurprisesBulk(2026));

		$this->assertCount(1, $surprises);
		$this->assertSame((new EarningsSurprise(
			symbol: 'CMPO',
			date: '2026-03-12',
			epsActual: null,
			epsEstimated: 0.2962,
			lastUpdated: '2026-03-26',
		))->toArray(), $surprises[0]->toArray());
	}

}
