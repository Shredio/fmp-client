<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Exception\UnexpectedHttpCodeException;
use Shredio\FmpClient\FmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class FmpClientTest extends TestCase
{

	public function testInvalidStatusCodeForJson(): void
	{
		$client = new FmpClient(new MockHttpClient([
			new MockResponse(info: ['http_code' => 401]),
		]), 'SECRET', strictMode: true);

		self::expectException(UnexpectedHttpCodeException::class);

		iterator_to_array($client->balanceSheetStatement('AAPL'));
	}

	public function testInvalidStatusCodeForCsv(): void
	{
		$client = new FmpClient(new MockHttpClient([
			new MockResponse(info: ['http_code' => 401]),
		]), 'SECRET', strictMode: true);

		self::expectException(UnexpectedHttpCodeException::class);

		iterator_to_array($client->balanceSheetStatementBulk(2000));
	}

}
