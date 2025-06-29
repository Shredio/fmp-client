<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class FinancialStatementSymbol
{

	public function __construct(
		public string $symbol,
		public string $companyName,
		public string $tradingCurrency,
		public ?string $reportingCurrency = null,
	)
	{
	}

	/**
	 * @return array{symbol: string, companyName: string, tradingCurrency: string, reportingCurrency: string|null}
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