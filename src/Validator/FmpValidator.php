<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Validator;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class FmpValidator
{

	public function __construct(
		private string $name,
		private ?string $context = null,
		private bool $isCsv = false,
	)
	{
	}

	public function withContext(string $context): self
	{
		return new self($this->name, $context, $this->isCsv);
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
		
		if ($val === null || ($this->isCsv && $val === '')) {
			return null;
		}
		
		if ($this->isCsv) {
			Assert::nullOrIntegerish($val, sprintf('The %s must be an integerish or null. Got: %%s', $this->getPath($key)));

			return (int) $val;
		}

		Assert::nullOrInteger($val, sprintf('The %s must be an integer or null. Got: %%s', $this->getPath($key)));

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getIntInArray(array $value, string $key): int
	{
		$val = $this->getValueFromArray($value, $key);
		
		if ($this->isCsv) {
			Assert::integerish($val, sprintf('The %s must be an integerish. Got: %%s', $this->getPath($key)));

			return (int) $val;
		}

		Assert::integer($val, sprintf('The %s must be an integer. Got: %%s', $this->getPath($key)));

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getNumericInArray(array $value, string $key): int|float
	{
		$val = $this->getValueFromArray($value, $key);
		
		Assert::numeric($val, sprintf('The %s must be numeric. Got: %%s', $this->getPath($key)));

		if (is_string($val)) {
			return str_contains($val, '.') ? (float) $val : (int) $val;
		}

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getNumericOrNullInArray(array $value, string $key): int|float|null
	{
		$val = $this->getValueFromArray($value, $key);
		
		if ($val === null || ($this->isCsv && $val === '')) {
			return null;
		}
		
		Assert::numeric($val, sprintf('The %s must be numeric or null. Got: %%s', $this->getPath($key)));

		if (is_string($val)) {
			return str_contains($val, '.') ? (float) $val : (int) $val;
		}

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getFloatInArray(array $value, string $key): float
	{
		$val = $this->getValueFromArray($value, $key);
		
		Assert::numeric($val, sprintf('The %s must be numeric. Got: %%s', $this->getPath($key)));

		return (float) $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getBoolInArray(array $value, string $key): bool
	{
		$val = $this->getValueFromArray($value, $key);
		Assert::boolean($val, sprintf('The %s must be a boolean. Got: %%s', $this->getPath($key)));

		return $val;
	}

	/**
	 * @param mixed[] $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function getBoolOrNullInArray(array $value, string $key): ?bool
	{
		$val = $this->getValueFromArray($value, $key);
		
		if ($val === null || ($this->isCsv && $val === '')) {
			return null;
		}
		
		if ($this->isCsv && is_string($val)) {
			return filter_var($val, FILTER_VALIDATE_BOOL);
		}
		
		Assert::nullOrBoolean($val, sprintf('The %s must be a boolean or null. Got: %%s', $this->getPath($key)));

		return $val;
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
