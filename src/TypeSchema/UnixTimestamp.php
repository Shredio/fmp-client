<?php declare(strict_types = 1);

namespace Shredio\FmpClient\TypeSchema;

use Shredio\TypeSchema\Context\TypeContext;

/**
 * FMP sends the timestamp of some quotes with milliseconds as a float (1794420455.449 for KZMS.ME on MCX on
 * 2026-11-11, next to 1794420456 for AAPL). Such a value is cut to the whole second the property expects instead
 * of rejecting the whole payload. Values no signed 64-bit integer can hold are left untouched, so they still fail
 * as a mapping error.
 */
final class UnixTimestamp
{

	private const float MaxMagnitude = 2.0 ** 63;

	public static function toWholeSeconds(mixed $value, TypeContext $context): mixed
	{
		if (is_float($value) && is_finite($value) && abs($value) < self::MaxMagnitude) {
			return (int) floor($value);
		}

		return $value;
	}

}
