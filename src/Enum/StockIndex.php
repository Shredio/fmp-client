<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Enum;

/**
 * Indexes the API publishes the constituents of. The value is the prefix of the endpoint path.
 */
enum StockIndex: string
{
	case Sp500 = 'sp500';
	case Nasdaq = 'nasdaq';
	case DowJones = 'dowjones';
}
