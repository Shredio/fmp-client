<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class IndexConstituent
{

	/**
	 * @param non-empty-string $symbol
	 * @param string $subSector Industry of the company
	 * @param string $headQuarter City and state of the headquarters
	 * @param string|null $dateFirstAdded In the Y-m-d format
	 * @param string $founded A year or a date, the format is not uniform
	 */
	public function __construct(
		public string $symbol,
		public string $name,
		public string $sector,
		public string $subSector,
		public string $headQuarter,
		public ?string $dateFirstAdded,
		public string $cik,
		public string $founded,
	)
	{
	}

	/**
	 * @return array{
	 *     symbol: non-empty-string,
	 *     name: string,
	 *     sector: string,
	 *     subSector: string,
	 *     headQuarter: string,
	 *     dateFirstAdded: string|null,
	 *     cik: string,
	 *     founded: string
	 * }
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'name' => $this->name,
			'sector' => $this->sector,
			'subSector' => $this->subSector,
			'headQuarter' => $this->headQuarter,
			'dateFirstAdded' => $this->dateFirstAdded,
			'cik' => $this->cik,
			'founded' => $this->founded,
		];
	}

}
