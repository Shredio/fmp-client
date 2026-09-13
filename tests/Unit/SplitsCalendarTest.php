<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\StockSplit;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class SplitsCalendarTest extends TestCase
{

	public function testSplitsCalendar(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/splits-calendar-2021-01-07-to-2021-01-10.json');

		$splits = [];
		foreach ($client->splitsCalendar(new DateTimeImmutable('2021-01-07'), new DateTimeImmutable('2021-01-10')) as $split) {
			$splits[] = $split;
		}

		$this->assertNotEmpty($splits);
		$this->assertCount(22, $splits);

		$expectedFirstSplit = new StockSplit(
			symbol: 'IPCALAB.BO',
			date: '2021-01-10',
			numerator: 2,
			denominator: 1,
			splitType: 'stock-split',
		);

		$this->assertSame($expectedFirstSplit->toArray(), $splits[0]->toArray());

		$expectedStockDividendSplit = new StockSplit(
			symbol: '6251.TW',
			date: '2021-01-07',
			numerator: 223,
			denominator: 250,
			splitType: 'stock-dividend',
		);

		$this->assertSame($expectedStockDividendSplit->toArray(), $splits[11]->toArray());

		$expectedNullSplitTypeSplit = new StockSplit(
			symbol: '7516.TWO',
			date: '2021-01-07',
			numerator: 2404,
			denominator: 3125,
			splitType: null,
		);

		$this->assertSame($expectedNullSplitTypeSplit->toArray(), $splits[16]->toArray());

		$expectedLastSplit = new StockSplit(
			symbol: '0208.KL',
			date: '2021-01-07',
			numerator: 2,
			denominator: 1,
			splitType: 'stock-split',
		);

		$this->assertSame($expectedLastSplit->toArray(), $splits[21]->toArray());
	}

	public function testSplitsCalendarWalksPastAnEmptyTailOfTheWindow(): void
	{
		// FMP fills at most ~90 days before `to`, so a window ending far ahead answers with an empty first page;
		// the walk has to step back and fetch the splits that sit earlier in the window.
		$requested = [];
		$httpClient = new MockHttpClient(static function (string $method, string $url) use (&$requested): MockResponse {
			parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
			$requested[] = sprintf('%s..%s', $query['from'], $query['to']);

			return $query['to'] === '2021-01-10'
				? MockResponse::fromFile(__DIR__ . '/fixtures/splits-calendar-2021-01-07-to-2021-01-10.json')
				: new MockResponse('[]');
		});
		$client = new SymfonyFmpClient($httpClient, 'SECRET', null, strictMode: true);

		$splits = iterator_to_array(
			$client->splitsCalendar(new DateTimeImmutable('2021-01-07'), new DateTimeImmutable('2021-04-10')),
			false,
		);

		$this->assertSame(['2021-01-07..2021-04-10', '2021-01-07..2021-01-10'], $requested);
		$this->assertCount(22, $splits);
		$this->assertSame('IPCALAB.BO', $splits[0]->symbol);
		$this->assertSame('0208.KL', $splits[21]->symbol);
	}

}
