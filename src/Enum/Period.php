<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Enum;

enum Period: string
{
    case FY = 'FY';
    case Q1 = 'Q1';
    case Q2 = 'Q2';
    case Q3 = 'Q3';
    case Q4 = 'Q4';
}

enum PeriodQuery: string
{
    case FY = 'FY';
    case Q1 = 'Q1';
    case Q2 = 'Q2';
    case Q3 = 'Q3';
    case Q4 = 'Q4';
    case Annual = 'annual';
    case Quarter = 'quarter';
}