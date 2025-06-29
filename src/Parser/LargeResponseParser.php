<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Parser;

use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use League\Csv\Reader;
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
		$reader = Reader::createFromStream(StreamWrapper::createResource($response, $client));
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
		$parser = static function (ResponseInterface $response) use ($client): iterable {
			if ($response instanceof StreamableInterface) {
				return Items::fromStream($response->toStream(), [
					'decoder' => new ExtJsonDecoder(true),
				]);
			}

			$toChunks = static function (ResponseStreamInterface $stream): iterable {
				foreach ($stream as $chunk) {
					yield $chunk->getContent();
				}
			};

			return Items::fromIterable($toChunks($client->stream($response)), [
				'decoder' => new ExtJsonDecoder(true),
			]);
		};

		foreach ($parser($response) as $value) {
			yield $value;
		}

		$response->cancel();
	}

}
