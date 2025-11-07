# FMP Client API Endpoints Overview

This document provides a comprehensive overview of all available endpoints (methods) in the FMP Client library. Each endpoint is described with its purpose and return values.

## Table of Contents

- [Exchange and Market Information](#exchange-and-market-information)
- [Stock and Asset Lists](#stock-and-asset-lists)
- [Company Information](#company-information)
- [Financial Statements](#financial-statements)
- [Financial Statement Growth](#financial-statement-growth)
- [Event Calendars](#event-calendars)
- [Market Data and Quotes](#market-data-and-quotes)
- [Financial Metrics and Ratios](#financial-metrics-and-ratios)

---

## Exchange and Market Information

### `availableExchanges()`

**Purpose:** Retrieve a list of all available exchanges in the FMP system.

**Parameters:** None

**Return Values:** `iterable<AvailableExchange>`
- `name` - Exchange name
- `stockExchangeHours` - Exchange trading hours

**API endpoint:** `https://financialmodelingprep.com/stable/available-exchanges`

---

### `allExchangeMarketHours()`

**Purpose:** Retrieve trading hours for all exchanges.

**Parameters:** None

**Return Values:** `iterable<ExchangeMarketHours>`
- `stockExchange` - Exchange name
- `stockMarketHours` - Trading hours information
- `isTheStockMarketOpen` - Indicates if the exchange is open
- `isTheEuronextMarketOpen` - Euronext market status
- `isTheForexMarketOpen` - Forex market status
- `isTheCryptoMarketOpen` - Crypto market status

**API endpoint:** `https://financialmodelingprep.com/stable/all-exchange-market-hours`

---

### `marketRiskPremium()`

**Purpose:** Retrieve market risk premiums for various countries.

**Parameters:** None

**Return Values:** `iterable<MarketRiskPremium>`
- `country` - Country name
- `continent` - Continent
- `totalEquityRiskPremium` - Total equity risk premium
- `countryRiskPremium` - Country risk premium

**API endpoint:** `https://financialmodelingprep.com/stable/market-risk-premium`

---

### `treasuryRates()`

**Purpose:** Retrieve current US Treasury rates.

**Parameters:** None

**Return Values:** `iterable<TreasuryRate>`
- `date` - Date
- `month1` through `month120` - Interest rates for various maturities

**API endpoint:** `https://financialmodelingprep.com/stable/treasury-rates`

---

## Stock and Asset Lists

### `indexList()`

**Purpose:** Retrieve a list of all available indices (e.g., S&P 500, NASDAQ).

**Parameters:** None

**Return Values:** `iterable<Index>`
- `symbol` - Index symbol
- `name` - Index name
- `currency` - Currency
- `stockExchange` - Exchange
- `exchangeShortName` - Exchange short name

**API endpoint:** `https://financialmodelingprep.com/stable/index-list`

---

### `cryptocurrencyList()`

**Purpose:** Retrieve a list of all available cryptocurrencies.

**Parameters:** None

**Return Values:** `iterable<Cryptocurrency>`
- `symbol` - Cryptocurrency symbol
- `name` - Cryptocurrency name
- `currency` - Currency
- `stockExchange` - Exchange
- `exchangeShortName` - Exchange short name

**API endpoint:** `https://financialmodelingprep.com/stable/cryptocurrency-list`

---

### `stockList()`

**Purpose:** Retrieve a list of all available stocks.

**Parameters:** None

**Return Values:** `iterable<Stock>`
- `symbol` - Ticker symbol
- `name` - Company name
- `price` - Current price
- `exchange` - Exchange
- `exchangeShortName` - Exchange short name
- `type` - Instrument type

**API endpoint:** `https://financialmodelingprep.com/stable/stock-list`

---

### `activelyTradingList()`

**Purpose:** Retrieve a list of actively trading stocks.

**Parameters:** None

**Return Values:** `iterable<ActivelyTrading>`
- `symbol` - Ticker symbol
- `name` - Company name
- `price` - Current price
- `exchange` - Exchange
- `exchangeShortName` - Exchange short name
- `type` - Instrument type

**API endpoint:** `https://financialmodelingprep.com/stable/actively-trading-list`

---

### `symbolChangeList()`

**Purpose:** Retrieve a list of symbol changes (e.g., due to mergers or rebranding).

**Parameters:** None

**Return Values:** `iterable<SymbolChange>`
- `date` - Change date
- `name` - Company name
- `oldSymbol` - Old symbol
- `newSymbol` - New symbol

**API endpoint:** `https://financialmodelingprep.com/stable/symbol-change`

---

### `delistedCompanies()`

**Purpose:** Retrieve a list of delisted companies.

**Parameters:**
- `limit` (int, default: 100) - Maximum number of records
- `page` (int, default: 0) - Page number

**Return Values:** `iterable<DelistedCompany>`
- `symbol` - Company symbol
- `companyName` - Company name
- `exchange` - Exchange
- `ipoDate` - IPO date
- `delistedDate` - Delisting date

**API endpoint:** `https://financialmodelingprep.com/stable/delisted-companies`

---

### `financialStatementSymbolList()`

**Purpose:** Retrieve a list of symbols for which financial statements are available.

**Parameters:** None

**Return Values:** `iterable<FinancialStatementSymbol>`
- `symbol` - Ticker symbol

**API endpoint:** `https://financialmodelingprep.com/stable/financial-statement-symbol-list`

---

## Company Information

### `companyProfile()`

**Purpose:** Retrieve detailed company profile.

**Parameters:**
- `symbol` (string) - Ticker symbol

**Return Values:** `CompanyProfile|null`
- `symbol` - Ticker symbol
- `price` - Current stock price
- `beta` - Beta coefficient
- `volAvg` - Average trading volume
- `mktCap` - Market capitalization
- `lastDiv` - Last dividend
- `range` - 52-week price range
- `changes` - Price change
- `companyName` - Company name
- `currency` - Currency
- `cik` - CIK number (SEC)
- `isin` - ISIN code
- `cusip` - CUSIP code
- `exchange` - Exchange
- `exchangeShortName` - Exchange short name
- `industry` - Industry
- `website` - Website
- `description` - Company description
- `ceo` - CEO name
- `sector` - Sector
- `country` - Country
- `fullTimeEmployees` - Number of employees
- `phone` - Phone
- `address` - Address
- `city` - City
- `state` - State
- `zip` - ZIP code
- `dcfDiff` - DCF difference
- `dcf` - Discounted cash flow (DCF)
- `image` - Logo image URL
- `ipoDate` - IPO date
- `defaultImage` - Default image indicator
- `isEtf` - ETF indicator
- `isActivelyTrading` - Active trading indicator
- `isAdr` - ADR indicator
- `isFund` - Fund indicator

**API endpoint:** `https://financialmodelingprep.com/stable/profile`

---

### `companyProfileBulk()`

**Purpose:** Bulk retrieval of company profiles for all companies (streaming large datasets).

**Parameters:** None

**Return Values:** `iterable<CompanyProfile>` (same structure as `companyProfile()`)

**API endpoint:** `https://financialmodelingprep.com/stable/profile-bulk`

---

### `sharesFloat()`

**Purpose:** Retrieve shares float information for a specific symbol.

**Parameters:**
- `symbol` (string) - Ticker symbol

**Return Values:** `SharesFloat|null`
- `symbol` - Ticker symbol
- `date` - Date
- `freeFloat` - Free float percentage
- `floatShares` - Number of floating shares
- `outstandingShares` - Total outstanding shares
- `source` - Data source

**API endpoint:** `https://financialmodelingprep.com/stable/shares-float`

---

### `sharesFloatAll()`

**Purpose:** Retrieve shares float information for all companies (with pagination).

**Parameters:**
- `limit` (int, default: 1000) - Maximum number of records (1-5000)
- `page` (int, default: 0) - Page number

**Return Values:** `iterable<SharesFloat>` (same structure as `sharesFloat()`)

**API endpoint:** `https://financialmodelingprep.com/stable/shares-float-all`

---

### `pressReleasesLatest()`

**Purpose:** Retrieve latest press releases from companies.

**Parameters:**
- `limit` (int) - Maximum number of records
- `page` (int, default: 0) - Page number

**Return Values:** `iterable<PressRelease>`
- `symbol` - Ticker symbol
- `date` - Release date
- `title` - Press release title
- `text` - Press release text

**API endpoint:** `https://financialmodelingprep.com/stable/news/press-releases-latest`

---

### `stockNewsLatest()`

**Purpose:** Retrieve latest stock news.

**Parameters:**
- `limit` (int, 1-250) - Maximum number of records
- `page` (int, default: 0) - Page number

**Return Values:** `iterable<StockNews>`
- `symbol` - Ticker symbol
- `publishedDate` - Publication date
- `title` - News title
- `image` - Image URL
- `site` - News source
- `text` - News text
- `url` - News URL

**API endpoint:** `https://financialmodelingprep.com/stable/news/stock-latest`

---

### `analystEstimates()`

**Purpose:** Retrieve analyst estimates for a company's financial results.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `period` (string, default: 'annual') - Period (annual/quarter)
- `page` (int, default: 0) - Page number
- `limit` (int, default: 6) - Maximum number of records

**Return Values:** `iterable<AnalystEstimate>`
- `symbol` - Ticker symbol
- `date` - Date
- `estimatedRevenueLow` - Lowest revenue estimate
- `estimatedRevenueHigh` - Highest revenue estimate
- `estimatedRevenueAvg` - Average revenue estimate
- `estimatedEbitdaLow` - Lowest EBITDA estimate
- `estimatedEbitdaHigh` - Highest EBITDA estimate
- `estimatedEbitdaAvg` - Average EBITDA estimate
- `estimatedEbitLow` - Lowest EBIT estimate
- `estimatedEbitHigh` - Highest EBIT estimate
- `estimatedEbitAvg` - Average EBIT estimate
- `estimatedNetIncomeLow` - Lowest net income estimate
- `estimatedNetIncomeHigh` - Highest net income estimate
- `estimatedNetIncomeAvg` - Average net income estimate
- `estimatedSgaExpenseLow` - Lowest SGA expense estimate
- `estimatedSgaExpenseHigh` - Highest SGA expense estimate
- `estimatedSgaExpenseAvg` - Average SGA expense estimate
- `estimatedEpsAvg` - Average EPS estimate
- `estimatedEpsHigh` - Highest EPS estimate
- `estimatedEpsLow` - Lowest EPS estimate
- `numberAnalystEstimatedRevenue` - Number of analysts estimating revenue
- `numberAnalystsEstimatedEps` - Number of analysts estimating EPS

**API endpoint:** `https://financialmodelingprep.com/stable/analyst-estimates`

---

### `financialScores()`

**Purpose:** Retrieve financial scores (ratings) for a specific company.

**Parameters:**
- `symbol` (string) - Ticker symbol

**Return Values:** `iterable<Scores>`
- `symbol` - Ticker symbol
- `altmanZScore` - Altman Z-Score (bankruptcy risk)
- `piotroskiScore` - Piotroski F-Score (financial strength)
- `workingCapital` - Working capital
- `totalAssets` - Total assets
- `retainedEarnings` - Retained earnings
- `ebit` - EBIT
- `marketCap` - Market capitalization
- `totalLiabilities` - Total liabilities
- `revenue` - Revenue

**API endpoint:** `https://financialmodelingprep.com/stable/financial-scores`

---

### `scoresBulk()`

**Purpose:** Bulk retrieval of financial scores for all companies.

**Parameters:** None

**Return Values:** `iterable<Scores>` (same structure as `financialScores()`)

**API endpoint:** `https://financialmodelingprep.com/stable/scores-bulk`

---

## Financial Statements

### `balanceSheetStatement()`

**Purpose:** Retrieve balance sheet statement for a specific company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int, default: 4) - Maximum number of records (1-1000)
- `period` (PeriodQuery|Period, default: FY) - Period (FY=annual, Q1-Q4=quarterly)

**Return Values:** `iterable<BalanceSheetStatement>`
Contains complete balance sheet including:
- `date` - Statement date
- `symbol` - Ticker symbol
- `reportedCurrency` - Reporting currency
- `cik` - CIK number
- `fillingDate` - Filing date
- `acceptedDate` - Acceptance date
- `calendarYear` - Calendar year
- `period` - Period
- Assets (e.g., `cashAndCashEquivalents`, `inventory`, `totalAssets`)
- Liabilities (e.g., `totalLiabilities`, `totalStockholdersEquity`)
- And many other accounting items

**API endpoint:** `https://financialmodelingprep.com/stable/balance-sheet-statement`

---

### `balanceSheetStatementBulk()`

**Purpose:** Bulk retrieval of balance sheets for all companies for a given period.

**Parameters:**
- `year` (int) - Year
- `period` (Period, default: FY) - Period

**Return Values:** `iterable<BalanceSheetStatement>` (same structure as `balanceSheetStatement()`)

**API endpoint:** `https://financialmodelingprep.com/stable/balance-sheet-statement-bulk`

---

### `incomeStatement()`

**Purpose:** Retrieve income statement for a specific company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int, default: 4) - Maximum number of records (1-1000)
- `period` (PeriodQuery|Period, default: FY) - Period

**Return Values:** `iterable<IncomeStatement>`
Contains complete income statement including:
- `date` - Statement date
- `symbol` - Ticker symbol
- `reportedCurrency` - Currency
- `revenue` - Revenue
- `costOfRevenue` - Cost of revenue
- `grossProfit` - Gross profit
- `operatingIncome` - Operating income
- `netIncome` - Net income
- `eps` - Earnings per share
- `ebitda` - EBITDA
- And many other items

**API endpoint:** `https://financialmodelingprep.com/stable/income-statement`

---

### `incomeStatementBulk()`

**Purpose:** Bulk retrieval of income statements for all companies for a given period.

**Parameters:**
- `year` (int) - Year
- `period` (Period, default: FY) - Period

**Return Values:** `iterable<IncomeStatement>` (same structure as `incomeStatement()`)

**API endpoint:** `https://financialmodelingprep.com/stable/income-statement-bulk`

---

### `cashFlowStatement()`

**Purpose:** Retrieve cash flow statement for a specific company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int, default: 4) - Maximum number of records (1-1000)
- `period` (PeriodQuery|Period, default: FY) - Period

**Return Values:** `iterable<CashFlowStatement>`
Contains complete cash flow statement including:
- `date` - Statement date
- `symbol` - Ticker symbol
- `reportedCurrency` - Currency
- `operatingCashFlow` - Operating cash flow
- `capitalExpenditure` - Capital expenditure
- `freeCashFlow` - Free cash flow
- `netCashProvidedByOperatingActivities` - Cash flow from operating activities
- `netCashUsedForInvestingActivities` - Cash flow from investing activities
- `netCashUsedProvidedByFinancingActivities` - Cash flow from financing activities
- And many other items

**API endpoint:** `https://financialmodelingprep.com/stable/cash-flow-statement`

---

### `cashFlowStatementBulk()`

**Purpose:** Bulk retrieval of cash flow statements for all companies for a given period.

**Parameters:**
- `year` (int) - Year
- `period` (Period, default: FY) - Period

**Return Values:** `iterable<CashFlowStatement>` (same structure as `cashFlowStatement()`)

**API endpoint:** `https://financialmodelingprep.com/stable/cash-flow-statement-bulk`

---

### `latestFinancialStatements()`

**Purpose:** Retrieve latest financial statements across all companies.

**Parameters:**
- `page` (int, default: 0) - Page number (0-100)
- `limit` (int, default: 250) - Maximum number of records (1-250)

**Return Values:** `iterable<LatestFinancialStatement>`
- `symbol` - Ticker symbol
- `annualFillingDate` - Latest annual statement date
- `quarterFillingDate` - Latest quarterly statement date

**API endpoint:** `https://financialmodelingprep.com/stable/latest-financial-statements`

---

## Financial Statement Growth

### `incomeStatementGrowth()`

**Purpose:** Retrieve year-over-year growth of income statement items.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int|null, default: null) - Maximum number of records
- `period` (PeriodQuery|null, default: null) - Period

**Return Values:** `iterable<IncomeStatementGrowth>`
Contains percentage growth of all income statement items (e.g., revenue growth, net income growth, etc.)

**API endpoint:** `https://financialmodelingprep.com/api/v3/income-statement-growth/{symbol}`

---

### `incomeStatementGrowthBulk()`

**Purpose:** Bulk retrieval of income statement growth for all companies.

**Parameters:**
- `year` (int) - Year
- `period` (Period) - Period

**Return Values:** `iterable<IncomeStatementGrowthBulk>`

**API endpoint:** `https://financialmodelingprep.com/stable/income-statement-growth-bulk`

---

### `balanceSheetStatementGrowth()`

**Purpose:** Retrieve year-over-year growth of balance sheet items.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int|null, default: null) - Maximum number of records
- `period` (PeriodQuery|null, default: null) - Period

**Return Values:** `iterable<BalanceSheetStatementGrowth>`
Contains percentage growth of all balance sheet items

**API endpoint:** `https://financialmodelingprep.com/api/v3/balance-sheet-statement-growth/{symbol}`

---

### `balanceSheetStatementGrowthBulk()`

**Purpose:** Bulk retrieval of balance sheet growth for all companies.

**Parameters:**
- `year` (int) - Year
- `period` (Period) - Period

**Return Values:** `iterable<BalanceSheetStatementGrowthBulk>`

**API endpoint:** `https://financialmodelingprep.com/stable/balance-sheet-statement-growth-bulk`

---

### `cashFlowStatementGrowth()`

**Purpose:** Retrieve year-over-year growth of cash flow statement items.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int|null, default: null) - Maximum number of records
- `period` (PeriodQuery|null, default: null) - Period

**Return Values:** `iterable<CashFlowStatementGrowth>`
Contains percentage growth of all cash flow statement items

**API endpoint:** `https://financialmodelingprep.com/api/v3/cash-flow-statement-growth/{symbol}`

---

### `cashFlowStatementGrowthBulk()`

**Purpose:** Bulk retrieval of cash flow growth for all companies.

**Parameters:**
- `year` (int) - Year
- `period` (Period) - Period

**Return Values:** `iterable<CashFlowStatementGrowthBulk>`

**API endpoint:** `https://financialmodelingprep.com/stable/cash-flow-statement-growth-bulk`

---

## Event Calendars

### `dividendsCalendar()`

**Purpose:** Retrieve dividend calendar for a given time period.

**Parameters:**
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date
- `logger` (LoggerInterface|null, default: null) - Optional logger

**Return Values:** `iterable<Dividend>`
- `date` - Dividend date
- `label` - Description
- `adjDividend` - Adjusted dividend
- `symbol` - Ticker symbol
- `dividend` - Dividend amount
- `recordDate` - Record date
- `paymentDate` - Payment date
- `declarationDate` - Declaration date

**API endpoint:** `https://financialmodelingprep.com/stable/dividends-calendar`

---

### `dividends()`

**Purpose:** Retrieve historical dividends for a specific symbol.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int|null, default: null) - Maximum number of records (1-1000)

**Return Values:** `iterable<Dividend>` (same structure as `dividendsCalendar()`)

**API endpoint:** `https://financialmodelingprep.com/stable/dividends`

---

### `earningsCalendar()`

**Purpose:** Retrieve earnings announcement calendar for a given time period.

**Parameters:**
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date
- `logger` (LoggerInterface|null, default: null) - Optional logger

**Return Values:** `iterable<EarningsCalendarItem>`
- `date` - Announcement date
- `symbol` - Ticker symbol
- `eps` - Actual EPS
- `epsEstimated` - Estimated EPS
- `time` - Announcement time
- `revenue` - Actual revenue
- `revenueEstimated` - Estimated revenue
- `updatedFromDate` - Last update date
- `fiscalDateEnding` - End of fiscal period

**API endpoint:** `https://financialmodelingprep.com/stable/earnings-calendar`

---

### `splitsCalendar()`

**Purpose:** Retrieve stock splits calendar for a given time period.

**Parameters:**
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date
- `logger` (LoggerInterface|null, default: null) - Optional logger

**Return Values:** `iterable<SplitsCalendarItem>`
- `date` - Split date
- `label` - Description
- `symbol` - Ticker symbol
- `numerator` - Split ratio numerator
- `denominator` - Split ratio denominator

**API endpoint:** `https://financialmodelingprep.com/stable/splits-calendar`

---

## Market Data and Quotes

### `batchExchangeQuote()`

**Purpose:** Retrieve current quotes for all stocks on a specific exchange (simplified version).

**Parameters:**
- `exchange` (string) - Exchange name (e.g., "NYSE", "NASDAQ")

**Return Values:** `iterable<BatchExchangeQuote>`
- `symbol` - Ticker symbol
- `name` - Company name
- `price` - Current price
- `changesPercentage` - Percentage change
- `change` - Absolute change
- `dayLow` - Daily low
- `dayHigh` - Daily high
- `yearHigh` - 52-week high
- `yearLow` - 52-week low
- `marketCap` - Market capitalization
- `priceAvg50` - 50-day average price
- `priceAvg200` - 200-day average price
- `exchange` - Exchange
- `volume` - Trading volume
- `avgVolume` - Average volume
- `open` - Opening price
- `previousClose` - Previous close
- `eps` - Earnings per share
- `pe` - P/E ratio
- `sharesOutstanding` - Outstanding shares
- `timestamp` - Timestamp

**API endpoint:** `https://financialmodelingprep.com/stable/batch-exchange-quote`

---

### `batchExchangeQuoteDetailed()`

**Purpose:** Retrieve detailed quotes for all stocks on a specific exchange (extended version).

**Parameters:**
- `exchange` (string) - Exchange name

**Return Values:** `iterable<BatchExchangeDetailedQuote>`
Contains all data from `BatchExchangeQuote` plus additional information:
- `earningsAnnouncement` - Earnings announcement date
- `timestamp` - Timestamp

**API endpoint:** `https://financialmodelingprep.com/stable/batch-exchange-quote`

---

### `batchForexQuotes()`

**Purpose:** Retrieve current quotes for all forex currency pairs.

**Parameters:** None

**Return Values:** `iterable<BatchForexQuote>`
- `symbol` - Currency pair symbol (e.g., "EUR/USD")
- `name` - Pair name
- `price` - Current rate
- `changesPercentage` - Percentage change
- `change` - Absolute change
- `dayLow` - Daily low
- `dayHigh` - Daily high
- `yearHigh` - 52-week high
- `yearLow` - 52-week low
- `marketCap` - Market capitalization
- `priceAvg50` - 50-day average
- `priceAvg200` - 200-day average
- `exchange` - Exchange/platform
- `volume` - Volume
- `avgVolume` - Average volume
- `open` - Opening rate
- `previousClose` - Previous close
- `eps` - EPS (not relevant for forex)
- `pe` - P/E (not relevant for forex)
- `earningsAnnouncement` - Announcement date
- `sharesOutstanding` - Outstanding shares
- `timestamp` - Timestamp

**API endpoint:** `https://financialmodelingprep.com/stable/batch-forex-quotes`

---

### `eodBulkQuotes()`

**Purpose:** Retrieve end-of-day quotes for all stocks for a given date.

**Parameters:**
- `date` (DateTimeImmutable) - Date for which to retrieve EOD data

**Return Values:** `iterable<EodQuote>`
- `symbol` - Ticker symbol
- `open` - Opening price
- `high` - Highest price of the day
- `low` - Lowest price of the day
- `close` - Closing price
- `volume` - Trading volume

**API endpoint:** `https://financialmodelingprep.com/stable/eod-bulk`

---

### `historicalPriceEod()`

**Purpose:** Retrieve historical daily prices for a specific symbol in a given time period.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date

**Return Values:** `iterable<HistoricalPriceEod>`
- `date` - Date
- `open` - Opening price
- `high` - Highest price
- `low` - Lowest price
- `close` - Closing price
- `adjClose` - Adjusted closing price
- `volume` - Trading volume
- `unadjustedVolume` - Unadjusted volume
- `change` - Price change
- `changePercent` - Percentage change
- `vwap` - Volume-weighted average price
- `label` - Description
- `changeOverTime` - Change over time

**API endpoint:** `https://financialmodelingprep.com/stable/historical-price-eod/full`

---

### `historicalChart()`

**Purpose:** Retrieve historical intraday price data with various time intervals.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `interval` (TimeInterval) - Time interval (1min, 5min, 15min, 30min, 1hour, 4hour)
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date

**Return Values:** `iterable<HistoricalChart>`
- `date` - Date and time
- `open` - Opening price
- `low` - Lowest price
- `high` - Highest price
- `close` - Closing price
- `volume` - Trading volume

**API endpoint:** `https://financialmodelingprep.com/stable/historical-chart/{interval}`

---

## Financial Metrics and Ratios

### `keyMetrics()`

**Purpose:** Retrieve key financial metrics for a specific company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int, default: 80) - Maximum number of records
- `period` (PeriodQuery, default: Annual) - Period

**Return Values:** `iterable<KeyMetrics>`
Contains many key metrics such as:
- `revenuePerShare` - Revenue per share
- `netIncomePerShare` - Net income per share
- `operatingCashFlowPerShare` - Operating cash flow per share
- `freeCashFlowPerShare` - Free cash flow per share
- `cashPerShare` - Cash per share
- `bookValuePerShare` - Book value per share
- `tangibleBookValuePerShare` - Tangible book value per share
- `shareholdersEquityPerShare` - Shareholders equity per share
- `interestDebtPerShare` - Interest debt per share
- `marketCap` - Market capitalization
- `enterpriseValue` - Enterprise value
- `peRatio` - P/E ratio
- `priceToSalesRatio` - P/S ratio
- `pocfratio` - Price to operating cash flow ratio
- `pfcfRatio` - Price to free cash flow ratio
- `pbRatio` - P/B ratio
- `ptbRatio` - Price to tangible book value ratio
- `evToSales` - EV/Sales
- `evToOperatingCashFlow` - EV/Operating cash flow
- `evToFreeCashFlow` - EV/Free cash flow
- `earningsYield` - Earnings yield
- `freeCashFlowYield` - Free cash flow yield
- `debtToEquity` - Debt to equity ratio
- `debtToAssets` - Debt to assets ratio
- `netDebtToEBITDA` - Net debt to EBITDA
- And many other metrics

**API endpoint:** `https://financialmodelingprep.com/stable/key-metrics`

---

### `keyMetricsTtm()`

**Purpose:** Retrieve key metrics TTM (Trailing Twelve Months) for a specific symbol.

**Parameters:**
- `symbol` (string) - Ticker symbol

**Return Values:** `iterable<KeyMetricsTtm>` (same structure as `keyMetrics()`)

**API endpoint:** `https://financialmodelingprep.com/stable/key-metrics-ttm`

---

### `keyMetricsTtmBulk()`

**Purpose:** Bulk retrieval of key metrics TTM for all companies.

**Parameters:** None

**Return Values:** `iterable<KeyMetricsTtm>` (same structure as `keyMetrics()`)

**API endpoint:** `https://financialmodelingprep.com/stable/key-metrics-ttm-bulk`

---

### `ratios()`

**Purpose:** Retrieve financial ratios for a specific company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int, default: 80) - Maximum number of records
- `period` (PeriodQuery, default: Annual) - Period

**Return Values:** `iterable<Ratios>`
Contains a wide range of financial ratios including:
- Liquidity ratios (currentRatio, quickRatio, cashRatio)
- Leverage ratios (debtRatio, debtEquityRatio)
- Profitability ratios (grossProfitMargin, operatingProfitMargin, netProfitMargin, ROA, ROE)
- Efficiency ratios (assetTurnover, inventoryTurnover)
- Market value ratios (priceEarningsRatio, priceToBookRatio, dividendYield)
- And many others

**API endpoint:** `https://financialmodelingprep.com/stable/ratios`

---

### `ratiosTtm()`

**Purpose:** Retrieve financial ratios TTM (trailing twelve months) for a specific symbol.

**Parameters:**
- `symbol` (string) - Ticker symbol

**Return Values:** `iterable<RatiosTtm>` (same structure as `ratios()`)

**API endpoint:** `https://financialmodelingprep.com/stable/ratios-ttm`

---

### `ratiosTtmBulk()`

**Purpose:** Bulk retrieval of financial ratios TTM for all companies.

**Parameters:** None

**Return Values:** `iterable<RatiosTtm>` (same structure as `ratios()`)

**API endpoint:** `https://financialmodelingprep.com/stable/ratios-ttm-bulk`
