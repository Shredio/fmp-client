<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\GradeNews;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class GradeNewsTest extends TestCase
{

	public function testGradesNews(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/grades-news-aapl.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$news = iterator_to_array($client->gradesNews('AAPL', limit: 100));

		$this->assertCount(100, $news);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/grades-news?symbol=AAPL&page=0&limit=100&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new GradeNews(
			symbol: 'AAPL',
			publishedDate: '2026-10-01T10:43:00.000Z',
			newsURL: 'https://www.streetinsider.com/Analyst+Comments/Needham+Reiterates+Hold+Rating+on+Apple+%28AAPL%29%2C+%27META+Wants+a+Big+Bite+of+AAPL%27/27131155.html',
			newsTitle: 'Needham Reiterates Hold Rating on Apple (AAPL), \'META Wants a Big Bite of AAPL\'',
			newsBaseURL: 'streetinsider.com',
			newsPublisher: 'StreetInsider',
			newGrade: 'Hold',
			previousGrade: 'Hold',
			gradingCompany: 'Needham',
			action: 'hold',
			priceWhenPosted: 333.02,
		))->toArray(), $news[0]->toArray());
	}

	public function testGradesNewsWithoutCoverage(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->gradesNews('SAP.DE')));
	}

	public function testGradesLatestNews(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/grades-latest-news.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$news = iterator_to_array($client->gradesLatestNews(limit: 100));

		$this->assertCount(100, $news);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/grades-latest-news?page=0&limit=100&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new GradeNews(
			symbol: 'PTC',
			publishedDate: '2026-10-05T14:19:56.000Z',
			newsURL: 'https://thefly.com/ajax/news_get.php?id=4435682',
			newsTitle: 'PTC downgraded to Sector Perform from Outperform at RBC Capital',
			newsBaseURL: 'thefly.com',
			newsPublisher: 'TheFly',
			newGrade: 'Sector Perform',
			previousGrade: 'Buy',
			gradingCompany: 'RBC Capital',
			action: 'downgrade',
			priceWhenPosted: 193.5181,
		))->toArray(), $news[0]->toArray());
	}

	public function testGradesLatestNewsWithoutPreviousGrade(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/grades-latest-news.json');

		$news = iterator_to_array($client->gradesLatestNews(limit: 100));

		$this->assertSame((new GradeNews(
			symbol: 'MSFT',
			publishedDate: '2026-10-05T13:44:12.000Z',
			newsURL: 'https://thefly.com/ajax/news_get.php?id=4435657',
			newsTitle: 'Microsoft upgraded, HubSpot downgraded: Wall Street\'s top analyst calls',
			newsBaseURL: 'thefly.com',
			newsPublisher: 'TheFly',
			newGrade: 'Neutral',
			previousGrade: null,
			gradingCompany: 'BNP Paribas',
			action: 'downgrade',
			priceWhenPosted: 526.44,
		))->toArray(), $news[5]->toArray());
	}

}
