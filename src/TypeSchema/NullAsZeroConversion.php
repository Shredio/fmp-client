<?php declare(strict_types = 1);

namespace Shredio\FmpClient\TypeSchema;

use Shredio\TypeSchema\Conversion\ConversionStrategyDecorator;

final readonly class NullAsZeroConversion extends ConversionStrategyDecorator
{

	public function int(mixed $value): ?int
	{
		if ($value === null) {
			return 0;
		}

		return parent::int($value);
	}

	public function float(mixed $value): ?float
	{
		if ($value === null) {
			return 0.0;
		}

		return parent::float($value);
	}

}
