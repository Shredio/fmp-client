<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Exception;

use Throwable;

/**
 * The API answered with an HTTP status other than 200. `$statusCode` carries that status; it is null only when the
 * exception was built from a message alone. The message keeps its fixed wording ("Unexpected HTTP status code %d
 * received when parsing ... response.") for callers that still read the status from it.
 */
final class UnexpectedHttpCodeException extends \Exception
{

	public function __construct(
		string $message,
		public readonly ?int $statusCode = null,
		?Throwable $previous = null,
	)
	{
		parent::__construct($message, previous: $previous);
	}

}
