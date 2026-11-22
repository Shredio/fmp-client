<?php declare(strict_types = 1);

namespace Tests\Unit;

use JsonException;
use Shredio\FmpClient\Exception\UnexpectedHttpCodeException;
use Shredio\FmpClient\Parser\LargeResponseParser;
use Shredio\FmpClient\Payload\Cryptocurrency;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;
use WeakReference;

final class JsonParsingTest extends TestCase
{

	public function testInvalidStatusCodeForLargeJson(): void
	{
		$client = new SymfonyFmpClient(new MockHttpClient([
			new MockResponse(info: ['http_code' => 401]),
		]), 'SECRET', strictMode: true);

		try {
			iterator_to_array($client->stockList());
			self::fail('An unexpected status code must throw.');
		} catch (UnexpectedHttpCodeException $exception) {
			self::assertSame(401, $exception->statusCode);
			self::assertSame('Unexpected HTTP status code 401 received when parsing JSON response.', $exception->getMessage());
		}
	}

	public function testBuiltInJsonParserReturnsTheSameObjectsAsStreaming(): void
	{
		$createClient = static fn (bool $builtInJsonParser): SymfonyFmpClient => new SymfonyFmpClient(new MockHttpClient([
			MockResponse::fromFile(__DIR__ . '/fixtures/cryptocurrency-list.json'),
		]), 'SECRET', strictMode: true, builtInJsonParser: $builtInJsonParser);
		$toArrays = static fn (iterable $cryptocurrencies): array => array_map(
			static fn (Cryptocurrency $cryptocurrency): array => $cryptocurrency->toArray(),
			iterator_to_array($cryptocurrencies, false),
		);

		$streamed = $toArrays($createClient(false)->cryptocurrencyList());
		$decoded = $toArrays($createClient(true)->cryptocurrencyList());

		self::assertCount(4786, $streamed);
		self::assertSame($streamed, $decoded);
	}

	public function testEmptyBodyOfSmallResponseThrows(): void
	{
		$client = new SymfonyFmpClient(new MockHttpClient([
			new MockResponse(''),
		]), 'SECRET', strictMode: true);

		$this->expectException(JsonException::class);

		$client->quote('AAPL');
	}

	public function testScalarBodyOfSmallResponseThrows(): void
	{
		$client = new SymfonyFmpClient(new MockHttpClient([
			new MockResponse('"Invalid API KEY."'),
		]), 'SECRET', strictMode: true);

		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('JSON content was expected to decode to an array, "string" returned.');

		$client->quote('AAPL');
	}

	public function testStreamedResponseIsReleasedWithoutGarbageCollection(): void
	{
		$httpClient = new MockHttpClient([
			new MockResponse('[{"symbol": "AAPL"}, {"symbol": "MSFT"}]'),
		]);
		$response = $httpClient->request('GET', 'https://financialmodelingprep.com/stable/stock-list');
		$responseReference = WeakReference::create($response);

		gc_collect_cycles();
		gc_disable();
		try {
			$items = iterator_to_array((new LargeResponseParser())->parseJson($httpClient, $response), false);
			unset($response);

			// JsonMachine keeps its parser in a reference cycle, which must not keep the response alive
			self::assertNull($responseReference->get());
		} finally {
			gc_enable();
		}

		self::assertSame([['symbol' => 'AAPL'], ['symbol' => 'MSFT']], $items);
	}

}
