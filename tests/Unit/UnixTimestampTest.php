<?php declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shredio\FmpClient\TypeSchema\UnixTimestamp;
use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchema\Conversion\ConversionStrategyFactory;
use Shredio\TypeSchema\Mapper\RegistryClassMapperProvider;

final class UnixTimestampTest extends TestCase
{

	/**
	 * @return iterable<string, array{mixed, mixed}>
	 */
	public static function valueProvider(): iterable
	{
		yield 'json float with milliseconds' => [1794420455.449, 1794420455];
		yield 'json float just below the next second' => [1768852017.999, 1768852017];
		yield 'json int is kept' => [1794420456, 1794420456];
		yield 'null is kept' => [null, null];
		yield 'string is kept' => ['1794420455.449', '1794420455.449'];
		yield 'float beyond 64-bit integer is kept' => [2.2E+35, 2.2E+35];
		yield 'infinity is kept' => [INF, INF];
	}

	#[DataProvider('valueProvider')]
	public function testToWholeSeconds(mixed $value, mixed $expected): void
	{
		$context = new TypeContext(ConversionStrategyFactory::lenient(), new RegistryClassMapperProvider([]));

		$this->assertSame($expected, UnixTimestamp::toWholeSeconds($value, $context));
	}

}
