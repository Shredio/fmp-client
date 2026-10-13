<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * The API does not return the symbol of the company.
 */
#[CompileObjectMapper]
final readonly class KeyExecutive
{

	/**
	 * @param int|null $pay Annual compensation in the currency given by $currencyPay
	 * @param string|null $gender male or female
	 * @param mixed $titleSince Null in every sampled response, so the type of a filled value is unknown
	 */
	public function __construct(
		public string $title,
		public string $name,
		public ?int $pay,
		public string $currencyPay,
		public ?string $gender,
		public ?int $yearBorn,
		public mixed $titleSince,
		public bool $active,
	)
	{
	}

	/**
	 * @return array{
	 *     title: string,
	 *     name: string,
	 *     pay: int|null,
	 *     currencyPay: string,
	 *     gender: string|null,
	 *     yearBorn: int|null,
	 *     titleSince: mixed,
	 *     active: bool
	 * }
	 */
	public function toArray(): array
	{
		return [
			'title' => $this->title,
			'name' => $this->name,
			'pay' => $this->pay,
			'currencyPay' => $this->currencyPay,
			'gender' => $this->gender,
			'yearBorn' => $this->yearBorn,
			'titleSince' => $this->titleSince,
			'active' => $this->active,
		];
	}

}
