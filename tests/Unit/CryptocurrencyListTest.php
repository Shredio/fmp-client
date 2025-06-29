<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\Cryptocurrency;
use Tests\TestCase;

final class CryptocurrencyListTest extends TestCase
{

	public function testCryptocurrencyList(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/cryptocurrency-list.json');

		$cryptocurrencies = iterator_to_array($client->cryptocurrencyList());

		$this->assertNotEmpty($cryptocurrencies);
		$this->assertSame((new Cryptocurrency(
			symbol: 'ALIENUSD',
			name: 'Alien Inu USD',
			exchange: 'CCC',
			icoDate: '2021-11-22',
			circulatingSupply: 0,
			totalSupply: null,
		))->toArray(), $cryptocurrencies[0]->toArray());
	}

}