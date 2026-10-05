<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'cik')]
final readonly class InstitutionalOwnershipFiling
{

	/**
	 * @param non-empty-string $cik CIK of the institutional investor
	 * @param string $name Name of the institutional investor
	 * @param non-empty-string $date Last day of the reported quarter
	 * @param non-empty-string $filingDate Date with a zeroed time part, e.g. "2026-10-05 00:00:00"
	 * @param non-empty-string $acceptedDate Date and time the SEC accepted the filing, e.g. "2026-10-05 10:59:23"
	 * @param string $formType One of 13F-HR, 13F-HR/A, 13F-NT, 13F-NT/A
	 * @param string $link Index page of the filing on the SEC website
	 * @param string $finalLink Primary document of the filing on the SEC website
	 */
	public function __construct(
		public string $cik,
		public string $name,
		public string $date,
		public string $filingDate,
		public string $acceptedDate,
		public string $formType,
		public string $link,
		public string $finalLink,
	)
	{
	}

	/**
	 * @return array{
	 *     cik: non-empty-string,
	 *     name: string,
	 *     date: non-empty-string,
	 *     filingDate: non-empty-string,
	 *     acceptedDate: non-empty-string,
	 *     formType: string,
	 *     link: string,
	 *     finalLink: string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'cik' => $this->cik,
			'name' => $this->name,
			'date' => $this->date,
			'filingDate' => $this->filingDate,
			'acceptedDate' => $this->acceptedDate,
			'formType' => $this->formType,
			'link' => $this->link,
			'finalLink' => $this->finalLink,
		];
	}

}
