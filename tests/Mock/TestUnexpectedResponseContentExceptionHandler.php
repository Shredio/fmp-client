<?php declare(strict_types = 1);

namespace Tests\Mock;

use LogicException;
use Psr\Log\LoggerInterface;
use Shredio\FmpClient\Exception\UnexpectedResponseContentException;
use Shredio\FmpClient\Exception\UnexpectedResponseContentExceptionHandler;
use Stringable;

final class TestUnexpectedResponseContentExceptionHandler implements UnexpectedResponseContentExceptionHandler
{

	/** @var list<string> */
	public array $messages = [];

	/** @var list<UnexpectedResponseContentException> */
	public array $exceptions = [];

	public function handle(UnexpectedResponseContentException $exception): void
	{
		$this->messages[] = $exception->getMessage();
		$this->exceptions[] = $exception;
	}

}
