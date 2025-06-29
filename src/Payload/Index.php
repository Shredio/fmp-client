<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class Index
{

	public function __construct(
		public string $symbol,
		public string $name,
		public string $exchange,
		public string $currency,
	)
	{
	}

	/**
	 * @return array{symbol: string, name: string, exchange: string, currency: string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'name' => $this->name,
			'exchange' => $this->exchange,
			'currency' => $this->currency,
		];
	}

}