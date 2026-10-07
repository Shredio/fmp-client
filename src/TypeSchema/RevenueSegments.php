<?php declare(strict_types = 1);

namespace Shredio\FmpClient\TypeSchema;

use Shredio\TypeSchema\Context\TypeContext;

/**
 * FMP ships the revenue of some segments as a numeric string instead of a number (IGC returns
 * "WellnessAndLifestyleMember": "113000" next to "Construction": 146000). Such a string is cast to the number it
 * holds instead of rejecting the whole period. Any other string (e.g. "n/a") is left untouched, so it still
 * fails as a mapping error.
 */
final class RevenueSegments
{

	public static function castNumericStrings(mixed $value, TypeContext $context): mixed
	{
		if (!is_array($value)) {
			return $value;
		}

		foreach ($value as $segment => $revenue) {
			if (!is_string($revenue)) {
				continue;
			}

			$float = filter_var($revenue, FILTER_VALIDATE_FLOAT);
			if ($float !== false) {
				$value[$segment] = $float;
			}
		}

		return $value;
	}

}
