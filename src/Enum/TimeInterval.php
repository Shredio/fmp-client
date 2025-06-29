<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Enum;

enum TimeInterval: string
{
    case OneMin = '1min';
    case FiveMin = '5min';
    case FifteenMin = '15min';
    case ThirtyMin = '30min';
    case OneHour = '1hour';
    case FourHour = '4hour';
}