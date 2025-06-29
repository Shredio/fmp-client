<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Exception;

use Throwable;

final class UnexpectedResponseContentException extends \InvalidArgumentException
{

	public function __construct(
		string $message,
		Throwable $previous,
		public readonly string $url,
	)
	{
		parent::__construct($message, previous: $previous);
	}

}
