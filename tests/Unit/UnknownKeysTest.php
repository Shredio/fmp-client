<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Exception\UnexpectedResponseContentException;
use Tests\Mock\TestUnexpectedResponseContentExceptionHandler;
use Tests\TestCase;

/**
 * Keys added by the API that a payload does not know are reported as notices: in strict mode they fail,
 * otherwise the payload is returned and the notices are passed to the handler.
 */
final class UnknownKeysTest extends TestCase
{

	public function testStrictModeRejectsUnknownKey(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/unknown-keys-data.json');

		try {
			iterator_to_array($client->availableExchanges());

			$this->fail('Expected UnexpectedResponseContentException to be thrown');
		} catch (UnexpectedResponseContentException $exception) {
			$this->assertSame(<<<'ERR'
Shredio\FmpClient\Payload\AvailableExchange: Extra key found.
  → at timezone
  → for value "AMEX"
ERR, $exception->getMessage());
			$this->assertFalse($exception->noticesOnly);
		}
	}

	public function testNoStrictModeReturnsPayloadAndReportsUnknownKey(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/unknown-keys-data.json', $handler = new TestUnexpectedResponseContentExceptionHandler())
			->withStrictMode(false);

		$exchanges = iterator_to_array($client->availableExchanges());

		$this->assertSame(['AMEX', 'AMS'], array_map(static fn ($exchange): string => $exchange->exchange, $exchanges));
		$this->assertSame([
			<<<'ERR'
Shredio\FmpClient\Payload\AvailableExchange: Extra key found.
  → at timezone
  → for value "AMEX"
ERR,
			<<<'ERR'
Shredio\FmpClient\Payload\AvailableExchange: Invalid type null, expected non-empty-string.
  → at name
  → for value "XETRA"
Extra key found.
  → at timezone
  → for value "XETRA"
ERR,
		], $handler->messages);
		$this->assertSame([true, false], array_map(
			static fn (UnexpectedResponseContentException $exception): bool => $exception->noticesOnly,
			$handler->exceptions,
		));
		$this->assertSame(
			'https://financialmodelingprep.com/stable/available-exchanges',
			$handler->exceptions[0]->url,
		);
	}

}
