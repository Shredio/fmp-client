<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper]
final readonly class EtfSectorExposure
{

	/**
	 * @param string $industry Name of the sector, the API calls it an industry
	 * @param float $exposure Share of the fund in percent
	 */
	public function __construct(
		public string $industry,
		public float $exposure,
	)
	{
	}

	/**
	 * @return array{industry: string, exposure: float}
	 */
	public function toArray(): array
	{
		return [
			'industry' => $this->industry,
			'exposure' => $this->exposure,
		];
	}

}
