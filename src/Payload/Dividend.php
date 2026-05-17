<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class Dividend
{
    /**
     * @param non-empty-string $symbol
	 * @param non-empty-string $date
	 * @param non-empty-string|null $recordDate
	 * @param non-empty-string|null $paymentDate
	 * @param non-empty-string|null $declarationDate
     */
    public function __construct(
        public string $symbol,
        public string $date,
        public ?string $recordDate,
        public ?string $paymentDate,
        public ?string $declarationDate,
        public ?float $adjDividend,
        public ?float $dividend,
        public float $yield,
        public string $frequency,
    ) {}

    /**
     * @return array{symbol: non-empty-string, date: string, recordDate: non-empty-string|null, paymentDate: non-empty-string|null, declarationDate: non-empty-string|null, adjDividend: float|null, dividend: float|null, yield: float, frequency: string}
     */
    public function toArray(): array
    {
        return [
            'symbol' => $this->symbol,
            'date' => $this->date,
            'recordDate' => $this->recordDate,
            'paymentDate' => $this->paymentDate,
            'declarationDate' => $this->declarationDate,
            'adjDividend' => $this->adjDividend,
            'dividend' => $this->dividend,
            'yield' => $this->yield,
            'frequency' => $this->frequency,
        ];
    }
}
