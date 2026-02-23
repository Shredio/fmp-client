<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class IsinSearchResult
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $isin
	 */
	public function __construct(
		public string $symbol,
		public string $name,
		public string $isin,
		public float|null $marketCap,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, name: string, isin: non-empty-string, marketCap: float|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'name' => $this->name,
			'isin' => $this->isin,
			'marketCap' => $this->marketCap,
		];
	}

}