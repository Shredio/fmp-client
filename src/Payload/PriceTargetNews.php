<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class PriceTargetNews
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $publishedDate Date and time in UTC, e.g. "2026-10-05T13:32:00.000Z"
	 * @param string|null $newsTitle Null for some older records
	 * @param string|null $analystName Empty or null when the news does not name the analyst
	 * @param float $priceTarget Price target as published
	 * @param float $adjPriceTarget Price target adjusted for later stock splits
	 * @param float $priceWhenPosted Stock price at the time of publication, adjusted for later stock splits
	 * @param string $newsBaseURL Domain of the publisher, e.g. "streetinsider.com"
	 */
	public function __construct(
		public string $symbol,
		public string $publishedDate,
		public string $newsURL,
		public string|null $newsTitle,
		public string|null $analystName,
		public float $priceTarget,
		public float $adjPriceTarget,
		public float $priceWhenPosted,
		public string $newsPublisher,
		public string $newsBaseURL,
		public string $analystCompany,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     publishedDate: non-empty-string,
	 *     newsURL: string,
	 *     newsTitle: string|null,
	 *     analystName: string|null,
	 *     priceTarget: float,
	 *     adjPriceTarget: float,
	 *     priceWhenPosted: float,
	 *     newsPublisher: string,
	 *     newsBaseURL: string,
	 *     analystCompany: string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'publishedDate' => $this->publishedDate,
			'newsURL' => $this->newsURL,
			'newsTitle' => $this->newsTitle,
			'analystName' => $this->analystName,
			'priceTarget' => $this->priceTarget,
			'adjPriceTarget' => $this->adjPriceTarget,
			'priceWhenPosted' => $this->priceWhenPosted,
			'newsPublisher' => $this->newsPublisher,
			'newsBaseURL' => $this->newsBaseURL,
			'analystCompany' => $this->analystCompany,
		];
	}

}
