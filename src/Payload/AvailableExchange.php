<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class AvailableExchange
{

	public function __construct(
		public string $exchange,
		public string $name,
		public ?string $countryName = null,
		public ?string $countryCode = null,
		public ?string $symbolSuffix = null,
		public ?string $delay = null,
	)
	{
	}

	/**
	 * @return array{exchange: string, name: string, countryName: string|null, countryCode: string|null, symbolSuffix: string|null, delay: string|null}
	 */
	public function toArray(): array
	{
		return [
			'exchange' => $this->exchange,
			'name' => $this->name,
			'countryName' => $this->countryName,
			'countryCode' => $this->countryCode,
			'symbolSuffix' => $this->symbolSuffix,
			'delay' => $this->delay,
		];
	}

}
