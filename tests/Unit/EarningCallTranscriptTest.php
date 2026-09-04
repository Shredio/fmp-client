<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\EarningCallTranscriptDate;
use Tests\TestCase;

final class EarningCallTranscriptTest extends TestCase
{

	public function testTranscriptDates(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/earning-call-transcript-dates-aapl.json');

		$dates = iterator_to_array($client->earningCallTranscriptDates('AAPL'));

		$this->assertCount(84, $dates);
		$this->assertSame((new EarningCallTranscriptDate(
			quarter: 3,
			fiscalYear: 2026,
			date: '2026-07-30',
		))->toArray(), $dates[0]->toArray());
		$this->assertSame((new EarningCallTranscriptDate(
			quarter: 4,
			fiscalYear: 2005,
			date: '2005-10-13',
		))->toArray(), $dates[83]->toArray());
	}

	public function testTranscript(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/earning-call-transcript-aapl-2026-q3.json');

		$transcript = $client->earningCallTranscript('AAPL', 2026, 3);

		$this->assertNotNull($transcript);
		$this->assertSame('AAPL', $transcript->symbol);
		$this->assertSame(Period::Q3, $transcript->period);
		$this->assertSame(2026, $transcript->year);
		$this->assertSame('2026-07-30', $transcript->date);
		$this->assertStringStartsWith('Suhasini Chandramouli: Good afternoon', $transcript->content);
		$this->assertStringEndsWith('We do appreciate your participation.', $transcript->content);
	}

	public function testTranscriptNotFound(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->earningCallTranscript('AAPL', 1990, 1));
	}

}
