<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * Unlike SenateTrade the latest feed covers every disclosed asset, so the symbol may be empty, and it does not
 * return the capital gains flag.
 */
#[CompileObjectMapper]
final readonly class LatestSenateTrade
{

	/**
	 * @param string $symbol Empty for assets without a ticker symbol, e.g. funds or municipal bonds
	 * @param non-empty-string|null $senateID Bioguide identifier
	 * @param non-empty-string $disclosureDate
	 * @param non-empty-string $transactionDate
	 * @param string $district Two letter state code, may be empty
	 * @param string $owner Self, Spouse, Joint, Child, may be empty
	 * @param string $assetType For example Stock, Stock Option, Other, may be empty
	 * @param string $type For example Purchase, Sale, Sale (Full), Sale (Partial), Exchange
	 * @param non-empty-string $amount Reported range, for example "$15,001 - $50,000"
	 */
	public function __construct(
		public string $symbol,
		public string|null $senateID,
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
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: string,
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
	 *     link: string
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
		];
	}

}
