<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Context\TypeContext;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class SharesFloat
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $date
	 * @param non-empty-string|null $source
	 */
	public function __construct(
		public string $symbol,
		public string|null $date,
		public int|float|null $freeFloat,
		public int|float|null $floatShares,
		#[CompilePropertyOptions(before: [self::class, 'castDecimalToInt'])]
		public int $outstandingShares,
		public string|null $source = null,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string|null, freeFloat: int|float|null, floatShares: int|float|null, outstandingShares: int, source: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'freeFloat' => $this->freeFloat,
			'floatShares' => $this->floatShares,
			'outstandingShares' => $this->outstandingShares,
			'source' => $this->source,
		];
	}

	/**
	 * The API sometimes returns outstandingShares as a decimal value (e.g. 56823487.1554),
	 * which the int type rejects. Round such values to the nearest integer.
	 */
	public static function castDecimalToInt(mixed $value, TypeContext $context): mixed
	{
		if (is_float($value)) {
			return (int) round($value);
		}

		if (is_string($value) && str_contains($value, '.')) {
			$float = filter_var($value, FILTER_VALIDATE_FLOAT);
			if ($float !== false) {
				return (int) round($float);
			}
		}

		return $value;
	}

}
