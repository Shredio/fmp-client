<?php declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shredio\FmpClient\TypeSchema\MoneyAmount;
use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchema\Conversion\ConversionStrategyFactory;
use Shredio\TypeSchema\Mapper\RegistryClassMapperProvider;

final class MoneyAmountTest extends TestCase
{

	/**
	 * @return iterable<string, array{mixed, mixed}>
	 */
	public static function valueProvider(): iterable
	{
		yield 'json float just below a whole number' => [4216133870848.9995, 4216133870849];
		yield 'json float just above a whole number' => [533931780000.00006, 533931780000];
		yield 'json float rounded half up' => [10630237599.5, 10630237600];
		yield 'json negative float' => [-509379020000.4, -509379020000];
		yield 'csv decimal string' => ['2497579230.6525', 2497579231];
		yield 'csv negative decimal string' => ['-0.197153', 0];
		yield 'csv exponent string' => ['1.5E+12', 1500000000000];
		yield 'json int is kept' => [3805797200000, 3805797200000];
		yield 'csv integer string is kept' => ['9223372036854775807', '9223372036854775807'];
		yield 'null is kept' => [null, null];
		yield 'empty string is kept' => ['', ''];
		yield 'non-numeric string is kept' => ['n/a', 'n/a'];
		yield 'float beyond 64-bit integer is kept' => [-2.2E+35, -2.2E+35];
		yield 'decimal string beyond 64-bit integer is converted to float and kept' => ['9223372036854775808.5', 9.2233720368547758E+18];
		yield 'infinity is kept' => [INF, INF];
	}

	#[DataProvider('valueProvider')]
	public function testRoundToInt(mixed $value, mixed $expected): void
	{
		$context = new TypeContext(ConversionStrategyFactory::lenient(), new RegistryClassMapperProvider([]));

		$this->assertSame($expected, MoneyAmount::roundToInt($value, $context));
	}

}
