<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\HolidayByExchange;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class HolidaysByExchangeTest extends TestCase
{

	public function testHolidaysWithoutRangeLeaveTheDefaultWindowToTheApi(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/holidays-by-exchange.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		iterator_to_array($client->holidaysByExchange('NASDAQ'));

		$this->assertSame(
			'https://financialmodelingprep.com/stable/holidays-by-exchange?exchange=NASDAQ&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

	public function testUpcomingHolidaysOfRange(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/holidays-by-exchange-upcoming.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$holidays = iterator_to_array($client->holidaysByExchange(
			'NASDAQ',
			from: new DateTimeImmutable('2026-11-04'),
			to: new DateTimeImmutable('2027-11-04'),
		));

		$this->assertSame(
			'https://financialmodelingprep.com/stable/holidays-by-exchange?exchange=NASDAQ&from=2026-11-04&to=2027-11-04&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame([
			['2027-09-06', 'Labor Day', true, null],
			['2027-07-05', 'Independence Day', true, null],
			['2027-06-18', 'Juneteenth', true, null],
			['2027-05-31', 'Memorial Day', true, null],
			['2027-03-26', 'Good Friday', true, null],
			['2027-02-15', 'Presidents\' Day', true, null],
			['2027-01-18', 'Martin Luther King, Jr. Day', true, null],
			['2027-01-01', 'New Year\'s Day', true, null],
			['2026-12-25', 'Christmas', true, null],
			['2026-12-24', 'Christmas', null, false],
			['2026-11-27', 'Thanksgiving Day', null, false],
			['2026-11-26', 'Thanksgiving Day', true, null],
		], array_map(
			static fn (HolidayByExchange $holiday): array => [$holiday->date, $holiday->name, $holiday->isClosed, $holiday->isFullyClosed],
			$holidays,
		));
		$this->assertSame((new HolidayByExchange(
			exchange: 'NASDAQ',
			date: '2026-11-27',
			name: 'Thanksgiving Day',
			isClosed: null,
			adjOpenTime: null,
			adjCloseTime: '13:00',
			isFullyClosed: false,
		))->toArray(), $holidays[10]->toArray());
	}

	public function testHolidaysByExchange(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/holidays-by-exchange.json');

		$holidays = iterator_to_array($client->holidaysByExchange('NASDAQ'));

		$this->assertNotEmpty($holidays);
		$this->assertContainsOnlyInstancesOf(HolidayByExchange::class, $holidays);

		$this->assertSame((new HolidayByExchange(
			exchange: 'NASDAQ',
			date: '2026-05-25',
			name: 'Memorial Day',
			isClosed: true,
			adjOpenTime: null,
			adjCloseTime: null,
			isFullyClosed: null,
		))->toArray(), $holidays[0]->toArray());
	}

	public function testHolidaysByExchangeEarlyClose(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/holidays-by-exchange.json');

		$holidays = iterator_to_array($client->holidaysByExchange('NASDAQ'));

		$earlyClose = null;
		foreach ($holidays as $holiday) {
			if ($holiday->isFullyClosed !== null) {
				$earlyClose = $holiday;
				break;
			}
		}

		$this->assertNotNull($earlyClose);
		$this->assertSame((new HolidayByExchange(
			exchange: 'NASDAQ',
			date: '2025-12-24',
			name: 'Christmas Early Close',
			isClosed: null,
			adjOpenTime: null,
			adjCloseTime: '13:00',
			isFullyClosed: false,
		))->toArray(), $earlyClose->toArray());
	}

}
