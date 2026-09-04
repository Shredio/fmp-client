<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class SenateTrade
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string|null $senateID Bioguide identifier, null for senators no longer in office
	 * @param non-empty-string $disclosureDate
	 * @param non-empty-string $transactionDate
	 * @param string $district Two letter state code, may be empty
	 * @param string $owner Self, Spouse, Joint, Child, may be empty
	 * @param non-empty-string $amount Reported range, for example "$15,001 - $50,000"
	 * @param string|null $capitalGainsOver200USD "True" or "False" as returned by the API
	 */
	public function __construct(
		public string $symbol,
		public ?string $senateID,
		public string $disclosureDate,
		public string $transactionDate,
		public string $firstName,
		public string $lastName,
		public string $office,
		public string $district,
		public string $owner,
		public string $assetDescription,
		public string $assetType,
		public string $type,
		public string $amount,
		public string $comment,
		public string $link,
		public ?string $capitalGainsOver200USD = null,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     senateID: non-empty-string|null,
	 *     disclosureDate: non-empty-string,
	 *     transactionDate: non-empty-string,
	 *     firstName: string,
	 *     lastName: string,
	 *     office: string,
	 *     district: string,
	 *     owner: string,
	 *     assetDescription: string,
	 *     assetType: string,
	 *     type: string,
	 *     amount: non-empty-string,
	 *     comment: string,
	 *     link: string,
	 *     capitalGainsOver200USD: string|null
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'senateID' => $this->senateID,
			'disclosureDate' => $this->disclosureDate,
			'transactionDate' => $this->transactionDate,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'office' => $this->office,
			'district' => $this->district,
			'owner' => $this->owner,
			'assetDescription' => $this->assetDescription,
			'assetType' => $this->assetType,
			'type' => $this->type,
			'amount' => $this->amount,
			'comment' => $this->comment,
			'link' => $this->link,
			'capitalGainsOver200USD' => $this->capitalGainsOver200USD,
		];
	}

}
