<?php declare(strict_types = 1);

namespace Tests\Unit;

use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use PrinsFrank\Standards\Currency\MinorUnits\CurrencyMinorLowerLastAlpha3;
use Shredio\FmpClient\Payload\FinancialStatementSymbol;
use Tests\TestCase;

final class FinancialStatementSymbolListTest extends TestCase
{

	public function testFinancialStatementSymbolList(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/financial-statement-symbol-list-test.json');

		$symbols = iterator_to_array($client->financialStatementSymbolList());

		$this->assertNotEmpty($symbols);
		$this->assertCount(4, $symbols);
		
		$this->assertSame((new FinancialStatementSymbol(
			symbol: 'HALB',
			companyName: 'Halberd Corporation',
			tradingCurrency: CurrencyAlpha3::US_Dollar,
			reportingCurrency: CurrencyAlpha3::US_Dollar,
		))->toArray(), $symbols[0]->toArray());

		$this->assertSame((new FinancialStatementSymbol(
			symbol: 'BHUDEVI.BO',
			companyName: 'Bhudevi Infra Projects Ltd.',
			tradingCurrency: CurrencyAlpha3::Indian_Rupee,
			reportingCurrency: null,
		))->toArray(), $symbols[1]->toArray());

		$this->assertSame((new FinancialStatementSymbol(
			symbol: 'SUMXF',
			companyName: 'Supremex Inc.',
			tradingCurrency: CurrencyAlpha3::US_Dollar,
			reportingCurrency: CurrencyAlpha3::Canadian_Dollar,
		))->toArray(), $symbols[2]->toArray());

		$this->assertSame((new FinancialStatementSymbol(
			symbol: 'HUM.L',
			companyName: 'Hummingbird Resources PLC',
			tradingCurrency: CurrencyMinorLowerLastAlpha3::Penny_Sterling,
			reportingCurrency: CurrencyAlpha3::US_Dollar,
		))->toArray(), $symbols[3]->toArray());
	}

}
