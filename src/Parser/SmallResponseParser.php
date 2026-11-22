<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Parser;

use JsonException;
use Shredio\FmpClient\Exception\UnexpectedHttpCodeException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Parser for responses small enough to be decoded at once: json_decode is several times faster than streaming
 * through JsonMachine and leaves no garbage cycles behind, which matters for endpoints called once per symbol
 */
final readonly class SmallResponseParser
{

	/**
	 * Decode the whole JSON response at once
	 *
	 * @return array<array-key, mixed>
	 * @throws JsonException When the body is empty, invalid or not a JSON array or object
	 */
	public function parseJson(ResponseInterface $response): array
	{
		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			$response->cancel();

			throw new UnexpectedHttpCodeException(sprintf(
				'Unexpected HTTP status code %d received when parsing JSON response.',
				$statusCode
			), $statusCode);
		}

		// the flags of the ExtJsonDecoder used by LargeResponseParser, so both parsers return the same values
		$decoded = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR);
		if (!is_array($decoded)) {
			throw new JsonException(sprintf('JSON content was expected to decode to an array, "%s" returned.', get_debug_type($decoded)));
		}

		return $decoded;
	}

}
