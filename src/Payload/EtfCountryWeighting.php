<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\PercentageString;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * The API does not return the symbol of the fund.
 */
#[CompileObjectMapper]
final readonly class EtfCountryWeighting
{

	/**
	 * @param string $country Name of the country, "Other" for the remainder
	 * @param float $weightPercentage Share of the fund in percent, the API sends it as a string such as "97.7%"
	 */
	public function __construct(
		public string $country,
		#[CompilePropertyOptions(before: [PercentageString::class, 'toFloat'])]
		public float $weightPercentage,
	)
	{
	}

	/**
	 * @return array{country: string, weightPercentage: float}
	 */
	public function toArray(): array
	{
		return [
			'country' => $this->country,
			'weightPercentage' => $this->weightPercentage,
		];
	}

}
