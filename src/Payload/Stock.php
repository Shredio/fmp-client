<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class Stock
{

	public function __construct(
		public string $symbol,
		public ?string $companyName = null,
	)
	{
	}

	/**
	 * @return array{symbol: string, companyName: string|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'companyName' => $this->companyName,
		];
	}

}