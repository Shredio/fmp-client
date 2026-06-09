<?php declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shredio\FmpClient\TypeSchema\NullAsZeroConversion;
use Shredio\TypeSchema\Conversion\ConversionStrategy;

final class NullAsZeroConversionTest extends TestCase
{

	public function testNullIsAlwaysConvertedToZero(): void
	{
		$conversion = new NullAsZeroConversion($this->createSpyInner());

		$this->assertSame(0.0, $conversion->float(null));
		$this->assertSame(0, $conversion->int(null));
	}

	/**
	 * @return iterable<string, array{string}>
	 */
	public static function infinityValueProvider(): iterable
	{
		yield 'infinite' => ['infinite'];
		yield '-infinite' => ['-infinite'];
		yield 'Infinite' => ['Infinite'];
		yield 'infinity' => ['infinity'];
		yield '-infinity' => ['-infinity'];
		yield 'Infinity' => ['Infinity'];
		yield '-Infinity' => ['-Infinity'];
		yield 'INFINITY' => ['INFINITY'];
	}

	#[DataProvider('infinityValueProvider')]
	public function testInfinityIsConvertedToZeroWhenEnabled(string $value): void
	{
		$conversion = new NullAsZeroConversion($this->createSpyInner(), handleInfinity: true);

		$this->assertSame(0.0, $conversion->float($value));
	}

	#[DataProvider('infinityValueProvider')]
	public function testInfinityIsDelegatedWhenDisabled(string $value): void
	{
		$inner = $this->createSpyInner();
		$conversion = new NullAsZeroConversion($inner);

		$this->assertSame(self::INNER_SENTINEL, $conversion->float($value));
		$this->assertSame([$value], $inner->floatCalls);
	}

	/**
	 * @return iterable<string, array{string}>
	 */
	public static function nanValueProvider(): iterable
	{
		yield 'nan' => ['nan'];
		yield 'NaN' => ['NaN'];
		yield 'NAN' => ['NAN'];
		yield 'n/a' => ['n/a'];
		yield 'N/A' => ['N/A'];
		yield 'na' => ['na'];
		yield 'NA' => ['NA'];
	}

	#[DataProvider('nanValueProvider')]
	public function testNanIsConvertedToZeroWhenEnabled(string $value): void
	{
		$conversion = new NullAsZeroConversion($this->createSpyInner(), handleNaN: true);

		$this->assertSame(0.0, $conversion->float($value));
	}

	#[DataProvider('nanValueProvider')]
	public function testNanIsDelegatedWhenDisabled(string $value): void
	{
		$inner = $this->createSpyInner();
		$conversion = new NullAsZeroConversion($inner);

		$this->assertSame(self::INNER_SENTINEL, $conversion->float($value));
		$this->assertSame([$value], $inner->floatCalls);
	}

	public function testNanIsNotHandledWhenOnlyInfiniteEnabled(): void
	{
		$inner = $this->createSpyInner();
		$conversion = new NullAsZeroConversion($inner, handleInfinity: true);

		$this->assertSame(self::INNER_SENTINEL, $conversion->float('nan'));
		$this->assertSame(['nan'], $inner->floatCalls);
	}

	public function testInfiniteIsNotHandledWhenOnlyNanEnabled(): void
	{
		$inner = $this->createSpyInner();
		$conversion = new NullAsZeroConversion($inner, handleNaN: true);

		$this->assertSame(self::INNER_SENTINEL, $conversion->float('Infinity'));
		$this->assertSame(['Infinity'], $inner->floatCalls);
	}

	public function testRegularNumericStringIsDelegated(): void
	{
		$inner = $this->createSpyInner();
		$conversion = new NullAsZeroConversion($inner, handleNaN: true, handleInfinity: true);

		$this->assertSame(self::INNER_SENTINEL, $conversion->float('1.5'));
		$this->assertSame(['1.5'], $inner->floatCalls);
	}

	public const float INNER_SENTINEL = -987.654;

	private function createSpyInner(): ConversionStrategy
	{
		return new class implements ConversionStrategy {

			/** @var list<mixed> */
			public array $floatCalls = [];

			public function string(mixed $value): ?string
			{
				return null;
			}

			public function int(mixed $value): ?int
			{
				return null;
			}

			public function float(mixed $value): ?float
			{
				$this->floatCalls[] = $value;

				return NullAsZeroConversionTest::INNER_SENTINEL;
			}

			public function bool(mixed $value): ?bool
			{
				return null;
			}

			public function null(mixed $value): null|false
			{
				return null;
			}

			public function array(mixed $value, bool $preserveKeys): ?array
			{
				return null;
			}

			public function isStrictForObject(string $className): bool
			{
				return false;
			}

		};
	}

}
