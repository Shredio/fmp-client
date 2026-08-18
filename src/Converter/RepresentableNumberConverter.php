<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Converter;

use Shredio\TypeSchema\Conversion\Converter\Number\NumberConverter;

/**
 * Rejects finite floats whose magnitude no signed 64-bit integer can hold. FMP occasionally ships a
 * corrupted figure - two adjacent values concatenated into something like -2.2E+35 - and no real financial
 * figure comes anywhere near that magnitude, so such a value always marks a broken row. Rejecting it here
 * turns the row into a regular mapping error: skipped and reported through the invalid-argument handler
 * instead of poisoning every consumer that stores figures in integer columns.
 */
final readonly class RepresentableNumberConverter implements NumberConverter
{

	private const float MaxMagnitude = 2.0 ** 63;

	public function __construct(
		private NumberConverter $decorate,
	)
	{
	}

	public function int(mixed $value): ?int
	{
		return $this->decorate->int($value);
	}

	public function float(mixed $value): ?float
	{
		$float = $this->decorate->float($value);
		if ($float !== null && is_finite($float) && abs($float) >= self::MaxMagnitude) {
			return null;
		}

		return $float;
	}

}
