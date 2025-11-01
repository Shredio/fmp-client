<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use PrinsFrank\Standards\Currency\MinorUnits\CurrencyMinorLowerLastAlpha3;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class FinancialStatementSymbol
{

	/**
	 * @param non-empty-string $symbol
	 */
	public function __construct(
		public string $symbol,
		public string $companyName,
		public CurrencyAlpha3|CurrencyMinorLowerLastAlpha3 $tradingCurrency,
		public CurrencyAlpha3|CurrencyMinorLowerLastAlpha3|null $reportingCurrency = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, companyName: string, tradingCurrency: CurrencyAlpha3|CurrencyMinorLowerLastAlpha3, reportingCurrency: CurrencyAlpha3|CurrencyMinorLowerLastAlpha3|null}
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
