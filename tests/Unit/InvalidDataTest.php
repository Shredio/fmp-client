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
		$this->expectExceptionMessage('The name of available exchange in AMS must be a non-empty-string. Got: NULL');
		iterator_to_array($client->availableExchanges());
	}

	public function testNoStrictMode(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/bad-data.json', $handler = new TestUnexpectedResponseContentExceptionHandler())
			->withStrictMode(false);

		$exchanges = iterator_to_array($client->availableExchanges());

		$this->assertCount(1, $exchanges);
		$this->assertSame('AMEX', $exchanges[0]->exchange);

		$this->assertSame([
			'The name of available exchange in AMS must be a non-empty-string. Got: NULL',
		], $handler->messages);
	}

}
