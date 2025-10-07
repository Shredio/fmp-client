<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Exception\UnexpectedResponseContentException;
use Tests\Mock\TestUnexpectedResponseContentExceptionHandler;
use Tests\TestCase;

final class InvalidDataTest extends TestCase
{

	public function testStrictMode(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/bad-data.json');

		$this->expectException(UnexpectedResponseContentException::class);
		$this->expectExceptionMessage(<<<'ERR'
Invalid type null, expected non-empty-string.
  → at name
  → for value "AMS"
ERR);
		iterator_to_array($client->availableExchanges());
	}

	public function testStrictModeExceptionUrl(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/bad-data.json');

		try {
			iterator_to_array($client->availableExchanges());

			$this->fail('Expected UnexpectedResponseContentException to be thrown');
		} catch (UnexpectedResponseContentException $e) {
			$this->assertSame('https://financialmodelingprep.com/stable/available-exchanges', $e->url);
		}
	}

	public function testNoStrictMode(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/bad-data.json', $handler = new TestUnexpectedResponseContentExceptionHandler())
			->withStrictMode(false);

		$exchanges = iterator_to_array($client->availableExchanges());

		$this->assertCount(1, $exchanges);
		$this->assertSame('AMEX', $exchanges[0]->exchange);

		$this->assertSame([
			<<<'ERR'
Invalid type null, expected non-empty-string.
  → at name
  → for value "AMS"
ERR,
		], $handler->messages);
	}

}
