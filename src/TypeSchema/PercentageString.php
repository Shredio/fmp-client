<?php declare(strict_types = 1);

namespace Shredio\FmpClient\TypeSchema;

use Shredio\TypeSchema\Context\TypeContext;

/**
 * FMP ships the country weights of a fund as a string with a percent sign ("97.7%"), while the sector weights of
 * the same fund arrive as a number. Such a string is cast to the number it holds. Any other value is left
 * untouched, so it still fails as a mapping error.
 */
final class PercentageString
{

	public static function toFloat(mixed $value, TypeContext $context): mixed
	{
		if (!is_string($value) || !str_ends_with($value, '%')) {
			return $value;
		}

		$float = filter_var(substr($value, 0, -1), FILTER_VALIDATE_FLOAT);

		return $float === false ? $value : $float;
	}

}
