<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class ExchangeMarketHours
{

	public function __construct(
		public string $exchange,
		public string $name,
		public string $openingHour,
		public string $closingHour,
		public string $timezone,
		public bool $isMarketOpen,
		public ?string $openingAdditional = null,
		public ?string $closingAdditional = null,
	)
	{
	}

	/**
	 * @return array{exchange: string, name: string, openingHour: string, closingHour: string, timezone: string, isMarketOpen: bool, openingAdditional: string|null, closingAdditional: string|null}
	 */
	public function toArray(): array
	{
		return [
			'exchange' => $this->exchange,
			'name' => $this->name,
			'openingHour' => $this->openingHour,
			'closingHour' => $this->closingHour,
			'timezone' => $this->timezone,
			'isMarketOpen' => $this->isMarketOpen,
			'openingAdditional' => $this->openingAdditional,
			'closingAdditional' => $this->closingAdditional,
		];
	}

}