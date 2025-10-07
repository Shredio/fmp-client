<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class Cryptocurrency
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $name
	 * @param non-empty-string $exchange
	 * @param non-empty-string|null $icoDate
	 */
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
	 * @return array{symbol: non-empty-string, name: string, exchange: string, icoDate: non-empty-string|null, circulatingSupply: int|float|null, totalSupply: int|float|null}
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
