<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class Stock
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $companyName
	 */
	public function __construct(
		public string $symbol,
		public ?string $companyName = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, companyName: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'companyName' => $this->companyName,
		];
	}

}
