<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\KeyExecutive;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class KeyExecutivesTest extends TestCase
{

	public function testKeyExecutives(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/key-executives-aapl.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$executives = iterator_to_array($client->keyExecutives('AAPL'));

		$this->assertCount(18, $executives);
		$this->assertSame('https://financialmodelingprep.com/stable/key-executives?symbol=AAPL&apikey=SECRET', $response->getRequestUrl());
		$this->assertSame((new KeyExecutive(
			title: 'Vice President of Corporate Development',
			name: 'Adrian Perica',
			pay: null,
			currencyPay: 'USD',
			gender: 'male',
			yearBorn: 1974,
			titleSince: null,
			active: true,
		))->toArray(), $executives[0]->toArray());
		$this->assertSame((new KeyExecutive(
			title: 'Senior Vice President of Services and Health',
			name: 'Eduardo H. Cue',
			pay: 2807461,
			currencyPay: 'USD',
			gender: 'male',
			yearBorn: 1964,
			titleSince: null,
			active: true,
		))->toArray(), $executives[2]->toArray());
	}

	public function testKeyExecutivesOfForeignListing(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/key-executives-sap-de.json');

		$executives = iterator_to_array($client->keyExecutives('SAP.DE'));

		$this->assertCount(10, $executives);
		$this->assertSame((new KeyExecutive(
			title: 'Demand Manager & Works Council Europe Representative Member of Supervisory Board',
			name: 'Jakub Cerny',
			pay: 235000,
			currencyPay: 'EUR',
			gender: null,
			yearBorn: 1982,
			titleSince: null,
			active: true,
		))->toArray(), $executives[0]->toArray());
	}

	public function testSymbolWithoutExecutives(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->keyExecutives('SPY')));
	}

}
