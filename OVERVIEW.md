# FMP Client API Endpoints Overview

This document provides a comprehensive overview of all available endpoints (methods) in the FMP Client library. Each endpoint is described with its purpose and return values.

## Table of Contents

- [Exchange and Market Information](#exchange-and-market-information)
- [Stock and Asset Lists](#stock-and-asset-lists)
- [Company Information](#company-information)
- [Financial Statements](#financial-statements)
- [Financial Statement Growth](#financial-statement-growth)
- [Revenue Segmentation](#revenue-segmentation)
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

### `holidaysByExchange(string $exchange)`

**Purpose:** Retrieve market holidays and early-close days for a specific exchange.

**Parameters:**
- `$exchange` - Exchange code (e.g. `NASDAQ`)

**Return Values:** `iterable<HolidayByExchange>`
- `exchange` - Exchange code
- `date` - Holiday date (Y-m-d)
- `name` - Holiday name
- `isClosed` - Whether the market is closed for the whole day (`null` for early-close days)
- `adjOpenTime` - Adjusted opening time, or `null`
- `adjCloseTime` - Adjusted closing time for early-close days, or `null`
- `isFullyClosed` - Whether the market is fully closed (present only for early-close days, otherwise `null`)

**API endpoint:** `https://financialmodelingprep.com/stable/holidays-by-exchange`

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

### `searchIsin()`

**Purpose:** Search for stocks by ISIN (International Securities Identification Number) code.

**Parameters:**
- `isin` (string) - ISIN code (e.g., "US0378331005")

**Return Values:** `iterable<IsinSearchResult>`
- `symbol` - Ticker symbol
- `name` - Company name
- `isin` - ISIN code
- `marketCap` - Market capitalization

**API endpoint:** `https://financialmodelingprep.com/stable/search-isin`

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

### `peersBulk()`

**Purpose:** Bulk retrieval of stock peers (companies considered similar by industry, sector and market cap) for all symbols.

**Parameters:** None

**Return Values:** `iterable<PeersBulk>`
- `symbol` - Ticker symbol
- `peers` - List of peer ticker symbols (`list<non-empty-string>`)

**API endpoint:** `https://financialmodelingprep.com/stable/peers-bulk`

---

## Financial Statements

### `balanceSheetStatement()`

**Purpose:** Retrieve balance sheet statement for a specific company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `limit` (int, default: 4) - Maximum number of records (1-1000)
- `period` (PeriodQuery|Period, default: FY) - Period (FY=annual, Q1-Q4=quarterly)

**Return Values:** `iterable<BalanceSheetStatement>`
- `symbol` - Ticker symbol
- `date` - Statement date
- `reportedCurrency` - Reporting currency
- `cik` - CIK number
- `filingDate` - Filing date (null when the API has not published it yet)
- `acceptedDate` - Acceptance date
- `fiscalYear` - Fiscal year
- `period` - Period
- `cashAndCashEquivalents` - Cash and cash equivalents
- `shortTermInvestments` - Short-term investments
- `cashAndShortTermInvestments` - Cash and short-term investments
- `netReceivables` - Net receivables
- `accountsReceivables` - Accounts receivables
- `otherReceivables` - Other receivables
- `inventory` - Inventory
- `prepaids` - Prepaid expenses
- `otherCurrentAssets` - Other current assets
- `totalCurrentAssets` - Total current assets
- `propertyPlantEquipmentNet` - Property, plant and equipment (net)
- `goodwill` - Goodwill
- `intangibleAssets` - Intangible assets
- `goodwillAndIntangibleAssets` - Goodwill and intangible assets
- `longTermInvestments` - Long-term investments
- `taxAssets` - Tax assets
- `otherNonCurrentAssets` - Other non-current assets
- `totalNonCurrentAssets` - Total non-current assets
- `otherAssets` - Other assets
- `totalAssets` - Total assets
- `totalPayables` - Total payables
- `accountPayables` - Account payables
- `otherPayables` - Other payables
- `accruedExpenses` - Accrued expenses
- `shortTermDebt` - Short-term debt
- `capitalLeaseObligationsCurrent` - Current capital lease obligations
- `taxPayables` - Tax payables
- `deferredRevenue` - Deferred revenue
- `otherCurrentLiabilities` - Other current liabilities
- `totalCurrentLiabilities` - Total current liabilities
- `longTermDebt` - Long-term debt
- `capitalLeaseObligationsNonCurrent` - Non-current capital lease obligations
- `deferredRevenueNonCurrent` - Non-current deferred revenue
- `deferredTaxLiabilitiesNonCurrent` - Non-current deferred tax liabilities
- `otherNonCurrentLiabilities` - Other non-current liabilities
- `totalNonCurrentLiabilities` - Total non-current liabilities
- `otherLiabilities` - Other liabilities
- `capitalLeaseObligations` - Capital lease obligations
- `totalLiabilities` - Total liabilities
- `treasuryStock` - Treasury stock
- `preferredStock` - Preferred stock
- `commonStock` - Common stock
- `retainedEarnings` - Retained earnings
- `additionalPaidInCapital` - Additional paid-in capital
- `accumulatedOtherComprehensiveIncomeLoss` - Accumulated other comprehensive income/loss
- `otherTotalStockholdersEquity` - Other total stockholders equity
- `totalStockholdersEquity` - Total stockholders equity
- `totalEquity` - Total equity
- `minorityInterest` - Minority interest
- `totalLiabilitiesAndTotalEquity` - Total liabilities and total equity
- `totalInvestments` - Total investments
- `totalDebt` - Total debt
- `netDebt` - Net debt

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
- `symbol` - Ticker symbol
- `date` - Statement date
- `reportedCurrency` - Reporting currency
- `cik` - CIK number
- `filingDate` - Filing date (null when the API has not published it yet)
- `acceptedDate` - Acceptance date
- `fiscalYear` - Fiscal year
- `period` - Period
- `revenue` - Revenue
- `costOfRevenue` - Cost of revenue
- `grossProfit` - Gross profit
- `researchAndDevelopmentExpenses` - Research and development expenses
- `generalAndAdministrativeExpenses` - General and administrative expenses
- `sellingAndMarketingExpenses` - Selling and marketing expenses
- `sellingGeneralAndAdministrativeExpenses` - Selling, general and administrative expenses
- `otherExpenses` - Other expenses
- `operatingExpenses` - Operating expenses
- `costAndExpenses` - Cost and expenses
- `netInterestIncome` - Net interest income
- `interestIncome` - Interest income
- `interestExpense` - Interest expense
- `depreciationAndAmortization` - Depreciation and amortization
- `ebitda` - EBITDA
- `ebit` - EBIT
- `nonOperatingIncomeExcludingInterest` - Non-operating income excluding interest
- `operatingIncome` - Operating income
- `totalOtherIncomeExpensesNet` - Total other income/expenses (net)
- `incomeBeforeTax` - Income before tax
- `incomeTaxExpense` - Income tax expense
- `netIncomeFromContinuingOperations` - Net income from continuing operations
- `netIncomeFromDiscontinuedOperations` - Net income from discontinued operations
- `otherAdjustmentsToNetIncome` - Other adjustments to net income
- `netIncome` - Net income
- `netIncomeDeductions` - Net income deductions
- `bottomLineNetIncome` - Bottom line net income
- `eps` - Earnings per share (basic)
- `epsDiluted` - Earnings per share (diluted)
- `weightedAverageShsOut` - Weighted average shares outstanding
- `weightedAverageShsOutDil` - Weighted average shares outstanding (diluted)

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
- `symbol` - Ticker symbol
- `date` - Statement date
- `reportedCurrency` - Reporting currency
- `cik` - CIK number
- `filingDate` - Filing date (null when the API has not published it yet)
- `acceptedDate` - Acceptance date
- `fiscalYear` - Fiscal year
- `period` - Period
- `netIncome` - Net income
- `depreciationAndAmortization` - Depreciation and amortization
- `deferredIncomeTax` - Deferred income tax
- `stockBasedCompensation` - Stock-based compensation
- `changeInWorkingCapital` - Change in working capital
- `accountsReceivables` - Accounts receivables
- `inventory` - Inventory
- `accountsPayables` - Accounts payables
- `otherWorkingCapital` - Other working capital
- `otherNonCashItems` - Other non-cash items
- `netCashProvidedByOperatingActivities` - Net cash provided by operating activities
- `investmentsInPropertyPlantAndEquipment` - Investments in property, plant and equipment
- `acquisitionsNet` - Acquisitions (net)
- `purchasesOfInvestments` - Purchases of investments
- `salesMaturitiesOfInvestments` - Sales/maturities of investments
- `otherInvestingActivities` - Other investing activities
- `netCashProvidedByInvestingActivities` - Net cash provided by investing activities
- `netDebtIssuance` - Net debt issuance
- `longTermNetDebtIssuance` - Long-term net debt issuance
- `shortTermNetDebtIssuance` - Short-term net debt issuance
- `netStockIssuance` - Net stock issuance
- `netCommonStockIssuance` - Net common stock issuance
- `commonStockIssuance` - Common stock issuance
- `commonStockRepurchased` - Common stock repurchased
- `netPreferredStockIssuance` - Net preferred stock issuance
- `netDividendsPaid` - Net dividends paid
- `commonDividendsPaid` - Common dividends paid
- `preferredDividendsPaid` - Preferred dividends paid
- `otherFinancingActivities` - Other financing activities
- `netCashProvidedByFinancingActivities` - Net cash provided by financing activities
- `effectOfForexChangesOnCash` - Effect of forex changes on cash
- `netChangeInCash` - Net change in cash
- `cashAtEndOfPeriod` - Cash at end of period
- `cashAtBeginningOfPeriod` - Cash at beginning of period
- `operatingCashFlow` - Operating cash flow
- `capitalExpenditure` - Capital expenditure
- `freeCashFlow` - Free cash flow
- `incomeTaxesPaid` - Income taxes paid
- `interestPaid` - Interest paid

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

## Revenue Segmentation

### `revenueProductSegmentation()`

**Purpose:** Retrieve the revenue breakdown by product line for a company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `period` (PeriodQuery, default: PeriodQuery::Annual) - Period (`annual` or `quarter`)

**Return Values:** `iterable<RevenueProductSegmentation>`
- `symbol` - Ticker symbol
- `fiscalYear` - Fiscal year of the reported period
- `period` - Fiscal period (`FY`, `Q1`, `Q2`, `Q3`, `Q4`)
- `reportedCurrency` - Currency used in the filing
- `date` - Period end date
- `data` - Map of product line name to revenue (`array<string, float>`)

**API endpoint:** `https://financialmodelingprep.com/stable/revenue-product-segmentation`

---

### `revenueGeographicSegmentation()`

**Purpose:** Retrieve the revenue breakdown by geographic region for a company.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `period` (PeriodQuery, default: PeriodQuery::Annual) - Period (`annual` or `quarter`)

**Return Values:** `iterable<RevenueGeographicSegmentation>`
- `symbol` - Ticker symbol
- `fiscalYear` - Fiscal year of the reported period
- `period` - Fiscal period (`FY`, `Q1`, `Q2`, `Q3`, `Q4`)
- `reportedCurrency` - Currency used in the filing
- `date` - Period end date
- `data` - Map of geographic region name to revenue (`array<string, float>`)

**API endpoint:** `https://financialmodelingprep.com/stable/revenue-geographic-segmentation`

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
- `symbol` - Ticker symbol
- `date` - Announcement date
- `epsActual` - Actual EPS (nullable when not yet reported)
- `epsEstimated` - Estimated EPS (nullable)
- `revenueActual` - Actual revenue (nullable when not yet reported)
- `revenueEstimated` - Estimated revenue (nullable)
- `lastUpdated` - Last update date

**API endpoint:** `https://financialmodelingprep.com/stable/earnings-calendar`

---

### `detailedEarningsCalendar()`

**Purpose:** Retrieve the earnings announcement calendar with the additional report-time fields exposed by passing `includeReportTimes=true` to the stable earnings calendar endpoint. Compared to `earningsCalendar()`, each item additionally carries the announcement `time` window (`bmo` = before market open, `amc` = after market close, or `null` when unspecified), the `periodEnding` of the fiscal period being reported, the `fiscalPeriod` and `fiscalYear` being reported, and a `confirmed` flag. Requests are automatically paginated over the date range.

**Parameters:**
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date
- `logger` (LoggerInterface|null, default: null) - Optional logger

**Return Values:** `iterable<DetailedEarningsCalendarItem>`
- `symbol` - Ticker symbol
- `date` - Announcement date
- `epsActual` - Actual EPS (nullable when not yet reported)
- `epsEstimated` - Estimated EPS (nullable)
- `revenueActual` - Actual revenue (nullable when not yet reported)
- `revenueEstimated` - Estimated revenue (nullable)
- `time` - Announcement time window: `bmo` (before market open), `amc` (after market close), or `null` when unspecified
- `periodEnding` - End date of the fiscal period being reported in `Y-m-d` format (nullable)
- `fiscalPeriod` - Fiscal quarter being reported as a `Period` enum (`Q1`, `Q2`, `Q3`, `Q4`)
- `fiscalYear` - Fiscal year being reported as an integer
- `confirmed` - Whether the announcement date is confirmed
- `lastUpdated` - Last update date

**API endpoint:** `https://financialmodelingprep.com/stable/earnings-calendar?includeReportTimes=true`

---

### `splits()`

**Purpose:** Retrieve the full historical stock split record for a single symbol.

**Parameters:**
- `symbol` (string) - Ticker symbol

**Return Values:** `iterable<StockSplit>`
- `symbol` - Ticker symbol
- `date` - Split date
- `numerator` - Split ratio numerator
- `denominator` - Split ratio denominator
- `splitType` - Type of split (`stock-split`, `stock-dividend`, or `null`)

**API endpoint:** `https://financialmodelingprep.com/stable/splits`

---

### `splitsCalendar()`

**Purpose:** Retrieve stock splits calendar for a given time period.

**Parameters:**
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date
- `logger` (LoggerInterface|null, default: null) - Optional logger

**Return Values:** `iterable<StockSplit>`
- `symbol` - Ticker symbol
- `date` - Split date
- `numerator` - Split ratio numerator
- `denominator` - Split ratio denominator
- `splitType` - Type of split (`stock-split`, `stock-dividend`, or `null`)

**API endpoint:** `https://financialmodelingprep.com/stable/splits-calendar`

---

### `economicCalendar()`

**Purpose:** Retrieve calendar of upcoming and past economic data releases (macroeconomic indicators, central bank speeches, employment data, etc.) for a given time period. Automatically paginated via the standard calendar paginator to handle large result sets.

**Parameters:**
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date
- `logger` (LoggerInterface|null, default: null) - Optional logger

**Return Values:** `iterable<EconomicCalendarItem>`
- `date` - Event datetime in `Y-m-d H:i:s` format
- `country` - ISO 3166-1 alpha-2 country code (e.g. `US`, `JP`, `DE`)
- `event` - Event name (e.g. `CPI (Apr)`, `Fed Waller Speech`)
- `currency` - ISO 4217 currency code (e.g. `USD`, `EUR`, `JPY`)
- `previous` - Previous reading (nullable)
- `estimate` - Consensus estimate (nullable)
- `actual` - Actual reading (nullable when not yet released)
- `change` - Difference between actual and previous (nullable)
- `impact` - Expected market impact (`Low`, `Medium`, `High`)
- `changePercentage` - Percentage change from previous (nullable)
- `unit` - Unit of measurement (`K`, `M`, `B`, `T`, `%`, or `null`)

**API endpoint:** `https://financialmodelingprep.com/stable/economic-calendar`

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

**Purpose:** Retrieve historical daily prices for a specific symbol in a given time period. The same endpoint also serves forex pairs (e.g. `EURUSD`), which return the identical field structure.

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
- `changePercent` - Percentage change (derived from `change` and previous close when the API returns null)
- `vwap` - Volume-weighted average price (derived as the average of `open`, `high`, `low` and `close` when the API returns null)
- `label` - Description
- `changeOverTime` - Change over time

**Note:** The API returns at most 5000 records per request; the client automatically issues follow-up requests to cover the entire date range.

**API endpoint:** `https://financialmodelingprep.com/stable/historical-price-eod/full`

---

### `historicalPriceEodNonSplitAdjusted()`

**Purpose:** Retrieve historical daily prices without stock split adjustments for a specific symbol in a given time period.

**Parameters:**
- `symbol` (string) - Ticker symbol
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date

**Return Values:** `iterable<HistoricalPriceEodNonSplitAdjusted>`
- `symbol` - Ticker symbol
- `date` - Date
- `adjOpen` - Opening price without split adjustments
- `adjHigh` - Highest price without split adjustments
- `adjLow` - Lowest price without split adjustments
- `adjClose` - Closing price without split adjustments
- `volume` - Trading volume

**Note:** The API returns at most 5000 records per request; the client automatically issues follow-up requests to cover the entire date range.

**API endpoint:** `https://financialmodelingprep.com/stable/historical-price-eod/non-split-adjusted`

---

### `historicalPriceEodLight()`

**Purpose:** Retrieve a lightweight variant of historical daily prices (closing price and volume only) for a specific symbol in a given time period. Also works with forex pairs (e.g. `EURUSD`).

**Parameters:**
- `symbol` (string) - Ticker symbol
- `from` (DateTimeImmutable) - Start date
- `to` (DateTimeImmutable) - End date

**Return Values:** `iterable<HistoricalPriceEodLight>`
- `symbol` - Ticker symbol
- `date` - Date
- `price` - Closing price
- `volume` - Trading volume

**Note:** The API returns at most 5000 records per request; the client automatically issues follow-up requests to cover the entire date range.

**API endpoint:** `https://financialmodelingprep.com/stable/historical-price-eod/light`

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
- `symbol` - Ticker symbol
- `date` - Date
- `fiscalYear` - Fiscal year
- `period` - Period
- `reportedCurrency` - Reported currency
- `marketCap` - Market capitalization
- `enterpriseValue` - Enterprise value
- `evToSales` - EV to sales ratio
- `evToOperatingCashFlow` - EV to operating cash flow ratio
- `evToFreeCashFlow` - EV to free cash flow ratio
- `evToEBITDA` - EV to EBITDA ratio
- `netDebtToEBITDA` - Net debt to EBITDA ratio
- `currentRatio` - Current ratio
- `incomeQuality` - Income quality
- `grahamNumber` - Graham number
- `grahamNetNet` - Graham net-net
- `taxBurden` - Tax burden
- `interestBurden` - Interest burden
- `workingCapital` - Working capital
- `investedCapital` - Invested capital
- `returnOnAssets` - Return on assets (ROA)
- `operatingReturnOnAssets` - Operating return on assets
- `returnOnTangibleAssets` - Return on tangible assets
- `returnOnEquity` - Return on equity (ROE)
- `returnOnInvestedCapital` - Return on invested capital (ROIC)
- `returnOnCapitalEmployed` - Return on capital employed (ROCE)
- `earningsYield` - Earnings yield
- `freeCashFlowYield` - Free cash flow yield
- `capexToOperatingCashFlow` - Capex to operating cash flow ratio
- `capexToDepreciation` - Capex to depreciation ratio
- `capexToRevenue` - Capex to revenue ratio
- `salesGeneralAndAdministrativeToRevenue` - SG&A to revenue ratio
- `researchAndDevelopementToRevenue` - R&D to revenue ratio
- `stockBasedCompensationToRevenue` - Stock-based compensation to revenue ratio
- `intangiblesToTotalAssets` - Intangibles to total assets ratio
- `averageReceivables` - Average receivables
- `averagePayables` - Average payables
- `averageInventory` - Average inventory
- `daysOfSalesOutstanding` - Days sales outstanding (DSO)
- `daysOfPayablesOutstanding` - Days payables outstanding (DPO)
- `daysOfInventoryOutstanding` - Days inventory outstanding (DIO)
- `operatingCycle` - Operating cycle
- `cashConversionCycle` - Cash conversion cycle
- `freeCashFlowToEquity` - Free cash flow to equity
- `freeCashFlowToFirm` - Free cash flow to firm
- `tangibleAssetValue` - Tangible asset value
- `netCurrentAssetValue` - Net current asset value

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
- `symbol` - Ticker symbol
- `date` - Date
- `fiscalYear` - Fiscal year
- `period` - Period
- `reportedCurrency` - Reported currency
- `grossProfitMargin` - Gross profit margin
- `ebitMargin` - EBIT margin
- `ebitdaMargin` - EBITDA margin
- `operatingProfitMargin` - Operating profit margin
- `pretaxProfitMargin` - Pretax profit margin
- `continuousOperationsProfitMargin` - Continuous operations profit margin
- `netProfitMargin` - Net profit margin
- `bottomLineProfitMargin` - Bottom line profit margin
- `receivablesTurnover` - Receivables turnover
- `payablesTurnover` - Payables turnover
- `inventoryTurnover` - Inventory turnover
- `fixedAssetTurnover` - Fixed asset turnover
- `assetTurnover` - Asset turnover
- `currentRatio` - Current ratio
- `quickRatio` - Quick ratio
- `solvencyRatio` - Solvency ratio
- `cashRatio` - Cash ratio
- `priceToEarningsRatio` - Price to earnings ratio (P/E)
- `priceToEarningsGrowthRatio` - Price to earnings growth ratio (PEG)
- `forwardPriceToEarningsGrowthRatio` - Forward price to earnings growth ratio
- `priceToBookRatio` - Price to book ratio (P/B)
- `priceToSalesRatio` - Price to sales ratio (P/S)
- `priceToFreeCashFlowRatio` - Price to free cash flow ratio
- `priceToOperatingCashFlowRatio` - Price to operating cash flow ratio
- `debtToAssetsRatio` - Debt to assets ratio
- `debtToEquityRatio` - Debt to equity ratio
- `debtToCapitalRatio` - Debt to capital ratio
- `longTermDebtToCapitalRatio` - Long-term debt to capital ratio
- `financialLeverageRatio` - Financial leverage ratio
- `workingCapitalTurnoverRatio` - Working capital turnover ratio
- `operatingCashFlowRatio` - Operating cash flow ratio
- `operatingCashFlowSalesRatio` - Operating cash flow sales ratio
- `freeCashFlowOperatingCashFlowRatio` - Free cash flow to operating cash flow ratio
- `debtServiceCoverageRatio` - Debt service coverage ratio
- `interestCoverageRatio` - Interest coverage ratio
- `shortTermOperatingCashFlowCoverageRatio` - Short-term operating cash flow coverage ratio
- `operatingCashFlowCoverageRatio` - Operating cash flow coverage ratio
- `capitalExpenditureCoverageRatio` - Capital expenditure coverage ratio
- `dividendPaidAndCapexCoverageRatio` - Dividend paid and capex coverage ratio
- `dividendPayoutRatio` - Dividend payout ratio
- `dividendYield` - Dividend yield
- `dividendYieldPercentage` - Dividend yield percentage
- `revenuePerShare` - Revenue per share
- `netIncomePerShare` - Net income per share
- `interestDebtPerShare` - Interest debt per share
- `cashPerShare` - Cash per share
- `bookValuePerShare` - Book value per share
- `tangibleBookValuePerShare` - Tangible book value per share
- `shareholdersEquityPerShare` - Shareholders equity per share
- `operatingCashFlowPerShare` - Operating cash flow per share
- `capexPerShare` - Capex per share
- `freeCashFlowPerShare` - Free cash flow per share
- `netIncomePerEBT` - Net income per EBT
- `ebtPerEbit` - EBT per EBIT
- `priceToFairValue` - Price to fair value
- `debtToMarketCap` - Debt to market cap
- `effectiveTaxRate` - Effective tax rate
- `enterpriseValueMultiple` - Enterprise value multiple
- `dividendPerShare` - Dividend per share

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
