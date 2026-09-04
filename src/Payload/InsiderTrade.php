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
	 * @param string $acquisitionOrDisposition A for acquisition, D for disposition, empty for form 3
	 * @param non-empty-string $directOrIndirect D for direct, I for indirect ownership
	 */
	public function __construct(
		public string $symbol,
		public string $filingDate,
		public string $transactionDate,
		public string $reportingCik,
		public string $companyCik,
		public string $transactionType,
		public int|float $securitiesOwned,
		public string $reportingName,
		public string $typeOfOwner,
		public string $acquisitionOrDisposition,
		public string $directOrIndirect,
		public string $formType,
		public int|float $securitiesTransacted,
		public float $price,
		public string $securityName,
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
	 *     securitiesOwned: int|float,
	 *     reportingName: string,
	 *     typeOfOwner: string,
	 *     acquisitionOrDisposition: string,
	 *     directOrIndirect: non-empty-string,
	 *     formType: string,
	 *     securitiesTransacted: int|float,
	 *     price: float,
	 *     securityName: string,
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
