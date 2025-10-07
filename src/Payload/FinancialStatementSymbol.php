<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class FinancialStatementSymbol
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $reportingCurrency
	 */
	public function __construct(
		public string $symbol,
		public string $companyName,
		public string $tradingCurrency,
		public ?string $reportingCurrency = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, companyName: string, tradingCurrency: string, reportingCurrency: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'companyName' => $this->companyName,
			'tradingCurrency' => $this->tradingCurrency,
			'reportingCurrency' => $this->reportingCurrency,
		];
	}

}
