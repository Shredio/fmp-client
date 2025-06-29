<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Exception;

interface UnexpectedResponseContentExceptionHandler
{

	public function handle(UnexpectedResponseContentException $exception): void;

}
