<?php

namespace App\Modules\Category\Application\Enums\Search;


enum SearchTypeEnum: int
{
    case CATEGORY = 1;
    case BRAND = 2;
    case PRODUCT = 3;


    public static function values(): array
    {
        return [
            self::CATEGORY->value,
            self::BRAND->value,
            self::PRODUCT->value,
        ];
    }
}
