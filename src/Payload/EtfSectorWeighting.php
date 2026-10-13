<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EtfSectorWeighting
{

	/**
	 * @param non-empty-string $symbol Ticker symbol of the fund
	 * @param float $weightPercentage Share of the fund in percent
	 */
	public function __construct(
		public string $symbol,
		public string $sector,
		public float $weightPercentage,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, sector: string, weightPercentage: float}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'sector' => $this->sector,
			'weightPercentage' => $this->weightPercentage,
		];
	}

}
