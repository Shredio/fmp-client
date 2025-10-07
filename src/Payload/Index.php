<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class Index
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $name
	 * @param non-empty-string $exchange
	 * @param non-empty-string $currency
	 */
	public function __construct(
		public string $symbol,
		public string $name,
		public string $exchange,
		public string $currency,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, name: string, exchange: string, currency: string}
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
