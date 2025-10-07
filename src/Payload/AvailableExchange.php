<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'exchange')]
final readonly class AvailableExchange
{

	/** @var non-empty-string|null */
	public ?string $symbolSuffix;

	/**
	 * @param non-empty-string $exchange
	 * @param non-empty-string $name
	 * @param non-empty-string|null $countryName
	 * @param non-empty-string|null $countryCode
	 * @param non-empty-string|null $symbolSuffix
	 * @param non-empty-string|null $delay
	 */
	public function __construct(
		public string $exchange,
		public string $name,
		public ?string $countryName = null,
		public ?string $countryCode = null,
		?string $symbolSuffix = null,
		public ?string $delay = null,
	)
	{
		$this->symbolSuffix = $symbolSuffix === 'N/A' ? null : $symbolSuffix;
	}

	/**
	 * @return array{exchange: non-empty-string, name: non-empty-string, countryName: non-empty-string|null, countryCode: non-empty-string|null, symbolSuffix: non-empty-string|null, delay: non-empty-string|null}
	 */
	public function toArray(): array
	{
		return [
			'exchange' => $this->exchange,
			'name' => $this->name,
			'countryName' => $this->countryName,
			'countryCode' => $this->countryCode,
			'symbolSuffix' => $this->symbolSuffix,
			'delay' => $this->delay,
		];
	}

}
