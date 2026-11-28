<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class InsiderTrade
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $filingDate
	 * @param non-empty-string $transactionDate
	 * @param string $transactionType Empty for form 3 (initial statement of ownership)
	 * @param int|float|null $securitiesOwned Null for some form 3 filings of derivative securities (e.g. convertible notes)
	 * @param string|null $acquisitionOrDisposition A for acquisition, D for disposition, empty for form 3, null like the other transaction fields of a filing FMP sends without them (GETYQ, ARCC)
	 * @param non-empty-string|null $directOrIndirect D for direct, I for indirect ownership, null for some filings without a transaction (e.g. form 3)
	 */
	public function __construct(
		public string $symbol,
		public string $filingDate,
		public string $transactionDate,
		public string $reportingCik,
		public string $companyCik,
		public string $transactionType,
		public int|float|null $securitiesOwned,
		public string $reportingName,
		public string|null $typeOfOwner,
		public string|null $acquisitionOrDisposition,
		public string|null $directOrIndirect,
		public string $formType,
		public int|float|null $securitiesTransacted,
		public float|null $price,
		public string|null $securityName,
		public string $url,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     filingDate: non-empty-string,
	 *     transactionDate: non-empty-string,
	 *     reportingCik: string,
	 *     companyCik: string,
	 *     transactionType: string,
	 *     securitiesOwned: int|float|null,
	 *     reportingName: string,
	 *     typeOfOwner: string|null,
	 *     acquisitionOrDisposition: string|null,
	 *     directOrIndirect: non-empty-string|null,
	 *     formType: string,
	 *     securitiesTransacted: int|float|null,
	 *     price: float|null,
	 *     securityName: string|null,
	 *     url: string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'filingDate' => $this->filingDate,
			'transactionDate' => $this->transactionDate,
			'reportingCik' => $this->reportingCik,
			'companyCik' => $this->companyCik,
			'transactionType' => $this->transactionType,
			'securitiesOwned' => $this->securitiesOwned,
			'reportingName' => $this->reportingName,
			'typeOfOwner' => $this->typeOfOwner,
			'acquisitionOrDisposition' => $this->acquisitionOrDisposition,
			'directOrIndirect' => $this->directOrIndirect,
			'formType' => $this->formType,
			'securitiesTransacted' => $this->securitiesTransacted,
			'price' => $this->price,
			'securityName' => $this->securityName,
			'url' => $this->url,
		];
	}

}
