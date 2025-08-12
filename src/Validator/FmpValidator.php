<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Validator;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class FmpValidator
{
	private ValidationStrategy $strategy;

	public function __construct(
		private string $name,
		private ?string $context = null,
		bool $isCsv = false,
	)
	{
		$this->strategy = $isCsv 
			? new CsvValidationStrategy() 
			: new JsonValidationStrategy();
	}

	public function withContext(string $context): self
	{
		return new self($this->name, $context, $this->strategy instanceof CsvValidationStrategy);
	}

	public function withCsvFormat(): self
	{
		return new self($this->name, $this->context, true);
	}

	/**
	 * @return mixed[]
	 *
	 * @throws InvalidArgumentException
	 */
	public function getArray(mixed $value): array
	{
		Assert::isArray($value, sprintf('The %s must be an array. Got: %%s', $this->getPath()));

		return $value;
	}

	/**
	 * @param mixed[] $value
	 * @return non-empty-string
	 *
	 * @throws InvalidArgumentException
	 */
	public function getNonEmptyStringInArray(array $value, string $key): string
	{
		$val = $this->getValueFromArray($value, $key);
		Assert::stringNotEmpty($val, sprintf('The %s must be a non-empty-string. Got: %%s', $this->getPath($key)));

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getStringOrNullInArray(array $value, string $key): ?string
	{
		$val = $this->getValueFromArray($value, $key);
		Assert::nullOrString($val, sprintf('The %s must be a string or null. Got: %%s', $this->getPath($key)));

		if ($val === '') {
			return null;
		}

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getOptionalStringInArray(array $value, string $key): ?string
	{
		if (!array_key_exists($key, $value)) {
			return null;
		}

		$val = $value[$key];
		Assert::nullOrString($val, sprintf('The %s must be a string or null. Got: %%s', $this->getPath($key)));

		if ($val === '') {
			return null;
		}

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getIntOrNullInArray(array $value, string $key): ?int
	{
		$val = $this->getValueFromArray($value, $key);
		return $this->strategy->validateInt($val, $this->getPath($key));
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getIntInArray(array $value, string $key): int
	{
		$val = $this->getValueFromArray($value, $key);
		return $this->strategy->validateRequiredInt($val, $this->getPath($key));
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getNumericInArray(array $value, string $key): int|float
	{
		$val = $this->getValueFromArray($value, $key);
		return $this->strategy->validateRequiredNumeric($val, $this->getPath($key));
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getNumericOrNullInArray(array $value, string $key): int|float|null
	{
		$val = $this->getValueFromArray($value, $key);
		return $this->strategy->validateNumeric($val, $this->getPath($key));
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getFloatInArray(array $value, string $key): float
	{
		$val = $this->getValueFromArray($value, $key);
		return $this->strategy->validateFloat($val, $this->getPath($key));
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getBoolInArray(array $value, string $key): bool
	{
		$val = $this->getValueFromArray($value, $key);
		return $this->strategy->validateRequiredBool($val, $this->getPath($key));
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getBoolOrNullInArray(array $value, string $key): ?bool
	{
		$val = $this->getValueFromArray($value, $key);
		return $this->strategy->validateBool($val, $this->getPath($key));
	}

	/**
	 * @param mixed[] $value
	 * @throws InvalidArgumentException
	 */
	public function getValueFromArray(array $value, string $key): mixed
	{
		Assert::keyExists($value, $key, sprintf('The %s must contain a "%s" key.', $this->getPath(), $key));

		return $value[$key];
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function assertMaxLength(string $value, int $max, ?string $key = null): void
	{
		Assert::maxLength($value, $max, sprintf('The %s must not exceed %d characters.', $this->getPath($key), $max));
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function assertLength(string $value, int $length, ?string $key = null): void
	{
		Assert::length($value, $length, sprintf('The %s must have exactly %d characters.', $this->getPath($key), $length));
	}

	private function getPath(?string $key = null): string
	{
		$str = $this->name;

		if ($this->context !== null) {
			$str .= sprintf(' in %s', $this->context);
		}

		if ($key !== null) {
			$str = sprintf('%s of %s', $key, $str);
		}

		return $str;
	}

}
