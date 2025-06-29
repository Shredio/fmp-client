<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

final readonly class DividendsCalendarItem
{
    public function __construct(
        public string $symbol,
        public string $date,
        public string|null $recordDate,
        public string|null $paymentDate,
        public string|null $declarationDate,
        public float $adjDividend,
        public float $dividend,
        public float $yield,
        public string $frequency,
    ) {}

    /**
     * @return array{symbol: string, date: string, recordDate: string|null, paymentDate: string|null, declarationDate: string|null, adjDividend: float, dividend: float, yield: float, frequency: string}
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
