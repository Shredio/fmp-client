# Přehled API Endpointů FMP Klienta

Tento dokument poskytuje kompletní přehled všech dostupných endpointů (metod) v FMP Client knihovně. Každý endpoint je popsán včetně jeho účelu a návratových hodnot.

## Obsah

- [Informace o burzách a trzích](#informace-o-burzách-a-trzích)
- [Seznamy akcií a aktiv](#seznamy-akcií-a-aktiv)
- [Informace o společnostech](#informace-o-společnostech)
- [Finanční výkazy](#finanční-výkazy)
- [Růst finančních výkazů](#růst-finančních-výkazů)
- [Kalendář událostí](#kalendář-událostí)
- [Tržní data a kotace](#tržní-data-a-kotace)
- [Finanční metriky a poměry](#finanční-metriky-a-poměry)
- [Pomocné metody](#pomocné-metody)

---

## Informace o burzách a trzích

### `availableExchanges()`

**Účel:** Získání seznamu všech dostupných burz v systému FMP.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<AvailableExchange>`
- `name` - Název burzy
- `stockExchangeHours` - Otevírací doba burzy

**API endpoint:** `https://financialmodelingprep.com/stable/available-exchanges`

---

### `allExchangeMarketHours()`

**Účel:** Získání otevírací doby pro všechny burzy.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<ExchangeMarketHours>`
- `stockExchange` - Název burzy
- `stockMarketHours` - Údaje o otevírací době
- `isTheStockMarketOpen` - Indikace, zda je burza otevřená
- `isTheEuronextMarketOpen` - Indikace stavu Euronext trhu
- `isTheForexMarketOpen` - Indikace stavu Forex trhu
- `isTheCryptoMarketOpen` - Indikace stavu krypto trhu

**API endpoint:** `https://financialmodelingprep.com/stable/all-exchange-market-hours`

---

### `marketRiskPremium()`

**Účel:** Získání tržní rizikové prémie pro různé země.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<MarketRiskPremium>`
- `country` - Název země
- `continent` - Kontinent
- `totalEquityRiskPremium` - Celková riziková prémie akcií
- `countryRiskPremium` - Riziková prémie země

**API endpoint:** `https://financialmodelingprep.com/stable/market-risk-premium`

---

### `treasuryRates()`

**Účel:** Získání aktuálních sazeb státních dluhopisů USA (Treasury rates).

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<TreasuryRate>`
- `date` - Datum
- `month1` až `month120` - Úrokové sazby pro různé splatnosti

**API endpoint:** `https://financialmodelingprep.com/stable/treasury-rates`

---

## Seznamy akcií a aktiv

### `indexList()`

**Účel:** Získání seznamu všech dostupných indexů (např. S&P 500, NASDAQ).

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<Index>`
- `symbol` - Symbol indexu
- `name` - Název indexu
- `currency` - Měna
- `stockExchange` - Burza
- `exchangeShortName` - Zkrácený název burzy

**API endpoint:** `https://financialmodelingprep.com/stable/index-list`

---

### `cryptocurrencyList()`

**Účel:** Získání seznamu všech dostupných kryptoměn.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<Cryptocurrency>`
- `symbol` - Symbol kryptoměny
- `name` - Název kryptoměny
- `currency` - Měna
- `stockExchange` - Burza
- `exchangeShortName` - Zkrácený název burzy

**API endpoint:** `https://financialmodelingprep.com/stable/cryptocurrency-list`

---

### `stockList()`

**Účel:** Získání seznamu všech dostupných akcií.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<Stock>`
- `symbol` - Ticker symbol akcie
- `name` - Název společnosti
- `price` - Aktuální cena
- `exchange` - Burza
- `exchangeShortName` - Zkrácený název burzy
- `type` - Typ instrumentu

**API endpoint:** `https://financialmodelingprep.com/stable/stock-list`

---

### `activelyTradingList()`

**Účel:** Získání seznamu aktivně obchodovaných akcií.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<ActivelyTrading>`
- `symbol` - Ticker symbol
- `name` - Název společnosti
- `price` - Aktuální cena
- `exchange` - Burza
- `exchangeShortName` - Zkrácený název burzy
- `type` - Typ instrumentu

**API endpoint:** `https://financialmodelingprep.com/stable/actively-trading-list`

---

### `symbolChangeList()`

**Účel:** Získání seznamu změn symbolů (např. při fúzích nebo rebrandingu společností).

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<SymbolChange>`
- `date` - Datum změny
- `name` - Název společnosti
- `oldSymbol` - Starý symbol
- `newSymbol` - Nový symbol

**API endpoint:** `https://financialmodelingprep.com/stable/symbol-change`

---

### `delistedCompanies()`

**Účel:** Získání seznamu vyřazených společností z burzy.

**Parametry:**
- `limit` (int, výchozí: 100) - Maximální počet záznamů
- `page` (int, výchozí: 0) - Číslo stránky

**Návratové hodnoty:** `iterable<DelistedCompany>`
- `symbol` - Symbol společnosti
- `companyName` - Název společnosti
- `exchange` - Burza
- `ipoDate` - Datum IPO
- `delistedDate` - Datum vyřazení

**API endpoint:** `https://financialmodelingprep.com/stable/delisted-companies`

---

### `financialStatementSymbolList()`

**Účel:** Získání seznamu symbolů, pro které jsou dostupné finanční výkazy.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<FinancialStatementSymbol>`
- `symbol` - Ticker symbol

**API endpoint:** `https://financialmodelingprep.com/stable/financial-statement-symbol-list`

---

## Informace o společnostech

### `companyProfile()`

**Účel:** Získání detailního profilu společnosti.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti

**Návratové hodnoty:** `CompanyProfile|null`
- `symbol` - Ticker symbol
- `price` - Aktuální cena akcie
- `beta` - Beta koeficient
- `volAvg` - Průměrný objem obchodů
- `mktCap` - Tržní kapitalizace
- `lastDiv` - Poslední dividenda
- `range` - Cenové rozpětí (52 týdnů)
- `changes` - Změna ceny
- `companyName` - Název společnosti
- `currency` - Měna
- `cik` - CIK číslo (SEC)
- `isin` - ISIN kód
- `cusip` - CUSIP kód
- `exchange` - Burza
- `exchangeShortName` - Zkrácený název burzy
- `industry` - Odvětví
- `website` - Webová stránka
- `description` - Popis společnosti
- `ceo` - Jméno CEO
- `sector` - Sektor
- `country` - Země
- `fullTimeEmployees` - Počet zaměstnanců
- `phone` - Telefon
- `address` - Adresa
- `city` - Město
- `state` - Stát
- `zip` - PSČ
- `dcfDiff` - Rozdíl DCF
- `dcf` - Diskontovaný peněžní tok (DCF)
- `image` - URL obrázku loga
- `ipoDate` - Datum IPO
- `defaultImage` - Indikace výchozího obrázku
- `isEtf` - Indikace, zda je to ETF
- `isActivelyTrading` - Indikace aktivního obchodování
- `isAdr` - Indikace, zda je to ADR
- `isFund` - Indikace, zda je to fond

**API endpoint:** `https://financialmodelingprep.com/stable/profile`

---

### `companyProfileBulk()`

**Účel:** Hromadné získání profilů všech společností (streaming velkého množství dat).

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<CompanyProfile>` (stejná struktura jako `companyProfile()`)

**API endpoint:** `https://financialmodelingprep.com/stable/profile-bulk`

---

### `sharesFloat()`

**Účel:** Získání informací o počtu volně obchodovatelných akcií pro konkrétní symbol.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti

**Návratové hodnoty:** `SharesFloat|null`
- `symbol` - Ticker symbol
- `date` - Datum
- `freeFloat` - Procento volně obchodovatelných akcií
- `floatShares` - Počet volně obchodovatelných akcií
- `outstandingShares` - Celkový počet vydaných akcií
- `source` - Zdroj dat

**API endpoint:** `https://financialmodelingprep.com/stable/shares-float`

---

### `sharesFloatAll()`

**Účel:** Získání informací o volně obchodovatelných akciích pro všechny společnosti (s možností stránkování).

**Parametry:**
- `limit` (int, výchozí: 1000) - Maximální počet záznamů (1-5000)
- `page` (int, výchozí: 0) - Číslo stránky

**Návratové hodnoty:** `iterable<SharesFloat>` (stejná struktura jako `sharesFloat()`)

**API endpoint:** `https://financialmodelingprep.com/stable/shares-float-all`

---

### `pressReleasesLatest()`

**Účel:** Získání nejnovějších tiskových zpráv společností.

**Parametry:**
- `limit` (int) - Maximální počet záznamů
- `page` (int, výchozí: 0) - Číslo stránky

**Návratové hodnoty:** `iterable<PressRelease>`
- `symbol` - Ticker symbol
- `date` - Datum vydání
- `title` - Nadpis tiskové zprávy
- `text` - Text tiskové zprávy

**API endpoint:** `https://financialmodelingprep.com/stable/news/press-releases-latest`

---

### `stockNewsLatest()`

**Účel:** Získání nejnovějších zpráv o akciích.

**Parametry:**
- `limit` (int, 1-250) - Maximální počet záznamů
- `page` (int, výchozí: 0) - Číslo stránky

**Návratové hodnoty:** `iterable<StockNews>`
- `symbol` - Ticker symbol
- `publishedDate` - Datum publikace
- `title` - Nadpis zprávy
- `image` - URL obrázku
- `site` - Zdroj zprávy
- `text` - Text zprávy
- `url` - URL zprávy

**API endpoint:** `https://financialmodelingprep.com/stable/news/stock-latest`

---

### `analystEstimates()`

**Účel:** Získání odhadů analytiků pro finanční výsledky společnosti.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `period` (string, výchozí: 'annual') - Období (annual/quarter)
- `page` (int, výchozí: 0) - Číslo stránky
- `limit` (int, výchozí: 6) - Maximální počet záznamů

**Návratové hodnoty:** `iterable<AnalystEstimate>`
- `symbol` - Ticker symbol
- `date` - Datum
- `estimatedRevenueLow` - Nejnižší odhad příjmů
- `estimatedRevenueHigh` - Nejvyšší odhad příjmů
- `estimatedRevenueAvg` - Průměrný odhad příjmů
- `estimatedEbitdaLow` - Nejnižší odhad EBITDA
- `estimatedEbitdaHigh` - Nejvyšší odhad EBITDA
- `estimatedEbitdaAvg` - Průměrný odhad EBITDA
- `estimatedEbitLow` - Nejnižší odhad EBIT
- `estimatedEbitHigh` - Nejvyšší odhad EBIT
- `estimatedEbitAvg` - Průměrný odhad EBIT
- `estimatedNetIncomeLow` - Nejnižší odhad čistého zisku
- `estimatedNetIncomeHigh` - Nejvyšší odhad čistého zisku
- `estimatedNetIncomeAvg` - Průměrný odhad čistého zisku
- `estimatedSgaExpenseLow` - Nejnižší odhad SGA výdajů
- `estimatedSgaExpenseHigh` - Nejvyšší odhad SGA výdajů
- `estimatedSgaExpenseAvg` - Průměrný odhad SGA výdajů
- `estimatedEpsAvg` - Průměrný odhad EPS
- `estimatedEpsHigh` - Nejvyšší odhad EPS
- `estimatedEpsLow` - Nejnižší odhad EPS
- `numberAnalystEstimatedRevenue` - Počet analytiků odhadujících příjmy
- `numberAnalystsEstimatedEps` - Počet analytiků odhadujících EPS

**API endpoint:** `https://financialmodelingprep.com/stable/analyst-estimates`

---

### `financialScores()`

**Účel:** Získání finančních skóre (hodnocení) pro konkrétní společnost.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti

**Návratové hodnoty:** `iterable<Scores>`
- `symbol` - Ticker symbol
- `altmanZScore` - Altman Z-Score (riziko bankrotu)
- `piotroskiScore` - Piotroski F-Score (finanční síla)
- `workingCapital` - Pracovní kapitál
- `totalAssets` - Celková aktiva
- `retainedEarnings` - Nerozdělený zisk
- `ebit` - EBIT
- `marketCap` - Tržní kapitalizace
- `totalLiabilities` - Celkové závazky
- `revenue` - Výnosy

**API endpoint:** `https://financialmodelingprep.com/stable/financial-scores`

---

### `scoresBulk()`

**Účel:** Hromadné získání finančních skóre pro všechny společnosti.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<Scores>` (stejná struktura jako `financialScores()`)

**API endpoint:** `https://financialmodelingprep.com/stable/scores-bulk`

---

## Finanční výkazy

### `balanceSheetStatement()`

**Účel:** Získání výkazu rozvahy (Balance Sheet) pro konkrétní společnost.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int, výchozí: 4) - Maximální počet záznamů (1-1000)
- `period` (PeriodQuery|Period, výchozí: FY) - Období (FY=roční, Q1-Q4=čtvrtletní)

**Návratové hodnoty:** `iterable<BalanceSheetStatement>`
Obsahuje kompletní rozvahu včetně:
- `date` - Datum výkazu
- `symbol` - Ticker symbol
- `reportedCurrency` - Měna vykazování
- `cik` - CIK číslo
- `fillingDate` - Datum podání
- `acceptedDate` - Datum přijetí
- `calendarYear` - Kalendářní rok
- `period` - Období
- Aktiva (např. `cashAndCashEquivalents`, `inventory`, `totalAssets`)
- Pasiva (např. `totalLiabilities`, `totalStockholdersEquity`)
- A mnoho dalších účetních položek

**API endpoint:** `https://financialmodelingprep.com/stable/balance-sheet-statement`

---

### `balanceSheetStatementBulk()`

**Účel:** Hromadné získání rozvah pro všechny společnosti za dané období.

**Parametry:**
- `year` (int) - Rok
- `period` (Period, výchozí: FY) - Období

**Návratové hodnoty:** `iterable<BalanceSheetStatement>` (stejná struktura jako `balanceSheetStatement()`)

**API endpoint:** `https://financialmodelingprep.com/stable/balance-sheet-statement-bulk`

---

### `incomeStatement()`

**Účel:** Získání výkazu zisku a ztráty (Income Statement) pro konkrétní společnost.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int, výchozí: 4) - Maximální počet záznamů (1-1000)
- `period` (PeriodQuery|Period, výchozí: FY) - Období

**Návratové hodnoty:** `iterable<IncomeStatement>`
Obsahuje kompletní výkaz zisku a ztráty včetně:
- `date` - Datum výkazu
- `symbol` - Ticker symbol
- `reportedCurrency` - Měna
- `revenue` - Výnosy
- `costOfRevenue` - Náklady na výnosy
- `grossProfit` - Hrubý zisk
- `operatingIncome` - Provozní zisk
- `netIncome` - Čistý zisk
- `eps` - Zisk na akcii
- `ebitda` - EBITDA
- A mnoho dalších položek výsledovky

**API endpoint:** `https://financialmodelingprep.com/stable/income-statement`

---

### `incomeStatementBulk()`

**Účel:** Hromadné získání výkazů zisku a ztráty pro všechny společnosti za dané období.

**Parametry:**
- `year` (int) - Rok
- `period` (Period, výchozí: FY) - Období

**Návratové hodnoty:** `iterable<IncomeStatement>` (stejná struktura jako `incomeStatement()`)

**API endpoint:** `https://financialmodelingprep.com/stable/income-statement-bulk`

---

### `cashFlowStatement()`

**Účel:** Získání výkazu peněžních toků (Cash Flow Statement) pro konkrétní společnost.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int, výchozí: 4) - Maximální počet záznamů (1-1000)
- `period` (PeriodQuery|Period, výchozí: FY) - Období

**Návratové hodnoty:** `iterable<CashFlowStatement>`
Obsahuje kompletní výkaz cash flow včetně:
- `date` - Datum výkazu
- `symbol` - Ticker symbol
- `reportedCurrency` - Měna
- `operatingCashFlow` - Provozní peněžní tok
- `capitalExpenditure` - Kapitálové výdaje
- `freeCashFlow` - Volný peněžní tok
- `netCashProvidedByOperatingActivities` - Peněžní tok z provozní činnosti
- `netCashUsedForInvestingActivities` - Peněžní tok z investiční činnosti
- `netCashUsedProvidedByFinancingActivities` - Peněžní tok z finanční činnosti
- A mnoho dalších položek

**API endpoint:** `https://financialmodelingprep.com/stable/cash-flow-statement`

---

### `cashFlowStatementBulk()`

**Účel:** Hromadné získání výkazů peněžních toků pro všechny společnosti za dané období.

**Parametry:**
- `year` (int) - Rok
- `period` (Period, výchozí: FY) - Období

**Návratové hodnoty:** `iterable<CashFlowStatement>` (stejná struktura jako `cashFlowStatement()`)

**API endpoint:** `https://financialmodelingprep.com/stable/cash-flow-statement-bulk`

---

### `latestFinancialStatements()`

**Účel:** Získání nejnovějších finančních výkazů napříč všemi společnostmi.

**Parametry:**
- `page` (int, výchozí: 0) - Číslo stránky (0-100)
- `limit` (int, výchozí: 250) - Maximální počet záznamů (1-250)

**Návratové hodnoty:** `iterable<LatestFinancialStatement>`
- `symbol` - Ticker symbol
- `annualFillingDate` - Datum posledního ročního výkazu
- `quarterFillingDate` - Datum posledního čtvrtletního výkazu

**API endpoint:** `https://financialmodelingprep.com/stable/latest-financial-statements`

---

## Růst finančních výkazů

### `incomeStatementGrowth()`

**Účel:** Získání meziročního růstu položek výkazu zisku a ztráty.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int|null, výchozí: null) - Maximální počet záznamů
- `period` (PeriodQuery|null, výchozí: null) - Období

**Návratové hodnoty:** `iterable<IncomeStatementGrowth>`
Obsahuje procentuální růst všech položek z Income Statement (např. růst výnosů, růst čistého zisku atd.)

**API endpoint:** `https://financialmodelingprep.com/api/v3/income-statement-growth/{symbol}`

---

### `incomeStatementGrowthBulk()`

**Účel:** Hromadné získání růstu výkazu zisku a ztráty pro všechny společnosti.

**Parametry:**
- `year` (int) - Rok
- `period` (Period) - Období

**Návratové hodnoty:** `iterable<IncomeStatementGrowthBulk>`

**API endpoint:** `https://financialmodelingprep.com/stable/income-statement-growth-bulk`

---

### `balanceSheetStatementGrowth()`

**Účel:** Získání meziročního růstu položek rozvahy.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int|null, výchozí: null) - Maximální počet záznamů
- `period` (PeriodQuery|null, výchozí: null) - Období

**Návratové hodnoty:** `iterable<BalanceSheetStatementGrowth>`
Obsahuje procentuální růst všech položek z Balance Sheet

**API endpoint:** `https://financialmodelingprep.com/api/v3/balance-sheet-statement-growth/{symbol}`

---

### `balanceSheetStatementGrowthBulk()`

**Účel:** Hromadné získání růstu rozvahy pro všechny společnosti.

**Parametry:**
- `year` (int) - Rok
- `period` (Period) - Období

**Návratové hodnoty:** `iterable<BalanceSheetStatementGrowthBulk>`

**API endpoint:** `https://financialmodelingprep.com/stable/balance-sheet-statement-growth-bulk`

---

### `cashFlowStatementGrowth()`

**Účel:** Získání meziročního růstu položek výkazu peněžních toků.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int|null, výchozí: null) - Maximální počet záznamů
- `period` (PeriodQuery|null, výchozí: null) - Období

**Návratové hodnoty:** `iterable<CashFlowStatementGrowth>`
Obsahuje procentuální růst všech položek z Cash Flow Statement

**API endpoint:** `https://financialmodelingprep.com/api/v3/cash-flow-statement-growth/{symbol}`

---

### `cashFlowStatementGrowthBulk()`

**Účel:** Hromadné získání růstu cash flow pro všechny společnosti.

**Parametry:**
- `year` (int) - Rok
- `period` (Period) - Období

**Návratové hodnoty:** `iterable<CashFlowStatementGrowthBulk>`

**API endpoint:** `https://financialmodelingprep.com/stable/cash-flow-statement-growth-bulk`

---

## Kalendář událostí

### `dividendsCalendar()`

**Účel:** Získání kalendáře dividend v daném časovém období.

**Parametry:**
- `from` (DateTimeImmutable) - Počáteční datum
- `to` (DateTimeImmutable) - Konečné datum
- `logger` (LoggerInterface|null, výchozí: null) - Volitelný logger

**Návratové hodnoty:** `iterable<Dividend>`
- `date` - Datum dividendy
- `label` - Popis
- `adjDividend` - Upravená dividenda
- `symbol` - Ticker symbol
- `dividend` - Výše dividendy
- `recordDate` - Datum záznamu
- `paymentDate` - Datum výplaty
- `declarationDate` - Datum vyhlášení

**API endpoint:** `https://financialmodelingprep.com/stable/dividends-calendar`

---

### `dividends()`

**Účel:** Získání historických dividend pro konkrétní symbol.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int|null, výchozí: null) - Maximální počet záznamů (1-1000)

**Návratové hodnoty:** `iterable<Dividend>` (stejná struktura jako `dividendsCalendar()`)

**API endpoint:** `https://financialmodelingprep.com/stable/dividends`

---

### `earningsCalendar()`

**Účel:** Získání kalendáře zveřejnění finančních výsledků v daném časovém období.

**Parametry:**
- `from` (DateTimeImmutable) - Počáteční datum
- `to` (DateTimeImmutable) - Konečné datum
- `logger` (LoggerInterface|null, výchozí: null) - Volitelný logger

**Návratové hodnoty:** `iterable<EarningsCalendarItem>`
- `date` - Datum zveřejnění
- `symbol` - Ticker symbol
- `eps` - Skutečný EPS
- `epsEstimated` - Odhadovaný EPS
- `time` - Čas zveřejnění
- `revenue` - Skutečné výnosy
- `revenueEstimated` - Odhadované výnosy
- `updatedFromDate` - Datum poslední aktualizace
- `fiscalDateEnding` - Konec fiskálního období

**API endpoint:** `https://financialmodelingprep.com/stable/earnings-calendar`

---

### `splitsCalendar()`

**Účel:** Získání kalendáře štěpení akcií (stock splits) v daném časovém období.

**Parametry:**
- `from` (DateTimeImmutable) - Počáteční datum
- `to` (DateTimeImmutable) - Konečné datum
- `logger` (LoggerInterface|null, výchozí: null) - Volitelný logger

**Návratové hodnoty:** `iterable<SplitsCalendarItem>`
- `date` - Datum štěpení
- `label` - Popis
- `symbol` - Ticker symbol
- `numerator` - Čitatel poměru štěpení
- `denominator` - Jmenovatel poměru štěpení

**API endpoint:** `https://financialmodelingprep.com/stable/splits-calendar`

---

## Tržní data a kotace

### `batchExchangeQuote()`

**Účel:** Získání aktuálních kotací pro všechny akcie na konkrétní burze (zjednodušená verze).

**Parametry:**
- `exchange` (string) - Název burzy (např. "NYSE", "NASDAQ")

**Návratové hodnoty:** `iterable<BatchExchangeQuote>`
- `symbol` - Ticker symbol
- `name` - Název společnosti
- `price` - Aktuální cena
- `changesPercentage` - Procentuální změna
- `change` - Absolutní změna
- `dayLow` - Denní minimum
- `dayHigh` - Denní maximum
- `yearHigh` - Roční maximum
- `yearLow` - Roční minimum
- `marketCap` - Tržní kapitalizace
- `priceAvg50` - 50denní průměrná cena
- `priceAvg200` - 200denní průměrná cena
- `exchange` - Burza
- `volume` - Objem obchodů
- `avgVolume` - Průměrný objem
- `open` - Otevírací cena
- `previousClose` - Předchozí závěrečná cena
- `eps` - Zisk na akcii
- `pe` - P/E poměr
- `sharesOutstanding` - Počet vydaných akcií
- `timestamp` - Časové razítko

**API endpoint:** `https://financialmodelingprep.com/stable/batch-exchange-quote`

---

### `batchExchangeQuoteDetailed()`

**Účel:** Získání detailních kotací pro všechny akcie na konkrétní burze (rozšířená verze).

**Parametry:**
- `exchange` (string) - Název burzy

**Návratové hodnoty:** `iterable<BatchExchangeDetailedQuote>`
Obsahuje všechny údaje z `BatchExchangeQuote` plus dodatečné informace:
- `earningsAnnouncement` - Datum zveřejnění výsledků
- `timestamp` - Časové razítko

**API endpoint:** `https://financialmodelingprep.com/stable/batch-exchange-quote`

---

### `batchForexQuotes()`

**Účel:** Získání aktuálních kotací všech měnových párů (Forex).

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<BatchForexQuote>`
- `symbol` - Symbol měnového páru (např. "EUR/USD")
- `name` - Název páru
- `price` - Aktuální kurz
- `changesPercentage` - Procentuální změna
- `change` - Absolutní změna
- `dayLow` - Denní minimum
- `dayHigh` - Denní maximum
- `yearHigh` - Roční maximum
- `yearLow` - Roční minimum
- `marketCap` - Tržní kapitalizace
- `priceAvg50` - 50denní průměr
- `priceAvg200` - 200denní průměr
- `exchange` - Burza/platforma
- `volume` - Objem
- `avgVolume` - Průměrný objem
- `open` - Otevírací kurz
- `previousClose` - Předchozí zavření
- `eps` - EPS (není relevantní pro forex)
- `pe` - P/E (není relevantní pro forex)
- `earningsAnnouncement` - Datum zveřejnění
- `sharesOutstanding` - Počet akcií
- `timestamp` - Časové razítko

**API endpoint:** `https://financialmodelingprep.com/stable/batch-forex-quotes`

---

### `eodBulkQuotes()`

**Účel:** Získání závěrečných denních kotací (End-of-Day) pro všechny akcie k danému datu.

**Parametry:**
- `date` (DateTimeImmutable) - Datum, pro které chcete získat EOD data

**Návratové hodnoty:** `iterable<EodQuote>`
- `symbol` - Ticker symbol
- `open` - Otevírací cena
- `high` - Nejvyšší cena dne
- `low` - Nejnižší cena dne
- `close` - Zavírací cena
- `volume` - Objem obchodů

**API endpoint:** `https://financialmodelingprep.com/stable/eod-bulk`

---

### `historicalPriceEod()`

**Účel:** Získání historických denních cen pro konkrétní symbol v daném časovém období.

**Parametry:**
- `symbol` (string) - Ticker symbol
- `from` (DateTimeImmutable) - Počáteční datum
- `to` (DateTimeImmutable) - Konečné datum

**Návratové hodnoty:** `iterable<HistoricalPriceEod>`
- `date` - Datum
- `open` - Otevírací cena
- `high` - Nejvyšší cena
- `low` - Nejnižší cena
- `close` - Zavírací cena
- `adjClose` - Upravená zavírací cena
- `volume` - Objem obchodů
- `unadjustedVolume` - Neupravený objem
- `change` - Změna ceny
- `changePercent` - Procentuální změna
- `vwap` - Vážený průměrný kurz
- `label` - Popis
- `changeOverTime` - Změna v čase

**API endpoint:** `https://financialmodelingprep.com/stable/historical-price-eod/full`

---

### `historicalChart()`

**Účel:** Získání historických intradenních cenových dat s různými časovými intervaly.

**Parametry:**
- `symbol` (string) - Ticker symbol
- `interval` (TimeInterval) - Časový interval (1min, 5min, 15min, 30min, 1hour, 4hour)
- `from` (DateTimeImmutable) - Počáteční datum
- `to` (DateTimeImmutable) - Konečné datum

**Návratové hodnoty:** `iterable<HistoricalChart>`
- `date` - Datum a čas
- `open` - Otevírací cena
- `low` - Nejnižší cena
- `high` - Nejvyšší cena
- `close` - Zavírací cena
- `volume` - Objem obchodů

**API endpoint:** `https://financialmodelingprep.com/stable/historical-chart/{interval}`

---

## Finanční metriky a poměry

### `keyMetrics()`

**Účel:** Získání klíčových finančních metrik pro konkrétní společnost.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int, výchozí: 80) - Maximální počet záznamů
- `period` (PeriodQuery, výchozí: Annual) - Období

**Návratové hodnoty:** `iterable<KeyMetrics>`
Obsahuje mnoho klíčových metrik jako:
- `revenuePerShare` - Výnosy na akcii
- `netIncomePerShare` - Čistý zisk na akcii
- `operatingCashFlowPerShare` - Provozní cash flow na akcii
- `freeCashFlowPerShare` - Volný cash flow na akcii
- `cashPerShare` - Hotovost na akcii
- `bookValuePerShare` - Účetní hodnota na akcii
- `tangibleBookValuePerShare` - Hmotná účetní hodnota na akcii
- `shareholdersEquityPerShare` - Vlastní kapitál na akcii
- `interestDebtPerShare` - Úročený dluh na akcii
- `marketCap` - Tržní kapitalizace
- `enterpriseValue` - Podniková hodnota
- `peRatio` - P/E poměr
- `priceToSalesRatio` - P/S poměr
- `pocfratio` - Poměr ceny k provoznímu cash flow
- `pfcfRatio` - Poměr ceny k volnému cash flow
- `pbRatio` - P/B poměr
- `ptbRatio` - Poměr ceny k hmotné účetní hodnotě
- `evToSales` - EV/Sales
- `evToOperatingCashFlow` - EV/Provozní cash flow
- `evToFreeCashFlow` - EV/Volný cash flow
- `earningsYield` - Výnosnost zisku
- `freeCashFlowYield` - Výnosnost volného cash flow
- `debtToEquity` - Poměr dluhu k vlastnímu kapitálu
- `debtToAssets` - Poměr dluhu k aktivům
- `netDebtToEBITDA` - Čistý dluh k EBITDA
- A mnoho dalších metrik

**API endpoint:** `https://financialmodelingprep.com/stable/key-metrics`

---

### `keyMetricsTtm()`

**Účel:** Získání klíčových metrik TTM (Trailing Twelve Months - za posledních 12 měsíců) pro konkrétní symbol.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti

**Návratové hodnoty:** `iterable<KeyMetricsTtm>` (stejná struktura jako `keyMetrics()`)

**API endpoint:** `https://financialmodelingprep.com/stable/key-metrics-ttm`

---

### `keyMetricsTtmBulk()`

**Účel:** Hromadné získání klíčových metrik TTM pro všechny společnosti.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<KeyMetricsTtm>` (stejná struktura jako `keyMetrics()`)

**API endpoint:** `https://financialmodelingprep.com/stable/key-metrics-ttm-bulk`

---

### `ratios()`

**Účel:** Získání finančních poměrů (ratios) pro konkrétní společnost.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti
- `limit` (int, výchozí: 80) - Maximální počet záznamů
- `period` (PeriodQuery, výchozí: Annual) - Období

**Návratové hodnoty:** `iterable<Ratios>`
Obsahuje širokou škálu finančních poměrů včetně:
- Poměry likvidity (currentRatio, quickRatio, cashRatio)
- Poměry zadluženosti (debtRatio, debtEquityRatio)
- Poměry rentability (grossProfitMargin, operatingProfitMargin, netProfitMargin, ROA, ROE)
- Poměry efektivity (assetTurnover, inventoryTurnover)
- Poměry tržní hodnoty (priceEarningsRatio, priceToBookRatio, dividendYield)
- A mnoho dalších

**API endpoint:** `https://financialmodelingprep.com/stable/ratios`

---

### `ratiosTtm()`

**Účel:** Získání finančních poměrů TTM (za posledních 12 měsíců) pro konkrétní symbol.

**Parametry:**
- `symbol` (string) - Ticker symbol společnosti

**Návratové hodnoty:** `iterable<RatiosTtm>` (stejná struktura jako `ratios()`)

**API endpoint:** `https://financialmodelingprep.com/stable/ratios-ttm`

---

### `ratiosTtmBulk()`

**Účel:** Hromadné získání finančních poměrů TTM pro všechny společnosti.

**Parametry:** Žádné

**Návratové hodnoty:** `iterable<RatiosTtm>` (stejná struktura jako `ratios()`)

**API endpoint:** `https://financialmodelingprep.com/stable/ratios-ttm-bulk`

---

## Pomocné metody

### `promise()`

**Účel:** Vytvoření asynchronního příslibu (Promise) pro neblokující operace.

**Parametry:**
- `fn` (callable) - Funkce, která má být spuštěna asynchronně

**Návratové hodnoty:** `FmpPromise<TReturn>` - Promise objekt pro práci s asynchronními operacemi

---

### `iteratePages()`

**Účel:** Pomocná metoda pro iteraci přes stránkované výsledky API.

**Parametry:**
- `callback` (callable) - Funkce, která vrací data pro danou stránku
- `initialPage` (int, výchozí: 0) - Počáteční stránka
- `maxPage` (int|null, výchozí: null) - Maximální stránka (včetně)
- `maxPageGuard` (int|null, výchozí: null) - Bezpečnostní limit pro prevenci nekonečných smyček

**Návratové hodnoty:** `iterable<T>` - Iterátor přes všechny položky ze všech stránek

---

### `withStrictMode()`

**Účel:** Vytvoření nové instance klienta se zapnutým/vypnutým striktním režimem.

**Parametry:**
- `strictMode` (bool) - True pro zapnutí striktního režimu (výjimky při chybách dat)

**Návratové hodnoty:** `FmpClient` - Nová instance klienta s daným nastavením

---

## Poznámky k použití

### Paměťová efektivita
Všechny metody vracející `iterable` používají streaming - data jsou zpracovávána průběžně, bez načtení celé odpovědi do paměti. To umožňuje práci s velkými datasety efektivně.

### Asynchronní operace
Pro paralelní volání více endpointů použijte metodu `promise()`, která využívá PHP Fibers pro neblokující operace.

### Striktní režim
Ve výchozím nastavení jsou chyby validace dat logované (pokud je nastaven handler), ale nezastaví zpracování. Ve striktním režimu (`strictMode = true`) jsou vyhazovány výjimky.

### Stránkování
Mnoho endpointů podporuje stránkování pomocí parametrů `page` a `limit`. Pro automatickou iteraci přes všechny stránky použijte pomocnou metodu `iteratePages()`.

### Bulk endpointy
Endpointy s příponou "Bulk" jsou optimalizované pro získání velkého množství dat najednou a často vrací data ve formátu CSV pro lepší výkon.
