<?php declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shredio\FmpClient\TypeSchema\RevenueSegments;
use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchema\Conversion\ConversionStrategyFactory;
use Shredio\TypeSchema\Mapper\RegistryClassMapperProvider;

final class RevenueSegmentsTest extends TestCase
{

	/**
	 * @return iterable<string, array{mixed, mixed}>
	 */
	public static function valueProvider(): iterable
	{
		yield 'integer string is cast' => [['WellnessAndLifestyleMember' => '113000'], ['WellnessAndLifestyleMember' => 113000.0]];
		yield 'decimal string is cast' => [['Metrology and inspection' => '513700000.5'], ['Metrology and inspection' => 513700000.5]];
		yield 'negative string is cast' => [['Construction' => '-1000'], ['Construction' => -1000.0]];
		yield 'numbers are kept' => [['Construction' => 146000, 'Rental' => 18000.5], ['Construction' => 146000, 'Rental' => 18000.5]];
		yield 'only strings are cast' => [['Construction' => 146000, 'Rental' => '18000'], ['Construction' => 146000, 'Rental' => 18000.0]];
		yield 'non-numeric string is kept' => [['Mac' => 'n/a'], ['Mac' => 'n/a']];
		yield 'empty string is kept' => [['Mac' => ''], ['Mac' => '']];
		yield 'null is kept' => [['Mac' => null], ['Mac' => null]];
		yield 'empty map is kept' => [[], []];
		yield 'non-array value is kept' => ['113000', '113000'];
	}

	#[DataProvider('valueProvider')]
	public function testCastNumericStrings(mixed $value, mixed $expected): void
	{
		$context = new TypeContext(ConversionStrategyFactory::lenient(), new RegistryClassMapperProvider([]));

		$this->assertSame($expected, RevenueSegments::castNumericStrings($value, $context));
	}

}
