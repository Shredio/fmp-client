<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\FmpClient\Payload\EarningsCalendarConfirmed;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class EarningsCalendarConfirmedTest extends TestCase
{

	public function testEarningsCalendarConfirmed(): void
	{
		$client = $this->createClient(
			__DIR__ . '/fixtures/earnings-calendar-confirmed-2026-07-10-to-2026-08-19.json',
			responses: [new MockResponse('[]')],
		);

		$items = [];
		foreach ($client->earningsCalendarConfirmed(new DateTimeImmutable('2026-07-10'), new DateTimeImmutable('2026-08-19')) as $item) {
			$items[] = $item;
		}

		$this->assertCount(8, $items);

		$expectedAllNullsItem = new EarningsCalendarConfirmed(
			symbol: 'ENTA',
			exchange: 'NASDAQ',
			time: null,
			when: null,
			date: '2026-08-10',
			publicationDate: '2026-05-11',
			title: 'Enanta Pharmaceuticals Reports Financial Results for its Fiscal Second Quarter Ended March 31, 2026',
			url: 'https://www.businesswire.com/news/home/20260511989798/en/Enanta-Pharmaceuticals-Reports-Financial-Results-for-its-Fiscal-Second-Quarter-Ended-March-31-2026/',
		);

		$this->assertSame($expectedAllNullsItem->toArray(), $items[0]->toArray());

		$expectedPostMarketItem = new EarningsCalendarConfirmed(
			symbol: 'COLM',
			exchange: 'NASDAQ',
			time: '16:15',
			when: 'post market',
			date: '2026-07-30',
			publicationDate: '2026-04-30',
			title: 'Columbia Sportswear Company Reports First Quarter 2026 Financial Results; Updates Full Year 2026 Financial Outlook',
			url: 'https://www.businesswire.com/news/home/20260430676623/en/Columbia-Sportswear-Company-Reports-First-Quarter-2026-Financial-Results-Updates-Full-Year-2026-Financial-Outlook/',
		);

		$this->assertSame($expectedPostMarketItem->toArray(), $items[1]->toArray());

		$expectedPreMarketItem = new EarningsCalendarConfirmed(
			symbol: 'PHM',
			exchange: 'NYSE',
			time: null,
			when: 'pre market',
			date: '2026-07-22',
			publicationDate: '2026-05-21',
			title: 'PulteGroup\'s Second Quarter 2026 Earnings Release and Webcast Conference Call Scheduled for July 22, 2026',
			url: 'https://www.businesswire.com/news/home/20260521879295/en/PulteGroup%E2%80%99s-Second-Quarter-2026-Earnings-Release-and-Webcast-Conference-Call-Scheduled-for-July-22-2026/',
		);

		$this->assertSame($expectedPreMarketItem->toArray(), $items[2]->toArray());
	}

}
