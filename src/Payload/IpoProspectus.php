<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\FmpClient\TypeSchema\MoneyAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * A registration statement or prospectus filed with the SEC for an offering: an `S-1` or `F-1` (and their `/A`
 * amendments) registers it, a `424B4` is the final prospectus with the offering price. The feed carries listed
 * companies' resale and shelf registrations too, so a filing alone does not mean an upcoming IPO.
 */
#[CompileObjectMapper(identifier: 'symbol')]
final readonly class IpoProspectus
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $acceptedDate In the Y-m-d format, the date the SEC accepted the filing
	 * @param non-empty-string $filingDate In the Y-m-d format, it may fall on the day after $acceptedDate
	 * @param non-empty-string $ipoDate In the Y-m-d format, often long before the filing for a listed company's registration
	 * @param string $cik The company's central index key, zero-padded to ten digits
	 * @param float $pricePublicPerShare The offering price per share in US dollars
	 * @param int $pricePublicTotal The gross proceeds in US dollars
	 * @param float|null $discountsAndCommissionsPerShare The underwriting discount per share in US dollars
	 * @param int|null $discountsAndCommissionsTotal The underwriting discount in US dollars, as sent (a few records carry the per-share figure here)
	 * @param float $proceedsBeforeExpensesPerShare The proceeds per share after the discount in US dollars
	 * @param int $proceedsBeforeExpensesTotal The proceeds after the discount in US dollars
	 * @param non-empty-string $form `S-1`, `S-1/A`, `F-1`, `F-1/A` or `424B4`
	 * @param non-empty-string $url The filing's document on sec.gov
	 */
	public function __construct(
		public string $symbol,
		public string $acceptedDate,
		public string $filingDate,
		public string $ipoDate,
		public string $cik,
		public float $pricePublicPerShare,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $pricePublicTotal,
		public ?float $discountsAndCommissionsPerShare,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public ?int $discountsAndCommissionsTotal,
		public float $proceedsBeforeExpensesPerShare,
		#[CompilePropertyOptions(before: [MoneyAmount::class, 'roundToInt'])]
		public int $proceedsBeforeExpensesTotal,
		public string $form,
		public string $url,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     acceptedDate: non-empty-string,
	 *     filingDate: non-empty-string,
	 *     ipoDate: non-empty-string,
	 *     cik: string,
	 *     pricePublicPerShare: float,
	 *     pricePublicTotal: int,
	 *     discountsAndCommissionsPerShare: float|null,
	 *     discountsAndCommissionsTotal: int|null,
	 *     proceedsBeforeExpensesPerShare: float,
	 *     proceedsBeforeExpensesTotal: int,
	 *     form: non-empty-string,
	 *     url: non-empty-string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'acceptedDate' => $this->acceptedDate,
			'filingDate' => $this->filingDate,
			'ipoDate' => $this->ipoDate,
			'cik' => $this->cik,
			'pricePublicPerShare' => $this->pricePublicPerShare,
			'pricePublicTotal' => $this->pricePublicTotal,
			'discountsAndCommissionsPerShare' => $this->discountsAndCommissionsPerShare,
			'discountsAndCommissionsTotal' => $this->discountsAndCommissionsTotal,
			'proceedsBeforeExpensesPerShare' => $this->proceedsBeforeExpensesPerShare,
			'proceedsBeforeExpensesTotal' => $this->proceedsBeforeExpensesTotal,
			'form' => $this->form,
			'url' => $this->url,
		];
	}

}
