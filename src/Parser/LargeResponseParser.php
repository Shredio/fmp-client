<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Parser;

use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use League\Csv\Reader;
use Shredio\FmpClient\Exception\UnexpectedHttpCodeException;
use Symfony\Component\HttpClient\Response\StreamableInterface;
use Symfony\Component\HttpClient\Response\StreamWrapper;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Parser for handling large HTTP responses in streaming fashion
 */
final readonly class LargeResponseParser
{

	/**
	 * Parse CSV response data in streaming mode
	 *
	 * @param HttpClientInterface $client HTTP client instance
	 * @param ResponseInterface $response HTTP response to parse
	 * @return iterable<array<string, string>>
	 */
	public function parseCsv(HttpClientInterface $client, ResponseInterface $response): iterable
	{
		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			$response->cancel();

			throw new UnexpectedHttpCodeException(sprintf(
				'Unexpected HTTP status code %d received when parsing CSV response.',
				$statusCode
			), $statusCode);
		}

		$reader = Reader::from(StreamWrapper::createResource($response, $client));
		$reader->setHeaderOffset(0);

		/** @var array<string, string> $item */
		foreach ($reader->getRecords() as $item) {
			yield $item;
		}

		$response->cancel();
	}

	/**
	 * Parse JSON response data in streaming mode
	 *
	 * @param HttpClientInterface $client HTTP client instance
	 * @param ResponseInterface $response HTTP response to parse
	 * @return iterable<array-key, mixed>
	 */
	public function parseJson(HttpClientInterface $client, ResponseInterface $response): iterable
	{
		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			$response->cancel();

			throw new UnexpectedHttpCodeException(sprintf(
				'Unexpected HTTP status code %d received when parsing JSON response.',
				$statusCode
			), $statusCode);
		}

		if ($response instanceof StreamableInterface) {
			$stream = $response->toStream();

			try {
				foreach (Items::fromStream($stream, ['decoder' => new ExtJsonDecoder(true)]) as $value) {
					yield $value;
				}
			} finally {
				// the JsonMachine parser holds itself in a reference cycle, closing the stream releases the response
				// (and its buffered body) right away instead of on the next garbage collection
				fclose($stream);
			}
		} else {
			$toChunks = static function (ResponseStreamInterface $stream): iterable {
				foreach ($stream as $chunk) {
					yield $chunk->getContent();
				}
			};

			foreach (Items::fromIterable($toChunks($client->stream($response)), ['decoder' => new ExtJsonDecoder(true)]) as $value) {
				yield $value;
			}
		}

		$response->cancel();
	}

}
