<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\PriceTargetNews;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class PriceTargetNewsTest extends TestCase
{

	public function testPriceTargetNews(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/price-target-news-aapl.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$news = iterator_to_array($client->priceTargetNews('AAPL', limit: 100));

		$this->assertCount(100, $news);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/price-target-news?symbol=AAPL&page=0&limit=100&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new PriceTargetNews(
			symbol: 'AAPL',
			publishedDate: '2026-10-01T12:01:10.000Z',
			newsURL: 'https://thefly.com/ajax/news_get.php?id=4433851',
			newsTitle: 'Apple price target lowered to $355 from $360 at Morgan Stanley',
			analystName: '',
			priceTarget: 355.0,
			adjPriceTarget: 355.0,
			priceWhenPosted: 330.26,
			newsPublisher: 'TheFly',
			newsBaseURL: 'thefly.com',
			analystCompany: 'Morgan Stanley',
		))->toArray(), $news[0]->toArray());
	}

	public function testOlderPriceTargetNewsWithoutAnalystNameOrTitle(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/price-target-news-aapl-page-1.json');

		$news = iterator_to_array($client->priceTargetNews('AAPL', 1, 100));

		$this->assertCount(100, $news);
		$this->assertSame((new PriceTargetNews(
			symbol: 'AAPL',
			publishedDate: '2023-02-03T09:31:00.000Z',
			newsURL: 'https://www.benzinga.com/news/23/02/30707241/da-davidson-maintains-buy-on-apple-raises-price-target-to-173',
			newsTitle: 'DA Davidson Maintains Buy on Apple, Raises Price Target to $173',
			analystName: null,
			priceTarget: 173.0,
			adjPriceTarget: 173.0,
			priceWhenPosted: 157.0901,
			newsPublisher: 'Benzinga',
			newsBaseURL: 'benzinga.com',
			analystCompany: 'D.A. Davidson',
		))->toArray(), $news[75]->toArray());
		$this->assertSame((new PriceTargetNews(
			symbol: 'AAPL',
			publishedDate: '2022-10-28T00:00:00.000Z',
			newsURL: 'https://www.marketwatch.com/articles/apple-earnings-stock-value-51666987800',
			newsTitle: null,
			analystName: 'Pierre Ferragu',
			priceTarget: 155.0,
			adjPriceTarget: 155.0,
			priceWhenPosted: 155.74,
			newsPublisher: 'MarketWatch',
			newsBaseURL: 'marketwatch.com',
			analystCompany: 'New Street',
		))->toArray(), $news[93]->toArray());
	}

	public function testPriceTargetNewsWithoutCoverage(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->priceTargetNews('SAP.DE')));
	}

	public function testPriceTargetLatestNews(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/price-target-latest-news.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$news = iterator_to_array($client->priceTargetLatestNews(limit: 100));

		$this->assertCount(100, $news);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/price-target-latest-news?page=0&limit=100&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new PriceTargetNews(
			symbol: 'UCTT',
			publishedDate: '2026-10-05T13:32:00.000Z',
			newsURL: 'https://www.streetinsider.com/Analyst+Comments/Ultra+Clean+%28UCTT%29+PT+Lowered+to+%24135+at+UBS%2C+Buy+Rating+Maintained/27146834.html',
			newsTitle: 'Ultra Clean (UCTT) PT Lowered to $135 at UBS, Buy Rating Maintained',
			analystName: 'Timothy Arcuri',
			priceTarget: 135.0,
			adjPriceTarget: 135.0,
			priceWhenPosted: 75.29,
			newsPublisher: 'StreetInsider',
			newsBaseURL: 'streetinsider.com',
			analystCompany: 'UBS',
		))->toArray(), $news[0]->toArray());
	}

	public function testPriceTargetLatestNewsWithFractionalTargetAndWithoutAnalystName(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/price-target-latest-news.json');

		$news = iterator_to_array($client->priceTargetLatestNews(limit: 100));

		$this->assertSame((new PriceTargetNews(
			symbol: 'ZGN',
			publishedDate: '2026-10-05T10:54:08.000Z',
			newsURL: 'https://thefly.com/ajax/news_get.php?id=4435319',
			newsTitle: 'Ermenegildo Zegna price target lowered to $15.40 from $15.50 at UBS',
			analystName: '',
			priceTarget: 15.4,
			adjPriceTarget: 15.4,
			priceWhenPosted: 12.52,
			newsPublisher: 'TheFly',
			newsBaseURL: 'thefly.com',
			analystCompany: 'UBS',
		))->toArray(), $news[79]->toArray());
	}

	public function testDefaultLimitIsLeftToTheApi(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/empty-response.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		iterator_to_array($client->priceTargetLatestNews());

		$this->assertSame(
			'https://financialmodelingprep.com/stable/price-target-latest-news?page=0&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

}
