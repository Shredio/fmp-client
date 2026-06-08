<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'exchange')]
final readonly class HolidayByExchange
{

	/**
	 * @param non-empty-string $exchange
	 * @param non-empty-string $date
	 * @param non-empty-string $name
	 * @param non-empty-string|null $adjOpenTime
	 * @param non-empty-string|null $adjCloseTime
	 */
	public function __construct(
		public string $exchange,
		public string $date,
		public string $name,
		public ?bool $isClosed,
		public ?string $adjOpenTime,
		public ?string $adjCloseTime,
		public ?bool $isFullyClosed = null,
	)
	{
	}

	/**
	 * @return array{exchange: non-empty-string, date: non-empty-string, name: non-empty-string, isClosed: bool|null, adjOpenTime: non-empty-string|null, adjCloseTime: non-empty-string|null, isFullyClosed: bool|null}
	 */
	public function toArray(): array
	{
		return [
			'exchange' => $this->exchange,
			'date' => $this->date,
			'name' => $this->name,
			'isClosed' => $this->isClosed,
			'adjOpenTime' => $this->adjOpenTime,
			'adjCloseTime' => $this->adjCloseTime,
			'isFullyClosed' => $this->isFullyClosed,
		];
	}

}
