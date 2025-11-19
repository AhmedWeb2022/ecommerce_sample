<?php

namespace App\Modules\Order\Application\Enums\Order;

enum OrderDurationTypeEnum: int
{
    case LAST_THREE_MONTH = 1;
    case LAST_SIX_MONTH = 2;
    case LAST_YEAR = 3;


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
