<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\InstitutionalHolder;
use Shredio\FmpClient\Payload\InstitutionalOwnershipFiling;
use Shredio\FmpClient\Payload\InstitutionalPositionsSummary;
use Tests\TestCase;

final class InstitutionalOwnershipTest extends TestCase
{

	public function testInstitutionalPositionsSummary(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/institutional-ownership-symbol-positions-summary-aapl-2026-q2.json');

		$summary = $client->institutionalPositionsSummary('AAPL', 2026, 2);

		$this->assertNotNull($summary);
		$this->assertSame((new InstitutionalPositionsSummary(
			symbol: 'AAPL',
			cik: '0000320193',
			date: '2026-06-30',
			investorsHolding: 6473,
			lastInvestorsHolding: 6418,
			investorsHoldingChange: 55,
			numberOf13Fshares: 9780581934,
			lastNumberOf13Fshares: 9405821074,
			numberOf13FsharesChange: 374760860,
			totalInvested: 2825766069697,
			lastTotalInvested: 2377594990986,
			totalInvestedChange: 448171078711,
			ownershipPercent: 66.4861,
			lastOwnershipPercent: 63.7762,
			ownershipPercentChange: 2.7099,
			newPositions: 201,
			lastNewPositions: 206,
			newPositionsChange: -5,
			increasedPositions: 2787,
			lastIncreasedPositions: 2625,
			increasedPositionsChange: 162,
			closedPositions: 183,
			lastClosedPositions: 211,
			closedPositionsChange: -28,
			reducedPositions: 2974,
			lastReducedPositions: 3113,
			reducedPositionsChange: -139,
			totalCalls: 188086543,
			lastTotalCalls: 165833284,
			totalCallsChange: 22253259,
			totalPuts: 157767438,
			lastTotalPuts: 134025688,
			totalPutsChange: 23741750,
			putCallRatio: 0.8388,
			lastPutCallRatio: 0.8082,
			putCallRatioChange: 3.0607,
		))->toArray(), $summary->toArray());
	}

	public function testInstitutionalPositionsSummaryForQuarterWithoutFilings(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertNull($client->institutionalPositionsSummary('AAPL', 2026, 4));
	}

	public function testInstitutionalHolders(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/institutional-ownership-extract-analytics-holder-aapl-2026-q2.json');

		$holders = iterator_to_array($client->institutionalHolders('AAPL', 2026, 2, limit: 100));

		$this->assertCount(100, $holders);
		$this->assertSame((new InstitutionalHolder(
			date: '2026-06-30',
			cik: '0002012383',
			filingDate: '2026-08-07',
			investorName: 'BLACKROCK, INC.',
			symbol: 'AAPL',
			securityName: 'APPLE INC',
			typeOfSecurity: 'COM',
			securityCusip: '037833100',
			sharesType: 'SH',
			putCallShare: 'Share',
			investmentDiscretion: 'SOLE',
			industryTitle: 'ELECTRONIC COMPUTERS',
			weight: 5.0007,
			lastWeight: 5.0758,
			changeInWeight: -0.075,
			changeInWeightPercentage: -1.4784,
			marketValue: 336524794350,
			lastMarketValue: 290512251859,
			changeInMarketValue: 46012542491,
			changeInMarketValuePercentage: 15.8384,
			sharesNumber: 1162996939,
			lastSharesNumber: 1144695425,
			changeInSharesNumber: 18301514,
			changeInSharesNumberPercentage: 1.5988,
			quarterEndPrice: 289.36,
			avgPricePaid: 234.24,
			isNew: false,
			isSoldOut: false,
			ownership: 7.9156,
			lastOwnership: 7.7814,
			changeInOwnership: 0.1342,
			changeInOwnershipPercentage: 1.7247,
			holdingPeriod: 8,
			firstAdded: '2024-09-30',
			performance: 40716816267,
			performancePercentage: 14.0155,
			lastPerformance: -20864809759,
			changeInPerformance: 61581626026,
			isCountedForPerformance: true,
		))->toArray(), $holders[0]->toArray());
	}

	public function testNewPositionHasZeroedPreviousQuarter(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/institutional-ownership-extract-analytics-holder-aapl-2026-q2.json');

		$holders = iterator_to_array($client->institutionalHolders('AAPL', 2026, 2, limit: 100));

		$this->assertSame((new InstitutionalHolder(
			date: '2026-06-30',
			cik: '0001374170',
			filingDate: '2026-08-12',
			investorName: 'NORGES BANK',
			symbol: 'AAPL',
			securityName: 'APPLE INC',
			typeOfSecurity: 'COM',
			securityCusip: '037833100',
			sharesType: 'SH',
			putCallShare: 'Share',
			investmentDiscretion: 'SOLE',
			industryTitle: 'ELECTRONIC COMPUTERS',
			weight: 5.5011,
			lastWeight: 0.0,
			changeInWeight: 5.5011,
			changeInWeightPercentage: 100.0,
			marketValue: 55188725946,
			lastMarketValue: 0,
			changeInMarketValue: 55188725946,
			changeInMarketValuePercentage: 100.0,
			sharesNumber: 190726866,
			lastSharesNumber: 0,
			changeInSharesNumber: 190726866,
			changeInSharesNumberPercentage: 100.0,
			quarterEndPrice: 289.36,
			avgPricePaid: 289.36,
			isNew: true,
			isSoldOut: false,
			ownership: 1.2981,
			lastOwnership: 0.0,
			changeInOwnership: 1.2981,
			changeInOwnershipPercentage: 100.0,
			holdingPeriod: 1,
			firstAdded: '2026-06-30',
			performance: 0,
			performancePercentage: 0.0,
			lastPerformance: 0,
			changeInPerformance: 0,
			isCountedForPerformance: true,
		))->toArray(), $holders[9]->toArray());
	}

	public function testOptionPosition(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/institutional-ownership-extract-analytics-holder-aapl-2026-q2.json');

		$holders = iterator_to_array($client->institutionalHolders('AAPL', 2026, 2, limit: 100));

		$this->assertSame('SUSQUEHANNA INTERNATIONAL GROUP, LLP', $holders[33]->investorName);
		$this->assertSame('CALL', $holders[33]->putCallShare);
		$this->assertSame('OTR', $holders[33]->investmentDiscretion);
		$this->assertSame(43119100, $holders[33]->sharesNumber);
	}

	public function testInstitutionalHoldersForQuarterWithoutFilings(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/empty-response.json');

		$this->assertSame([], iterator_to_array($client->institutionalHolders('AAPL', 2026, 4)));
	}

	public function testInstitutionalOwnershipLatest(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/institutional-ownership-latest.json');

		$filings = iterator_to_array($client->institutionalOwnershipLatest(limit: 100));

		$this->assertCount(100, $filings);
		$this->assertSame((new InstitutionalOwnershipFiling(
			cik: '0001531809',
			name: 'CAPWEALTH ADVISORS, LLC',
			date: '2026-09-30',
			filingDate: '2026-10-05 00:00:00',
			acceptedDate: '2026-10-05 10:59:23',
			formType: '13F-HR',
			link: 'https://www.sec.gov/Archives/edgar/data/1531809/000153180926000007/0001531809-26-000007-index.htm',
			finalLink: 'https://www.sec.gov/Archives/edgar/data/1531809/000153180926000007/xslForm13F_X02/primary_doc.xml',
		))->toArray(), $filings[0]->toArray());
	}

}
