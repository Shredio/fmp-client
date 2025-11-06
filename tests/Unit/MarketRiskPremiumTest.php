<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\MarketRiskPremium;
use Tests\TestCase;

final class MarketRiskPremiumTest extends TestCase
{

	public function testMarketRiskPremium(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/market-risk-premium.json');

		$premiums = iterator_to_array($client->marketRiskPremium());

		$this->assertGreaterThan(180, count($premiums));
		$this->assertSame((new MarketRiskPremium(
			country: 'United States',
			continent: 'North America',
			countryRiskPremium: 0.0,
			totalEquityRiskPremium: 4.33,
		))->toArray(), $premiums[7]->toArray());
	}

}
