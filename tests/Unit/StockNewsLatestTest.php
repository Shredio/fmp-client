<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\StockNews;
use Tests\TestCase;

final class StockNewsLatestTest extends TestCase
{

	public function testStockNewsLatest(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/stock-news.json');

		$stockNews = iterator_to_array($client->stockNewsLatest(20));

		$this->assertNotEmpty($stockNews);
		$this->assertSame((new StockNews(
			symbol: 'PCAR',
			publishedDate: '2025-09-11 16:52:00',
			publisher: 'PRNewsWire',
			title: 'PACCAR offers Allison Neutral at Stop Technology as Standard Offering for Vehicles Equipped with Allison Rugged Duty Series™ Transmissions',
			image: 'https://images.financialmodelingprep.com/news/paccar-offers-allison-neutral-at-stop-technology-as-standard-20250911.jpg',
			site: 'prnewswire.com',
			text: 'INDIANAPOLIS , Sept. 11, 2025 /PRNewswire/ -- Allison Transmission is proud to announce that its Neutral at Stop fuel saving technology is now the standard offering on Kenworth and Peterbilt models equipped with Allison 4700 Rugged Duty Series™ transmissions.',
			url: 'https://www.prnewswire.com/news-releases/paccar-offers-allison-neutral-at-stop-technology-as-standard-offering-for-vehicles-equipped-with-allison-rugged-duty-series-transmissions-302554450.html',
		))->toArray(), $stockNews[0]->toArray());
	}

	public function testStockNewsLatestWithParameters(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/stock-news.json');

		$stockNews = iterator_to_array($client->stockNewsLatest(10, 1));

		$this->assertNotEmpty($stockNews);
		$this->assertInstanceOf(StockNews::class, $stockNews[0]);
	}

}