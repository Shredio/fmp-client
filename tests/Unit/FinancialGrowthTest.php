<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Enum\PeriodQuery;
use Shredio\FmpClient\Payload\FinancialGrowth;
use Shredio\FmpClient\SymfonyFmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class FinancialGrowthTest extends TestCase
{

	public function testFinancialGrowth(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/financial-growth-aapl.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$growth = iterator_to_array($client->financialGrowth('AAPL', 1000));

		$this->assertCount(41, $growth);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/financial-growth?symbol=AAPL&limit=1000&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame((new FinancialGrowth(
			symbol: 'AAPL',
			date: '2025-09-27',
			fiscalYear: '2025',
			period: Period::FY,
			reportedCurrency: 'USD',
			revenueGrowth: 0.0642551178283274,
			grossProfitGrowth: 0.08035066940442653,
			ebitgrowth: 0.0748592946511722,
			operatingIncomeGrowth: 0.07981106349824699,
			netIncomeGrowth: 0.19495177946573355,
			epsgrowth: 0.22585924713584285,
			epsdilutedGrowth: 0.2269736842105263,
			weightedAverageSharesGrowth: -0.025761769441082424,
			weightedAverageSharesDilutedGrowth: -0.02618091334457634,
			dividendsPerShareGrowth: 0.03904275381624116,
			operatingCashFlowGrowth: -0.05726656180763441,
			receivablesGrowth: 0.1013541053394321,
			inventoryGrowth: -0.2152072467746363,
			assetGrowth: -0.015724149268453065,
			bookValueperShareGrowth: 0.3289327621427069,
			debtGrowth: -0.05612343459965227,
			rdexpenseGrowth: 0.10137073637233025,
			sgaexpensesGrowth: 0.05763114534237652,
			freeCashFlowGrowth: -0.09227347505215656,
			tenYRevenueGrowthPerShare: 1.7413426413189617,
			fiveYRevenueGrowthPerShare: 0.759746864116655,
			threeYRevenueGrowthPerShare: 0.1448507983899727,
			tenYOperatingCFGrowthPerShare: 1.1119537209402253,
			fiveYOperatingCFGrowthPerShare: 0.6040801412812578,
			threeYOperatingCFGrowthPerShare: -0.00995974428332331,
			tenYNetIncomeGrowthPerShare: 2.2296307380063842,
			fiveYNetIncomeGrowthPerShare: 1.264730804073461,
			threeYNetIncomeGrowthPerShare: 0.21747017302582256,
			tenYShareholdersEquityGrowthPerShare: -0.04893583739091923,
			fiveYShareholdersEquityGrowthPerShare: 0.3099186418475882,
			threeYShareholdersEquityGrowthPerShare: 0.5784796508310256,
			tenYDividendperShareGrowthPerShare: 1.0535518165116085,
			fiveYDividendperShareGrowthPerShare: 0.2712587064462357,
			threeYDividendperShareGrowthPerShare: 0.12718318394130193,
			ebitdaGrowth: 0.07038464388942414,
			growthCapitalExpenditure: -0.34592992484386575,
			tenYBottomLineNetIncomeGrowthPerShare: 2.2296307380063842,
			fiveYBottomLineNetIncomeGrowthPerShare: 1.264730804073461,
			threeYBottomLineNetIncomeGrowthPerShare: 0.21747017302582256,
		))->toArray(), $growth[0]->toArray());
		$this->assertSame('1985-09-30', $growth[40]->date);
	}

	public function testQuarterlyFinancialGrowthOfForeignListing(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/financial-growth-sap-de-quarter.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$growth = iterator_to_array($client->financialGrowth('SAP.DE', 4, PeriodQuery::Quarter));

		$this->assertCount(4, $growth);
		$this->assertSame(
			'https://financialmodelingprep.com/stable/financial-growth?symbol=SAP.DE&limit=4&period=quarter&apikey=SECRET',
			$response->getRequestUrl(),
		);
		$this->assertSame('2026-06-30', $growth[0]->date);
		$this->assertSame(Period::Q2, $growth[0]->period);
		$this->assertSame('EUR', $growth[0]->reportedCurrency);
		$this->assertSame(0.03380429094714809, $growth[0]->revenueGrowth);
		$this->assertSame(Period::Q1, $growth[1]->period);
	}

	public function testDefaultLimitAndPeriodAreLeftToTheApi(): void
	{
		$response = MockResponse::fromFile(__DIR__ . '/fixtures/empty-response.json');
		$client = new SymfonyFmpClient(new MockHttpClient([$response]), 'SECRET', null, strictMode: true);

		$this->assertSame([], iterator_to_array($client->financialGrowth('SPY')));
		$this->assertSame(
			'https://financialmodelingprep.com/stable/financial-growth?symbol=SPY&apikey=SECRET',
			$response->getRequestUrl(),
		);
	}

}
