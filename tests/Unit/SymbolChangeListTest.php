<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\SymbolChange;
use Tests\TestCase;

final class SymbolChangeListTest extends TestCase
{

	public function testSymbolChangeList(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/symbol-change.json');

		$symbolChanges = iterator_to_array($client->symbolChangeList());

		$this->assertNotEmpty($symbolChanges);
		$this->assertSame((new SymbolChange(
			date: '2025-08-15',
			companyName: 'USBC, Inc. Common Stock',
			oldSymbol: 'KNW',
			newSymbol: 'USBC',
		))->toArray(), $symbolChanges[0]->toArray());
	}

}