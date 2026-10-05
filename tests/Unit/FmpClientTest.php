<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Exception\UnexpectedHttpCodeException;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class FmpClientTest extends TestCase
{

	public function testInvalidStatusCodeForJson(): void
	{
		$client = new SymfonyFmpClient(new MockHttpClient([
			new MockResponse(info: ['http_code' => 401]),
		]), 'SECRET', strictMode: true);

		try {
			iterator_to_array($client->balanceSheetStatement('AAPL'));
			self::fail('An unexpected status code must throw.');
		} catch (UnexpectedHttpCodeException $exception) {
			self::assertSame(401, $exception->statusCode);
			self::assertSame('Unexpected HTTP status code 401 received when parsing JSON response.', $exception->getMessage());
		}
	}

	public function testInvalidStatusCodeForCsv(): void
	{
		$client = new SymfonyFmpClient(new MockHttpClient([
			new MockResponse(info: ['http_code' => 429]),
		]), 'SECRET', strictMode: true, retryConfiguration: null);

		try {
			iterator_to_array($client->balanceSheetStatementBulk(2000));
			self::fail('An unexpected status code must throw.');
		} catch (UnexpectedHttpCodeException $exception) {
			self::assertSame(429, $exception->statusCode);
			self::assertSame('Unexpected HTTP status code 429 received when parsing CSV response.', $exception->getMessage());
		}
	}

	public function testTheStatusCodeIsOptionalForAnExceptionBuiltFromAMessage(): void
	{
		$exception = new UnexpectedHttpCodeException('Unexpected HTTP status code 500 received when parsing JSON response.');

		self::assertNull($exception->statusCode);
		self::assertSame('Unexpected HTTP status code 500 received when parsing JSON response.', $exception->getMessage());
	}

}
