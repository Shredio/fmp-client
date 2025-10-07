<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class ActivelyTrading
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $name
	 */
	public function __construct(
		public string $symbol,
		public string $name,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, name: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'name' => $this->name,
		];
	}

}
