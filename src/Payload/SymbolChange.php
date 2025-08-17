<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class SymbolChange
{

	/**
	 * @param non-empty-string $date
	 * @param non-empty-string $companyName
	 * @param non-empty-string $oldSymbol
	 * @param non-empty-string $newSymbol
	 */
	public function __construct(
		public string $date,
		public string $companyName,
		public string $oldSymbol,
		public string $newSymbol,
	)
	{
	}

	/**
	 * @return array{date: non-empty-string, companyName: non-empty-string, oldSymbol: non-empty-string, newSymbol: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'companyName' => $this->companyName,
			'oldSymbol' => $this->oldSymbol,
			'newSymbol' => $this->newSymbol,
		];
	}

}