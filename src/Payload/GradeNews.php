<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class GradeNews
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $publishedDate Date and time in UTC, e.g. "2026-10-05T14:19:56.000Z"
	 * @param string $newsBaseURL Domain of the publisher, e.g. "thefly.com"
	 * @param string|null $previousGrade Null when the news does not state the previous rating, e.g. for an initiation
	 * @param string $action One of upgrade, downgrade, hold, initialise
	 * @param float $priceWhenPosted Stock price at the time of publication
	 */
	public function __construct(
		public string $symbol,
		public string $publishedDate,
		public string $newsURL,
		public string $newsTitle,
		public string $newsBaseURL,
		public string $newsPublisher,
		public string $newGrade,
		public string|null $previousGrade,
		public string $gradingCompany,
		public string $action,
		public float $priceWhenPosted,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     publishedDate: non-empty-string,
	 *     newsURL: string,
	 *     newsTitle: string,
	 *     newsBaseURL: string,
	 *     newsPublisher: string,
	 *     newGrade: string,
	 *     previousGrade: string|null,
	 *     gradingCompany: string,
	 *     action: string,
	 *     priceWhenPosted: float
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'publishedDate' => $this->publishedDate,
			'newsURL' => $this->newsURL,
			'newsTitle' => $this->newsTitle,
			'newsBaseURL' => $this->newsBaseURL,
			'newsPublisher' => $this->newsPublisher,
			'newGrade' => $this->newGrade,
			'previousGrade' => $this->previousGrade,
			'gradingCompany' => $this->gradingCompany,
			'action' => $this->action,
			'priceWhenPosted' => $this->priceWhenPosted,
		];
	}

}
