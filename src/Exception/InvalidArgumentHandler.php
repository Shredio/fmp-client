<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Exception;

interface InvalidArgumentHandler
{

	public function handle(InvalidArgumentException $exception): void;

}
