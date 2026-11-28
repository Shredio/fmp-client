<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class Grade
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 * @param string|null $previousGrade Null when FMP sends none (META, November 2026)
	 */
	public function __construct(
		public string $symbol,
		public string $date,
		public string $gradingCompany,
		public string|null $previousGrade,
		public string $newGrade,
		public string $action,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, date: non-empty-string, gradingCompany: string, previousGrade: string|null, newGrade: string, action: string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'date' => $this->date,
			'gradingCompany' => $this->gradingCompany,
			'previousGrade' => $this->previousGrade,
			'newGrade' => $this->newGrade,
			'action' => $this->action,
		];
	}

}
