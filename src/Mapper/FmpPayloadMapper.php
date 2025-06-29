<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Mapper;

use Shredio\FmpClient\Enum\Period;
use Shredio\FmpClient\Payload\AnalystEstimate;
use Shredio\FmpClient\Payload\AvailableExchange;
use Shredio\FmpClient\Payload\BalanceSheetStatement;
use Shredio\FmpClient\Payload\BatchExchangeDetailedQuote;
use Shredio\FmpClient\Payload\BatchExchangeQuote;
use Shredio\FmpClient\Payload\BatchForexQuote;
use Shredio\FmpClient\Payload\CashFlowStatement;
use Shredio\FmpClient\Payload\CompanyProfile;
use Shredio\FmpClient\Payload\Cryptocurrency;
use Shredio\FmpClient\Payload\DividendsCalendarItem;
use Shredio\FmpClient\Payload\EarningsCalendarItem;
use Shredio\FmpClient\Payload\EodQuote;
use Shredio\FmpClient\Payload\ExchangeMarketHours;
use Shredio\FmpClient\Payload\FinancialStatementSymbol;
use Shredio\FmpClient\Payload\HistoricalChart;
use Shredio\FmpClient\Payload\HistoricalPriceEod;
use Shredio\FmpClient\Payload\IncomeStatement;
use Shredio\FmpClient\Payload\Index;
use Shredio\FmpClient\Payload\KeyMetrics;
use Shredio\FmpClient\Payload\KeyMetricsTtm;
use Shredio\FmpClient\Payload\LatestFinancialStatement;
use Shredio\FmpClient\Payload\Ratios;
use Shredio\FmpClient\Payload\RatiosTtm;
use Shredio\FmpClient\Payload\Scores;
use Shredio\FmpClient\Payload\SplitsCalendarItem;
use Shredio\FmpClient\Payload\Stock;
use Shredio\FmpClient\Validator\FmpValidator;
use Webmozart\Assert\InvalidArgumentException;

final readonly class FmpPayloadMapper
{

	/**
	 * @throws InvalidArgumentException
	 */
	public function availableExchange(mixed $data): AvailableExchange
	{
		$validator = new FmpValidator('available exchange');

		$data = $validator->getArray($data);
		$exchange = $validator->getNonEmptyStringInArray($data, 'exchange');

		$validator = $validator->withContext($exchange);

		$name = $validator->getNonEmptyStringInArray($data, 'name');
		$countryName = $validator->getStringOrNullInArray($data, 'countryName');
		$countryCode = $validator->getStringOrNullInArray($data, 'countryCode');
		$symbolSuffix = $validator->getStringOrNullInArray($data, 'symbolSuffix');
		$delay = $validator->getStringOrNullInArray($data, 'delay');

		if ($countryCode !== null) {
			$validator->assertLength($countryCode, 2, 'countryCode');
		}

		return new AvailableExchange(
			exchange: $exchange,
			name: $name,
			countryName: $countryName,
			countryCode: $countryCode,
			symbolSuffix: $symbolSuffix === 'N/A' ? null : $symbolSuffix,
			delay: $delay,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function exchangeMarketHours(mixed $data): ExchangeMarketHours
	{
		$validator = new FmpValidator('exchange market hours');

		$data = $validator->getArray($data);
		$exchange = $validator->getNonEmptyStringInArray($data, 'exchange');

		$validator = $validator->withContext($exchange);

		$name = $validator->getNonEmptyStringInArray($data, 'name');
		$openingHour = $validator->getNonEmptyStringInArray($data, 'openingHour');
		$closingHour = $validator->getNonEmptyStringInArray($data, 'closingHour');
		$timezone = $validator->getNonEmptyStringInArray($data, 'timezone');
		$isMarketOpen = $validator->getBoolInArray($data, 'isMarketOpen');
		$openingAdditional = $validator->getOptionalStringInArray($data, 'openingAdditional');
		$closingAdditional = $validator->getOptionalStringInArray($data, 'closingAdditional');

		return new ExchangeMarketHours(
			exchange: $exchange,
			name: $name,
			openingHour: $openingHour,
			closingHour: $closingHour,
			timezone: $timezone,
			isMarketOpen: $isMarketOpen,
			openingAdditional: $openingAdditional,
			closingAdditional: $closingAdditional,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function index(mixed $data): Index
	{
		$validator = new FmpValidator('index');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$name = $validator->getNonEmptyStringInArray($data, 'name');
		$exchange = $validator->getNonEmptyStringInArray($data, 'exchange');
		$currency = $validator->getNonEmptyStringInArray($data, 'currency');

		return new Index(
			symbol: $symbol,
			name: $name,
			exchange: $exchange,
			currency: $currency,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function cryptocurrency(mixed $data): Cryptocurrency
	{
		$validator = new FmpValidator('cryptocurrency');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$name = $validator->getNonEmptyStringInArray($data, 'name');
		$exchange = $validator->getNonEmptyStringInArray($data, 'exchange');
		$icoDate = $validator->getStringOrNullInArray($data, 'icoDate');
		$circulatingSupply = $validator->getNumericOrNullInArray($data, 'circulatingSupply');
		$totalSupply = $validator->getNumericOrNullInArray($data, 'totalSupply');

		return new Cryptocurrency(
			symbol: $symbol,
			name: $name,
			exchange: $exchange,
			icoDate: $icoDate,
			circulatingSupply: $circulatingSupply,
			totalSupply: $totalSupply,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function stock(mixed $data): Stock
	{
		$validator = new FmpValidator('stock');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$companyName = $validator->getStringOrNullInArray($data, 'companyName');

		return new Stock(
			symbol: $symbol,
			companyName: $companyName,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function companyProfile(mixed $data, bool $isCsv = false): CompanyProfile
	{
		$validator = new FmpValidator('company profile');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$price = $validator->getNumericOrNullInArray($data, 'price');
		$marketCap = $validator->getNumericOrNullInArray($data, 'marketCap');
		$beta = $validator->getNumericOrNullInArray($data, 'beta');
		$lastDividend = $validator->getNumericOrNullInArray($data, 'lastDividend');
		$range = $validator->getStringOrNullInArray($data, 'range');
		$change = $validator->getNumericOrNullInArray($data, 'change');
		$changePercentage = $validator->getNumericOrNullInArray($data, 'changePercentage');
		$volume = $validator->getNumericOrNullInArray($data, 'volume');
		$averageVolume = $validator->getNumericOrNullInArray($data, 'averageVolume');
		$companyName = $validator->getStringOrNullInArray($data, 'companyName');
		$currency = $validator->getStringOrNullInArray($data, 'currency');
		$cik = $validator->getStringOrNullInArray($data, 'cik');
		$isin = $validator->getStringOrNullInArray($data, 'isin');
		$cusip = $validator->getStringOrNullInArray($data, 'cusip');
		$exchangeFullName = $validator->getStringOrNullInArray($data, 'exchangeFullName');
		$exchange = $validator->getStringOrNullInArray($data, 'exchange');
		$industry = $validator->getStringOrNullInArray($data, 'industry');
		$website = $validator->getStringOrNullInArray($data, 'website');
		$description = $validator->getStringOrNullInArray($data, 'description');
		$ceo = $validator->getStringOrNullInArray($data, 'ceo');
		$sector = $validator->getStringOrNullInArray($data, 'sector');
		$country = $validator->getStringOrNullInArray($data, 'country');
		$fullTimeEmployees = $validator->getStringOrNullInArray($data, 'fullTimeEmployees');
		$phone = $validator->getStringOrNullInArray($data, 'phone');
		$address = $validator->getStringOrNullInArray($data, 'address');
		$city = $validator->getStringOrNullInArray($data, 'city');
		$state = $validator->getStringOrNullInArray($data, 'state');
		$zip = $validator->getStringOrNullInArray($data, 'zip');
		$image = $validator->getStringOrNullInArray($data, 'image');
		$ipoDate = $validator->getStringOrNullInArray($data, 'ipoDate');
		$defaultImage = $validator->getBoolOrNullInArray($data, 'defaultImage');
		$isEtf = $validator->getBoolOrNullInArray($data, 'isEtf');
		$isActivelyTrading = $validator->getBoolOrNullInArray($data, 'isActivelyTrading');
		$isAdr = $validator->getBoolOrNullInArray($data, 'isAdr');
		$isFund = $validator->getBoolOrNullInArray($data, 'isFund');

		return new CompanyProfile(
			symbol: $symbol,
			price: $price,
			marketCap: $this->toIntegerOrNull($marketCap),
			beta: $beta,
			lastDividend: $lastDividend,
			range: $range,
			change: $change,
			changePercentage: $changePercentage,
			volume: $volume,
			averageVolume: $this->toIntegerOrNull($averageVolume),
			companyName: $companyName,
			currency: $currency,
			cik: $cik,
			isin: $isin,
			cusip: $cusip,
			exchangeFullName: $exchangeFullName,
			exchange: $exchange,
			industry: $industry,
			website: $website,
			description: $description,
			ceo: $ceo,
			sector: $sector,
			country: $country,
			fullTimeEmployees: $fullTimeEmployees,
			phone: $phone,
			address: $address,
			city: $city,
			state: $state,
			zip: $zip,
			image: $image,
			ipoDate: $ipoDate,
			defaultImage: $defaultImage,
			isEtf: $isEtf,
			isActivelyTrading: $isActivelyTrading,
			isAdr: $isAdr,
			isFund: $isFund,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function analystEstimate(mixed $data): AnalystEstimate
	{
		$validator = new FmpValidator('analyst estimate');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$revenueLow = $validator->getIntInArray($data, 'revenueLow');
		$revenueHigh = $validator->getIntInArray($data, 'revenueHigh');
		$revenueAvg = $validator->getIntInArray($data, 'revenueAvg');
		$ebitdaLow = $validator->getIntInArray($data, 'ebitdaLow');
		$ebitdaHigh = $validator->getIntInArray($data, 'ebitdaHigh');
		$ebitdaAvg = $validator->getIntInArray($data, 'ebitdaAvg');
		$ebitLow = $validator->getIntInArray($data, 'ebitLow');
		$ebitHigh = $validator->getIntInArray($data, 'ebitHigh');
		$ebitAvg = $validator->getIntInArray($data, 'ebitAvg');
		$netIncomeLow = $validator->getIntInArray($data, 'netIncomeLow');
		$netIncomeHigh = $validator->getIntInArray($data, 'netIncomeHigh');
		$netIncomeAvg = $validator->getIntInArray($data, 'netIncomeAvg');
		$sgaExpenseLow = $validator->getIntInArray($data, 'sgaExpenseLow');
		$sgaExpenseHigh = $validator->getIntInArray($data, 'sgaExpenseHigh');
		$sgaExpenseAvg = $validator->getIntInArray($data, 'sgaExpenseAvg');
		$epsAvg = $validator->getNumericInArray($data, 'epsAvg');
		$epsHigh = $validator->getNumericInArray($data, 'epsHigh');
		$epsLow = $validator->getNumericInArray($data, 'epsLow');
		$numAnalystsRevenue = $validator->getIntInArray($data, 'numAnalystsRevenue');
		$numAnalystsEps = $validator->getIntInArray($data, 'numAnalystsEps');

		return new AnalystEstimate(
			symbol: $symbol,
			date: $date,
			revenueLow: $revenueLow,
			revenueHigh: $revenueHigh,
			revenueAvg: $revenueAvg,
			ebitdaLow: $ebitdaLow,
			ebitdaHigh: $ebitdaHigh,
			ebitdaAvg: $ebitdaAvg,
			ebitLow: $ebitLow,
			ebitHigh: $ebitHigh,
			ebitAvg: $ebitAvg,
			netIncomeLow: $netIncomeLow,
			netIncomeHigh: $netIncomeHigh,
			netIncomeAvg: $netIncomeAvg,
			sgaExpenseLow: $sgaExpenseLow,
			sgaExpenseHigh: $sgaExpenseHigh,
			sgaExpenseAvg: $sgaExpenseAvg,
			epsAvg: $epsAvg,
			epsHigh: $epsHigh,
			epsLow: $epsLow,
			numAnalystsRevenue: $numAnalystsRevenue,
			numAnalystsEps: $numAnalystsEps,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function balanceSheetStatement(mixed $data, bool $isCsv = false): BalanceSheetStatement
	{
		$validator = new FmpValidator('balance sheet statement');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$reportedCurrency = $validator->getNonEmptyStringInArray($data, 'reportedCurrency');
		$cik = $validator->getNonEmptyStringInArray($data, 'cik');
		$filingDate = $validator->getNonEmptyStringInArray($data, 'filingDate');
		$acceptedDate = $validator->getNonEmptyStringInArray($data, 'acceptedDate');
		$fiscalYear = $validator->getNonEmptyStringInArray($data, 'fiscalYear');
		$period = $validator->getNonEmptyStringInArray($data, 'period');

		return new BalanceSheetStatement(
			symbol: $symbol,
			date: $date,
			reportedCurrency: $reportedCurrency,
			cik: $cik,
			filingDate: $filingDate,
			acceptedDate: $acceptedDate,
			fiscalYear: $fiscalYear,
			period: Period::from($period),
			cashAndCashEquivalents: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'cashAndCashEquivalents')),
			shortTermInvestments: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'shortTermInvestments')),
			cashAndShortTermInvestments: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'cashAndShortTermInvestments')),
			netReceivables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netReceivables')),
			accountsReceivables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'accountsReceivables')),
			otherReceivables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherReceivables')),
			inventory: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'inventory')),
			prepaids: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'prepaids')),
			otherCurrentAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherCurrentAssets')),
			totalCurrentAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalCurrentAssets')),
			propertyPlantEquipmentNet: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'propertyPlantEquipmentNet')),
			goodwill: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'goodwill')),
			intangibleAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'intangibleAssets')),
			goodwillAndIntangibleAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'goodwillAndIntangibleAssets')),
			longTermInvestments: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'longTermInvestments')),
			taxAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'taxAssets')),
			otherNonCurrentAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherNonCurrentAssets')),
			totalNonCurrentAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalNonCurrentAssets')),
			otherAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherAssets')),
			totalAssets: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalAssets')),
			totalPayables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalPayables')),
			accountPayables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'accountPayables')),
			otherPayables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherPayables')),
			accruedExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'accruedExpenses')),
			shortTermDebt: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'shortTermDebt')),
			capitalLeaseObligationsCurrent: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'capitalLeaseObligationsCurrent')),
			taxPayables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'taxPayables')),
			deferredRevenue: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'deferredRevenue')),
			otherCurrentLiabilities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherCurrentLiabilities')),
			totalCurrentLiabilities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalCurrentLiabilities')),
			longTermDebt: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'longTermDebt')),
			capitalLeaseObligationsNonCurrent: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'capitalLeaseObligationsNonCurrent')),
			deferredRevenueNonCurrent: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'deferredRevenueNonCurrent')),
			deferredTaxLiabilitiesNonCurrent: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'deferredTaxLiabilitiesNonCurrent')),
			otherNonCurrentLiabilities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherNonCurrentLiabilities')),
			totalNonCurrentLiabilities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalNonCurrentLiabilities')),
			otherLiabilities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherLiabilities')),
			capitalLeaseObligations: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'capitalLeaseObligations')),
			totalLiabilities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalLiabilities')),
			treasuryStock: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'treasuryStock')),
			preferredStock: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'preferredStock')),
			commonStock: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'commonStock')),
			retainedEarnings: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'retainedEarnings')),
			additionalPaidInCapital: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'additionalPaidInCapital')),
			accumulatedOtherComprehensiveIncomeLoss: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'accumulatedOtherComprehensiveIncomeLoss')),
			otherTotalStockholdersEquity: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherTotalStockholdersEquity')),
			totalStockholdersEquity: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalStockholdersEquity')),
			totalEquity: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalEquity')),
			minorityInterest: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'minorityInterest')),
			totalLiabilitiesAndTotalEquity: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalLiabilitiesAndTotalEquity')),
			totalInvestments: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalInvestments')),
			totalDebt: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalDebt')),
			netDebt: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netDebt')),
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function dividendsCalendar(mixed $data): DividendsCalendarItem
	{
		$validator = new FmpValidator('dividends calendar');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$recordDate = $validator->getStringOrNullInArray($data, 'recordDate');
		$paymentDate = $validator->getStringOrNullInArray($data, 'paymentDate');
		$declarationDate = $validator->getStringOrNullInArray($data, 'declarationDate');
		$adjDividend = $validator->getFloatInArray($data, 'adjDividend');
		$dividend = $validator->getFloatInArray($data, 'dividend');
		$yield = $validator->getFloatInArray($data, 'yield');
		$frequency = $validator->getNonEmptyStringInArray($data, 'frequency');

		return new DividendsCalendarItem(
			symbol: $symbol,
			date: $date,
			recordDate: $recordDate,
			paymentDate: $paymentDate,
			declarationDate: $declarationDate,
			adjDividend: $adjDividend,
			dividend: $dividend,
			yield: $yield,
			frequency: $frequency,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function earningsCalendar(mixed $data): EarningsCalendarItem
	{
		$validator = new FmpValidator('earnings calendar');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$epsActual = $validator->getFloatInArray($data, 'epsActual');
		$epsEstimated = $validator->getNumericOrNullInArray($data, 'epsEstimated');
		$revenueActual = $validator->getIntInArray($data, 'revenueActual');
		$revenueEstimated = $validator->getIntOrNullInArray($data, 'revenueEstimated');
		$lastUpdated = $validator->getNonEmptyStringInArray($data, 'lastUpdated');

		return new EarningsCalendarItem(
			symbol: $symbol,
			date: $date,
			epsActual: $epsActual,
			epsEstimated: $epsEstimated,
			revenueActual: $revenueActual,
			revenueEstimated: $revenueEstimated,
			lastUpdated: $lastUpdated,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function splitsCalendar(mixed $data): SplitsCalendarItem
	{
		$validator = new FmpValidator('splits calendar');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$numerator = $validator->getIntInArray($data, 'numerator');
		$denominator = $validator->getIntInArray($data, 'denominator');

		return new SplitsCalendarItem(
			symbol: $symbol,
			date: $date,
			numerator: $numerator,
			denominator: $denominator,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function incomeStatement(mixed $data, bool $isCsv = false): IncomeStatement
	{
		$validator = new FmpValidator('income statement');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$reportedCurrency = $validator->getNonEmptyStringInArray($data, 'reportedCurrency');
		$cik = $validator->getNonEmptyStringInArray($data, 'cik');
		$filingDate = $validator->getNonEmptyStringInArray($data, 'filingDate');
		$acceptedDate = $validator->getNonEmptyStringInArray($data, 'acceptedDate');
		$fiscalYear = $validator->getNonEmptyStringInArray($data, 'fiscalYear');
		$period = $validator->getNonEmptyStringInArray($data, 'period');

		return new IncomeStatement(
			symbol: $symbol,
			date: $date,
			reportedCurrency: $reportedCurrency,
			cik: $cik,
			filingDate: $filingDate,
			acceptedDate: $acceptedDate,
			fiscalYear: $fiscalYear,
			period: Period::from($period),
			revenue: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'revenue')),
			costOfRevenue: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'costOfRevenue')),
			grossProfit: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'grossProfit')),
			researchAndDevelopmentExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'researchAndDevelopmentExpenses')),
			generalAndAdministrativeExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'generalAndAdministrativeExpenses')),
			sellingAndMarketingExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'sellingAndMarketingExpenses')),
			sellingGeneralAndAdministrativeExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'sellingGeneralAndAdministrativeExpenses')),
			otherExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherExpenses')),
			operatingExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'operatingExpenses')),
			costAndExpenses: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'costAndExpenses')),
			netInterestIncome: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netInterestIncome')),
			interestIncome: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'interestIncome')),
			interestExpense: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'interestExpense')),
			depreciationAndAmortization: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'depreciationAndAmortization')),
			ebitda: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'ebitda')),
			ebit: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'ebit')),
			nonOperatingIncomeExcludingInterest: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'nonOperatingIncomeExcludingInterest')),
			operatingIncome: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'operatingIncome')),
			totalOtherIncomeExpensesNet: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'totalOtherIncomeExpensesNet')),
			incomeBeforeTax: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'incomeBeforeTax')),
			incomeTaxExpense: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'incomeTaxExpense')),
			netIncomeFromContinuingOperations: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netIncomeFromContinuingOperations')),
			netIncomeFromDiscontinuedOperations: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netIncomeFromDiscontinuedOperations')),
			otherAdjustmentsToNetIncome: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherAdjustmentsToNetIncome')),
			netIncome: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netIncome')),
			netIncomeDeductions: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netIncomeDeductions')),
			bottomLineNetIncome: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'bottomLineNetIncome')),
			eps: (float) ($validator->getNumericOrNullInArray($data, 'eps') ?? 0.0),
			epsDiluted: (float) ($validator->getNumericOrNullInArray($data, 'epsDiluted') ?? 0.0),
			weightedAverageShsOut: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'weightedAverageShsOut')),
			weightedAverageShsOutDil: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'weightedAverageShsOutDil')),
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function cashFlowStatement(mixed $data, bool $isCsv = false): CashFlowStatement
	{
		$validator = new FmpValidator('cash flow statement');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$reportedCurrency = $validator->getNonEmptyStringInArray($data, 'reportedCurrency');
		$cik = $validator->getNonEmptyStringInArray($data, 'cik');
		$filingDate = $validator->getNonEmptyStringInArray($data, 'filingDate');
		$acceptedDate = $validator->getNonEmptyStringInArray($data, 'acceptedDate');
		$fiscalYear = $validator->getNonEmptyStringInArray($data, 'fiscalYear');
		$period = $validator->getNonEmptyStringInArray($data, 'period');

		return new CashFlowStatement(
			symbol: $symbol,
			date: $date,
			reportedCurrency: $reportedCurrency,
			cik: $cik,
			filingDate: $filingDate,
			acceptedDate: $acceptedDate,
			fiscalYear: $fiscalYear,
			period: Period::from($period),
			netIncome: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netIncome')),
			depreciationAndAmortization: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'depreciationAndAmortization')),
			deferredIncomeTax: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'deferredIncomeTax')),
			stockBasedCompensation: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'stockBasedCompensation')),
			changeInWorkingCapital: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'changeInWorkingCapital')),
			accountsReceivables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'accountsReceivables')),
			inventory: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'inventory')),
			accountsPayables: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'accountsPayables')),
			otherWorkingCapital: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherWorkingCapital')),
			otherNonCashItems: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherNonCashItems')),
			netCashProvidedByOperatingActivities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netCashProvidedByOperatingActivities')),
			investmentsInPropertyPlantAndEquipment: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'investmentsInPropertyPlantAndEquipment')),
			acquisitionsNet: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'acquisitionsNet')),
			purchasesOfInvestments: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'purchasesOfInvestments')),
			salesMaturitiesOfInvestments: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'salesMaturitiesOfInvestments')),
			otherInvestingActivities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherInvestingActivities')),
			netCashProvidedByInvestingActivities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netCashProvidedByInvestingActivities')),
			netDebtIssuance: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netDebtIssuance')),
			longTermNetDebtIssuance: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'longTermNetDebtIssuance')),
			shortTermNetDebtIssuance: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'shortTermNetDebtIssuance')),
			netStockIssuance: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netStockIssuance')),
			netCommonStockIssuance: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netStockIssuance')),
			commonStockIssuance: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'commonStockIssuance')),
			commonStockRepurchased: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'commonStockRepurchased')),
			netPreferredStockIssuance: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netPreferredStockIssuance')),
			netDividendsPaid: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netDividendsPaid')),
			commonDividendsPaid: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'commonDividendsPaid')),
			preferredDividendsPaid: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'preferredDividendsPaid')),
			otherFinancingActivities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'otherFinancingActivities')),
			netCashProvidedByFinancingActivities: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netCashProvidedByFinancingActivities')),
			effectOfForexChangesOnCash: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'effectOfForexChangesOnCash')),
			netChangeInCash: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'netChangeInCash')),
			cashAtEndOfPeriod: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'cashAtEndOfPeriod')),
			cashAtBeginningOfPeriod: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'cashAtBeginningOfPeriod')),
			operatingCashFlow: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'operatingCashFlow')),
			capitalExpenditure: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'capitalExpenditure')),
			freeCashFlow: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'freeCashFlow')),
			incomeTaxesPaid: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'incomeTaxesPaid')),
			interestPaid: $this->toIntegerOrZero($validator->getNumericOrNullInArray($data, 'interestPaid')),
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function latestFinancialStatement(mixed $data): LatestFinancialStatement
	{
		$validator = new FmpValidator('latest financial statement');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$calendarYear = $validator->getIntInArray($data, 'calendarYear');
		$period = $validator->getNonEmptyStringInArray($data, 'period');
		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$dateAdded = $validator->getNonEmptyStringInArray($data, 'dateAdded');

		return new LatestFinancialStatement(
			symbol: $symbol,
			calendarYear: $calendarYear,
			period: $period,
			date: $date,
			dateAdded: $dateAdded,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function financialStatementSymbol(mixed $data): FinancialStatementSymbol
	{
		$validator = new FmpValidator('financial statement symbol');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$companyName = $validator->getNonEmptyStringInArray($data, 'companyName');
		$tradingCurrency = $validator->getNonEmptyStringInArray($data, 'tradingCurrency');
		$reportingCurrency = $validator->getStringOrNullInArray($data, 'reportingCurrency');

		return new FinancialStatementSymbol(
			symbol: $symbol,
			companyName: $companyName,
			tradingCurrency: $tradingCurrency,
			reportingCurrency: $reportingCurrency,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function eodBulkQuote(mixed $data, bool $isCsv = false): EodQuote
	{
		$validator = new FmpValidator('eod bulk quote');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$open = $validator->getNumericInArray($data, 'open');
		$low = $validator->getNumericInArray($data, 'low');
		$high = $validator->getNumericInArray($data, 'high');
		$close = $validator->getNumericInArray($data, 'close');
		$adjClose = $validator->getNumericInArray($data, 'adjClose');
		$volume = $validator->getNumericInArray($data, 'volume');

		return new EodQuote(
			symbol: $symbol,
			date: $date,
			open: (float) $open,
			low: (float) $low,
			high: (float) $high,
			close: (float) $close,
			adjClose: (float) $adjClose,
			volume: (float) $volume,
		);
	}

	private function toIntegerOrNull(int|float|null $value, bool $zeroAsNull = false): ?int
	{
		$value = $value === null ? null : (int) $value;

		if ($zeroAsNull && $value === 0) {
			return null;
		}

		return $value;
	}

	private function toIntegerOrZero(int|float|null $value): int
	{
		return $value === null ? 0 : (int) $value;
	}

	private function toFloatOrNull(int|float|null $value): ?float
	{
		return $value === null ? null : (float) $value;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function batchExchangeQuote(mixed $data): BatchExchangeQuote
	{
		$validator = new FmpValidator('batch exchange quote');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$price = $validator->getNumericOrNullInArray($data, 'price');
		$change = $validator->getNumericOrNullInArray($data, 'change');
		$volume = $validator->getNumericOrNullInArray($data, 'volume');

		return new BatchExchangeQuote(
			symbol: $symbol,
			price: $price === null ? null : (float) $price,
			change: $change === null ? null : (float) $change,
			volume: $volume,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function batchForexQuote(mixed $data): BatchForexQuote
	{
		$validator = new FmpValidator('batch forex quote');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$price = $validator->getNumericInArray($data, 'price');
		$change = $validator->getNumericInArray($data, 'change');
		$volume = $validator->getNumericOrNullInArray($data, 'volume');

		return new BatchForexQuote(
			symbol: $symbol,
			price: (float) $price,
			change: (float) $change,
			volume: $volume,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function batchExchangeDetailedQuote(mixed $data): BatchExchangeDetailedQuote
	{
		$validator = new FmpValidator('batch exchange detailed quote');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$name = $validator->getNonEmptyStringInArray($data, 'name');
		$price = $validator->getNumericOrNullInArray($data, 'price');
		$changePercentage = $validator->getNumericOrNullInArray($data, 'changePercentage');
		$change = $validator->getNumericOrNullInArray($data, 'change');
		$volume = $validator->getNumericOrNullInArray($data, 'volume');
		$dayLow = $validator->getNumericOrNullInArray($data, 'dayLow');
		$dayHigh = $validator->getNumericOrNullInArray($data, 'dayHigh');
		$yearHigh = $validator->getNumericOrNullInArray($data, 'yearHigh');
		$yearLow = $validator->getNumericOrNullInArray($data, 'yearLow');
		$marketCap = $validator->getIntOrNullInArray($data, 'marketCap');
		$priceAvg50 = $validator->getNumericOrNullInArray($data, 'priceAvg50');
		$priceAvg200 = $validator->getNumericOrNullInArray($data, 'priceAvg200');
		$exchange = $validator->getNonEmptyStringInArray($data, 'exchange');
		$open = $validator->getNumericOrNullInArray($data, 'open');
		$previousClose = $validator->getNumericOrNullInArray($data, 'previousClose');
		$timestamp = $validator->getIntOrNullInArray($data, 'timestamp');

		return new BatchExchangeDetailedQuote(
			symbol: $symbol,
			name: $name,
			exchange: $exchange,
			price: $price === null ? null : (float) $price,
			changePercentage: $changePercentage === null ? null : (float) $changePercentage,
			change: $change === null ? null : (float) $change,
			volume: $volume,
			dayLow: $dayLow === null ? null : (float) $dayLow,
			dayHigh: $dayHigh === null ? null : (float) $dayHigh,
			yearHigh: $yearHigh === null ? null : (float) $yearHigh,
			yearLow: $yearLow === null ? null : (float) $yearLow,
			marketCap: $marketCap,
			priceAvg50: $priceAvg50 === null ? null : (float) $priceAvg50,
			priceAvg200: $priceAvg200 === null ? null : (float) $priceAvg200,
			open: $open === null ? null : (float) $open,
			previousClose: $previousClose === null ? null : (float) $previousClose,
			timestamp: $timestamp,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function historicalPriceEod(mixed $data): HistoricalPriceEod
	{
		$validator = new FmpValidator('historical price eod');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$open = $validator->getNumericInArray($data, 'open');
		$high = $validator->getNumericInArray($data, 'high');
		$low = $validator->getNumericInArray($data, 'low');
		$close = $validator->getNumericInArray($data, 'close');
		$volume = $validator->getIntInArray($data, 'volume');
		$change = $validator->getNumericInArray($data, 'change');
		$changePercent = $validator->getNumericInArray($data, 'changePercent');
		$vwap = $validator->getNumericInArray($data, 'vwap');

		return new HistoricalPriceEod(
			symbol: $symbol,
			date: $date,
			open: (float) $open,
			high: (float) $high,
			low: (float) $low,
			close: (float) $close,
			volume: $volume,
			change: (float) $change,
			changePercent: (float) $changePercent,
			vwap: (float) $vwap,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function historicalChart(mixed $data): HistoricalChart
	{
		$validator = new FmpValidator('historical chart');

		$data = $validator->getArray($data);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$open = $validator->getNumericInArray($data, 'open');
		$high = $validator->getNumericInArray($data, 'high');
		$low = $validator->getNumericInArray($data, 'low');
		$close = $validator->getNumericInArray($data, 'close');
		$volume = $validator->getIntInArray($data, 'volume');

		return new HistoricalChart(
			date: $date,
			open: (float) $open,
			high: (float) $high,
			low: (float) $low,
			close: (float) $close,
			volume: $volume,
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function keyMetrics(mixed $data): KeyMetrics
	{
		$validator = new FmpValidator('key metrics');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$fiscalYear = $validator->getNonEmptyStringInArray($data, 'fiscalYear');
		$period = Period::from($validator->getNonEmptyStringInArray($data, 'period'));
		$reportedCurrency = $validator->getNonEmptyStringInArray($data, 'reportedCurrency');

		return new KeyMetrics(
			symbol: $symbol,
			date: $date,
			fiscalYear: $fiscalYear,
			period: $period,
			reportedCurrency: $reportedCurrency,
			marketCap: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'marketCap')),
			enterpriseValue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'enterpriseValue')),
			evToSales: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToSales')),
			evToOperatingCashFlow: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToOperatingCashFlow')),
			evToFreeCashFlow: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToFreeCashFlow')),
			evToEBITDA: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToEBITDA')),
			netDebtToEBITDA: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netDebtToEBITDA')),
			currentRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'currentRatio')),
			incomeQuality: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'incomeQuality')),
			grahamNumber: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'grahamNumber')),
			grahamNetNet: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'grahamNetNet')),
			taxBurden: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'taxBurden')),
			interestBurden: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'interestBurden')),
			workingCapital: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'workingCapital')),
			investedCapital: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'investedCapital')),
			returnOnAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnAssets')),
			operatingReturnOnAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingReturnOnAssets')),
			returnOnTangibleAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnTangibleAssets')),
			returnOnEquity: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnEquity')),
			returnOnInvestedCapital: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnInvestedCapital')),
			returnOnCapitalEmployed: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnCapitalEmployed')),
			earningsYield: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'earningsYield')),
			freeCashFlowYield: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowYield')),
			capexToOperatingCashFlow: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexToOperatingCashFlow')),
			capexToDepreciation: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexToDepreciation')),
			capexToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexToRevenue')),
			salesGeneralAndAdministrativeToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'salesGeneralAndAdministrativeToRevenue')),
			researchAndDevelopementToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'researchAndDevelopementToRevenue')),
			stockBasedCompensationToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'stockBasedCompensationToRevenue')),
			intangiblesToTotalAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'intangiblesToTotalAssets')),
			averageReceivables: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'averageReceivables')),
			averagePayables: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'averagePayables')),
			averageInventory: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'averageInventory')),
			daysOfSalesOutstanding: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'daysOfSalesOutstanding')),
			daysOfPayablesOutstanding: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'daysOfPayablesOutstanding')),
			daysOfInventoryOutstanding: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'daysOfInventoryOutstanding')),
			operatingCycle: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCycle')),
			cashConversionCycle: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'cashConversionCycle')),
			freeCashFlowToEquity: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowToEquity')),
			freeCashFlowToFirm: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowToFirm')),
			tangibleAssetValue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'tangibleAssetValue')),
			netCurrentAssetValue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'netCurrentAssetValue')),
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function keyMetricsTtm(mixed $data, bool $isCsv = false): KeyMetricsTtm
	{
		$validator = new FmpValidator('key metrics ttm');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		return new KeyMetricsTtm(
			symbol: $symbol,
			marketCap: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'marketCap')),
			enterpriseValue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'enterpriseValueTTM')),
			evToSales: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToSalesTTM')),
			evToOperatingCashFlow: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToOperatingCashFlowTTM')),
			evToFreeCashFlow: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToFreeCashFlowTTM')),
			evToEBITDA: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'evToEBITDATTM')),
			netDebtToEBITDA: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netDebtToEBITDATTM')),
			currentRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'currentRatioTTM')),
			incomeQuality: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'incomeQualityTTM')),
			grahamNumber: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'grahamNumberTTM')),
			grahamNetNet: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'grahamNetNetTTM')),
			taxBurden: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'taxBurdenTTM')),
			interestBurden: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'interestBurdenTTM')),
			workingCapital: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'workingCapitalTTM')),
			investedCapital: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'investedCapitalTTM')),
			returnOnAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnAssetsTTM')),
			operatingReturnOnAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingReturnOnAssetsTTM')),
			returnOnTangibleAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnTangibleAssetsTTM')),
			returnOnEquity: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnEquityTTM')),
			returnOnInvestedCapital: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnInvestedCapitalTTM')),
			returnOnCapitalEmployed: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'returnOnCapitalEmployedTTM')),
			earningsYield: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'earningsYieldTTM')),
			freeCashFlowYield: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowYieldTTM')),
			capexToOperatingCashFlow: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexToOperatingCashFlowTTM')),
			capexToDepreciation: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexToDepreciationTTM')),
			capexToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexToRevenueTTM')),
			salesGeneralAndAdministrativeToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'salesGeneralAndAdministrativeToRevenueTTM')),
			researchAndDevelopementToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'researchAndDevelopementToRevenueTTM')),
			stockBasedCompensationToRevenue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'stockBasedCompensationToRevenueTTM')),
			intangiblesToTotalAssets: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'intangiblesToTotalAssetsTTM')),
			averageReceivables: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'averageReceivablesTTM')),
			averagePayables: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'averagePayablesTTM')),
			averageInventory: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'averageInventoryTTM')),
			daysOfSalesOutstanding: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'daysOfSalesOutstandingTTM')),
			daysOfPayablesOutstanding: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'daysOfPayablesOutstandingTTM')),
			daysOfInventoryOutstanding: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'daysOfInventoryOutstandingTTM')),
			operatingCycle: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCycleTTM')),
			cashConversionCycle: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'cashConversionCycleTTM')),
			freeCashFlowToEquity: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowToEquityTTM')),
			freeCashFlowToFirm: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowToFirmTTM')),
			tangibleAssetValue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'tangibleAssetValueTTM')),
			netCurrentAssetValue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'netCurrentAssetValueTTM')),
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function ratios(mixed $data): Ratios
	{
		$validator = new FmpValidator('ratios');

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$date = $validator->getNonEmptyStringInArray($data, 'date');
		$fiscalYear = $validator->getNonEmptyStringInArray($data, 'fiscalYear');
		$period = Period::from($validator->getNonEmptyStringInArray($data, 'period'));
		$reportedCurrency = $validator->getNonEmptyStringInArray($data, 'reportedCurrency');

		return new Ratios(
			symbol: $symbol,
			date: $date,
			fiscalYear: $fiscalYear,
			period: $period,
			reportedCurrency: $reportedCurrency,
			grossProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'grossProfitMargin')),
			ebitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'ebitMargin')),
			ebitdaMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'ebitdaMargin')),
			operatingProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingProfitMargin')),
			pretaxProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'pretaxProfitMargin')),
			continuousOperationsProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'continuousOperationsProfitMargin')),
			netProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netProfitMargin')),
			bottomLineProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'bottomLineProfitMargin')),
			receivablesTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'receivablesTurnover')),
			payablesTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'payablesTurnover')),
			inventoryTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'inventoryTurnover')),
			fixedAssetTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'fixedAssetTurnover')),
			assetTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'assetTurnover')),
			currentRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'currentRatio')),
			quickRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'quickRatio')),
			solvencyRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'solvencyRatio')),
			cashRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'cashRatio')),
			priceToEarningsRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToEarningsRatio')),
			priceToEarningsGrowthRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToEarningsGrowthRatio')),
			forwardPriceToEarningsGrowthRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'forwardPriceToEarningsGrowthRatio')),
			priceToBookRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToBookRatio')),
			priceToSalesRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToSalesRatio')),
			priceToFreeCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToFreeCashFlowRatio')),
			priceToOperatingCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToOperatingCashFlowRatio')),
			debtToAssetsRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToAssetsRatio')),
			debtToEquityRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToEquityRatio')),
			debtToCapitalRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToCapitalRatio')),
			longTermDebtToCapitalRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'longTermDebtToCapitalRatio')),
			financialLeverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'financialLeverageRatio')),
			workingCapitalTurnoverRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'workingCapitalTurnoverRatio')),
			operatingCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowRatio')),
			operatingCashFlowSalesRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowSalesRatio')),
			freeCashFlowOperatingCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowOperatingCashFlowRatio')),
			debtServiceCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtServiceCoverageRatio')),
			interestCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'interestCoverageRatio')),
			shortTermOperatingCashFlowCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'shortTermOperatingCashFlowCoverageRatio')),
			operatingCashFlowCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowCoverageRatio')),
			capitalExpenditureCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capitalExpenditureCoverageRatio')),
			dividendPaidAndCapexCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendPaidAndCapexCoverageRatio')),
			dividendPayoutRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendPayoutRatio')),
			dividendYield: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendYield')),
			dividendYieldPercentage: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendYieldPercentage')),
			revenuePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'revenuePerShare')),
			netIncomePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netIncomePerShare')),
			interestDebtPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'interestDebtPerShare')),
			cashPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'cashPerShare')),
			bookValuePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'bookValuePerShare')),
			tangibleBookValuePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'tangibleBookValuePerShare')),
			shareholdersEquityPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'shareholdersEquityPerShare')),
			operatingCashFlowPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowPerShare')),
			capexPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexPerShare')),
			freeCashFlowPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowPerShare')),
			netIncomePerEBT: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netIncomePerEBT')),
			ebtPerEbit: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'ebtPerEbit')),
			priceToFairValue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToFairValue')),
			debtToMarketCap: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToMarketCap')),
			effectiveTaxRate: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'effectiveTaxRate')),
			enterpriseValueMultiple: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'enterpriseValueMultiple')),
			dividendPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendPerShare')),
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function ratiosTtm(mixed $data, bool $isCsv = false): RatiosTtm
	{
		$validator = new FmpValidator('ratios ttm');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		return new RatiosTtm(
			symbol: $symbol,
			grossProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'grossProfitMarginTTM')),
			ebitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'ebitMarginTTM')),
			ebitdaMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'ebitdaMarginTTM')),
			operatingProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingProfitMarginTTM')),
			pretaxProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'pretaxProfitMarginTTM')),
			continuousOperationsProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'continuousOperationsProfitMarginTTM')),
			netProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netProfitMarginTTM')),
			bottomLineProfitMargin: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'bottomLineProfitMarginTTM')),
			receivablesTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'receivablesTurnoverTTM')),
			payablesTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'payablesTurnoverTTM')),
			inventoryTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'inventoryTurnoverTTM')),
			fixedAssetTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'fixedAssetTurnoverTTM')),
			assetTurnover: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'assetTurnoverTTM')),
			currentRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'currentRatioTTM')),
			quickRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'quickRatioTTM')),
			solvencyRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'solvencyRatioTTM')),
			cashRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'cashRatioTTM')),
			priceToEarningsRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToEarningsRatioTTM')),
			priceToEarningsGrowthRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToEarningsGrowthRatioTTM')),
			forwardPriceToEarningsGrowthRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'forwardPriceToEarningsGrowthRatioTTM')),
			priceToBookRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToBookRatioTTM')),
			priceToSalesRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToSalesRatioTTM')),
			priceToFreeCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToFreeCashFlowRatioTTM')),
			priceToOperatingCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToOperatingCashFlowRatioTTM')),
			debtToAssetsRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToAssetsRatioTTM')),
			debtToEquityRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToEquityRatioTTM')),
			debtToCapitalRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToCapitalRatioTTM')),
			longTermDebtToCapitalRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'longTermDebtToCapitalRatioTTM')),
			financialLeverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'financialLeverageRatioTTM')),
			workingCapitalTurnoverRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'workingCapitalTurnoverRatioTTM')),
			operatingCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowRatioTTM')),
			operatingCashFlowSalesRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowSalesRatioTTM')),
			freeCashFlowOperatingCashFlowRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowOperatingCashFlowRatioTTM')),
			debtServiceCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtServiceCoverageRatioTTM')),
			interestCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'interestCoverageRatioTTM')),
			shortTermOperatingCashFlowCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'shortTermOperatingCashFlowCoverageRatioTTM')),
			operatingCashFlowCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowCoverageRatioTTM')),
			capitalExpenditureCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capitalExpenditureCoverageRatioTTM')),
			dividendPaidAndCapexCoverageRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendPaidAndCapexCoverageRatioTTM')),
			dividendPayoutRatio: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendPayoutRatioTTM')),
			dividendYield: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'dividendYieldTTM')),
			enterpriseValue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'enterpriseValueTTM')),
			revenuePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'revenuePerShareTTM')),
			netIncomePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netIncomePerShareTTM')),
			interestDebtPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'interestDebtPerShareTTM')),
			cashPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'cashPerShareTTM')),
			bookValuePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'bookValuePerShareTTM')),
			tangibleBookValuePerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'tangibleBookValuePerShareTTM')),
			shareholdersEquityPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'shareholdersEquityPerShareTTM')),
			operatingCashFlowPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'operatingCashFlowPerShareTTM')),
			capexPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'capexPerShareTTM')),
			freeCashFlowPerShare: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'freeCashFlowPerShareTTM')),
			netIncomePerEBT: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'netIncomePerEBTTTM')),
			ebtPerEbit: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'ebtPerEbitTTM')),
			priceToFairValue: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'priceToFairValueTTM')),
			debtToMarketCap: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'debtToMarketCapTTM')),
			effectiveTaxRate: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'effectiveTaxRateTTM')),
			enterpriseValueMultiple: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'enterpriseValueMultipleTTM')),
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function scores(mixed $data, bool $isCsv = false): Scores
	{
		$validator = new FmpValidator('scores');
		
		if ($isCsv) {
			$validator = $validator->withCsvFormat();
		}

		$data = $validator->getArray($data);
		$symbol = $validator->getNonEmptyStringInArray($data, 'symbol');

		$validator = $validator->withContext($symbol);

		$reportedCurrency = $validator->getNonEmptyStringInArray($data, 'reportedCurrency');

		return new Scores(
			symbol: $symbol,
			reportedCurrency: $reportedCurrency,
			altmanZScore: $this->toFloatOrNull($validator->getNumericOrNullInArray($data, 'altmanZScore')),
			piotroskiScore: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'piotroskiScore')),
			workingCapital: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'workingCapital')),
			totalAssets: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'totalAssets')),
			retainedEarnings: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'retainedEarnings')),
			ebit: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'ebit')),
			marketCap: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'marketCap')),
			totalLiabilities: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'totalLiabilities')),
			revenue: $this->toIntegerOrNull($validator->getNumericOrNullInArray($data, 'revenue')),
		);
	}

}
