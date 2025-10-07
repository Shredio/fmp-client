<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\DelistedCompany;
use Tests\TestCase;

final class DelistedCompaniesTest extends TestCase
{

	public function testDelistedCompanies(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/delisted-companies.json');

		$delistedCompanies = iterator_to_array($client->delistedCompanies());

		$this->assertNotEmpty($delistedCompanies);
		$this->assertSame((new DelistedCompany(
			symbol: 'CAM',
			companyName: 'Cameron Intl Corp.',
			exchange: 'NYSE',
			ipoDate: '1995-07-05',
			delistedDate: '2025-10-06',
		))->toArray(), $delistedCompanies[0]->toArray());
	}

	public function testDelistedCompaniesWithParameters(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/delisted-companies.json');

		$delistedCompanies = iterator_to_array($client->delistedCompanies(50, 1));

		$this->assertNotEmpty($delistedCompanies);
		$this->assertInstanceOf(DelistedCompany::class, $delistedCompanies[0]);
	}

}