<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class Cryptocurrency
{

	public function __construct(
		public string $symbol,
		public string $name,
		public string $exchange,
		public ?string $icoDate = null,
		public int|float|null $circulatingSupply = null,
		public int|float|null $totalSupply = null,
	)
	{
	}

	/**
	 * @return array{symbol: string, name: string, exchange: string, icoDate: string|null, circulatingSupply: int|float|null, totalSupply: int|float|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'name' => $this->name,
			'exchange' => $this->exchange,
			'icoDate' => $this->icoDate,
			'circulatingSupply' => $this->circulatingSupply,
			'totalSupply' => $this->totalSupply,
		];
	}

}