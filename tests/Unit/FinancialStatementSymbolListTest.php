<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\FinancialStatementSymbol;
use Tests\TestCase;

final class FinancialStatementSymbolListTest extends TestCase
{

	public function testFinancialStatementSymbolList(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/financial-statement-symbol-list-test.json');

		$symbols = iterator_to_array($client->financialStatementSymbolList());

		$this->assertNotEmpty($symbols);
		$this->assertCount(3, $symbols);
		
		$this->assertSame((new FinancialStatementSymbol(
			symbol: 'HALB',
			companyName: 'Halberd Corporation',
			tradingCurrency: 'USD',
			reportingCurrency: 'USD',
		))->toArray(), $symbols[0]->toArray());

		$this->assertSame((new FinancialStatementSymbol(
			symbol: 'BHUDEVI.BO',
			companyName: 'Bhudevi Infra Projects Ltd.',
			tradingCurrency: 'INR',
			reportingCurrency: null,
		))->toArray(), $symbols[1]->toArray());

		$this->assertSame((new FinancialStatementSymbol(
			symbol: 'SUMXF',
			companyName: 'Supremex Inc.',
			tradingCurrency: 'USD',
			reportingCurrency: 'CAD',
		))->toArray(), $symbols[2]->toArray());
	}

}
