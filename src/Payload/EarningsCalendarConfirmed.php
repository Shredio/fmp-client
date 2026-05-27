<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class EarningsCalendarConfirmed
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $exchange
	 * @param non-empty-string|null $time
	 * @param non-empty-string|null $when
	 * @param non-empty-string $date
	 * @param non-empty-string $publicationDate
	 * @param non-empty-string $title
	 * @param non-empty-string $url
	 */
	public function __construct(
		public string $symbol,
		public string $exchange,
		public ?string $time,
		public ?string $when,
		public string $date,
		public string $publicationDate,
		public string $title,
		public string $url,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, exchange: non-empty-string, time: non-empty-string|null, when: non-empty-string|null, date: non-empty-string, publicationDate: non-empty-string, title: non-empty-string, url: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'exchange' => $this->exchange,
			'time' => $this->time,
			'when' => $this->when,
			'date' => $this->date,
			'publicationDate' => $this->publicationDate,
			'title' => $this->title,
			'url' => $this->url,
		];
	}

}
