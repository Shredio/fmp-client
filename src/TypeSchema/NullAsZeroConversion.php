<?php declare(strict_types = 1);

namespace Shredio\FmpClient\TypeSchema;

use Shredio\TypeSchema\Conversion\ConversionStrategy;
use Shredio\TypeSchema\Conversion\ConversionStrategyDecorator;

final readonly class NullAsZeroConversion extends ConversionStrategyDecorator
{

	private const array NanValues = [
		'nan' => true,
		'n/a' => true,
		'na' => true,
	];

	private const array InfinityValues = [
		'infinite' => true,
		'-infinite' => true,
		'infinity' => true,
		'-infinity' => true,
	];

	public function __construct(
		ConversionStrategy $inner,
		private bool $handleNaN = false,
		private bool $handleInfinity = false,
	)
	{
		parent::__construct($inner);
	}

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

		if (is_string($value) && ($this->handleNaN || $this->handleInfinity)) {
			$normalizedValue = strtolower($value);
			if ($this->handleNaN && isset(self::NanValues[$normalizedValue])) {
				return 0.0;
			}

			if ($this->handleInfinity && isset(self::InfinityValues[$normalizedValue])) {
				return 0.0;
			}
		}

		return parent::float($value);
	}

}
