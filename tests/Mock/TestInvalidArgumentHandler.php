<?php declare(strict_types = 1);

namespace Tests\Mock;

use LogicException;
use Psr\Log\LoggerInterface;
use Shredio\FmpClient\Exception\InvalidArgumentException;
use Shredio\FmpClient\Exception\InvalidArgumentHandler;
use Stringable;

final class TestInvalidArgumentHandler implements InvalidArgumentHandler
{

	/** @var list<string> */
	public array $messages = [];

	public function handle(InvalidArgumentException $exception): void
	{
		$this->messages[] = $exception->getMessage();
	}

}
