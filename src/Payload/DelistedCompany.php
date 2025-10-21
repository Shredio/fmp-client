<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class DelistedCompany
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $companyName
	 * @param non-empty-string $exchange
	 * @param non-empty-string $ipoDate
	 * @param non-empty-string $delistedDate
	 */
	public function __construct(
		public string $symbol,
		public string $companyName,
		public string $exchange,
		public string $ipoDate,
		public string $delistedDate,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, companyName: non-empty-string, exchange: non-empty-string, ipoDate: non-empty-string, delistedDate: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'companyName' => $this->companyName,
			'exchange' => $this->exchange,
			'ipoDate' => $this->ipoDate,
			'delistedDate' => $this->delistedDate,
		];
	}

}
