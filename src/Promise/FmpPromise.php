<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Promise;

use Fiber;

/**
 * @template-covariant T
 */
final readonly class FmpPromise
{

	/**
	 * @param Fiber<mixed, mixed, T, mixed> $fiber
	 */
	public function __construct(
		private Fiber $fiber,
	)
	{
	}

	/**
	 * @return T
	 */
	public function await(): mixed
	{
		while (!$this->fiber->isTerminated()) {
			$this->fiber->resume();
		}

		return $this->fiber->getReturn();
	}

	/**
	 * @template TRet
	 * @param callable(): TRet $fn
	 * @return self<TRet>
	 */
	public static function run(callable $fn): self
	{
		$fiber = new Fiber($fn);
		$fiber->start();

		/** @var FmpPromise<TRet> */
		return new self($fiber);
	}

	public static function wait(): void
	{
		if (Fiber::getCurrent() !== null) {
			Fiber::suspend();
		}
	}

}
