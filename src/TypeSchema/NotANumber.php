<?php declare(strict_types = 1);

namespace Shredio\FmpClient\TypeSchema;

use Shredio\TypeSchema\Context\TypeContext;

/**
 * FMP writes a number it does not know as "NaN" into a CSV response, while the JSON endpoint sends null for the
 * very same record. Such a value is turned into null. Any other value is left untouched, so it is still converted
 * or rejected the usual way.
 */
final class NotANumber
{

	public static function toNull(mixed $value, TypeContext $context): mixed
	{
		return $value === 'NaN' ? null : $value;
	}

}
