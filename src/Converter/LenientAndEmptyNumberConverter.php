<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Converter;

use Shredio\TypeSchema\Conversion\Converter\Number\NumberConverter;

final readonly class LenientAndEmptyNumberConverter implements NumberConverter
{

	public function __construct(
		private NumberConverter $decorate,
	)
	{
	}

	public function int(mixed $value): ?int
	{
		if ($value === '') {
			return 0;
		}

		return $this->decorate->int($value);
	}

	public function float(mixed $value): ?float
	{
		if ($value === '') {
			return 0.0;
		}

		return $this->decorate->float($value);
	}

}
