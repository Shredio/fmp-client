<?php declare(strict_types = 1);

namespace Shredio\FmpClient\TypeSchema;

use Shredio\TypeSchema\Context\TypeContext;

/**
 * FMP computes money amounts such as market cap or enterprise value as price times shares, so a whole amount
 * occasionally arrives with a floating-point tail (e.g. 4216133870848.9995 in JSON, or the same value as a CSV
 * string). Such a value is rounded to the integer the property expects instead of rejecting the whole payload.
 * Magnitudes no signed 64-bit integer can hold are left untouched, so they still fail as a mapping error.
 */
final class MoneyAmount
{

	private const float MaxMagnitude = 2.0 ** 63;

	public static function roundToInt(mixed $value, TypeContext $context): mixed
	{
		if (is_string($value) && preg_match('~^-?\d+$~', $value) !== 1) {
			$float = filter_var($value, FILTER_VALIDATE_FLOAT);
			if ($float === false) {
				return $value;
			}

			$value = $float;
		}

		if (is_float($value) && is_finite($value) && abs($value) < self::MaxMagnitude) {
			return (int) round($value);
		}

		return $value;
	}

}
