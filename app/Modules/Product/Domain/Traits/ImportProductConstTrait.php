<?php

namespace App\Modules\Product\Domain\Traits;

use App\Modules\Product\Http\Imports\AddProductImport;
use App\Modules\Product\Http\Imports\UpdateProductImport;

trait ImportProductConstTrait
{
    const INTERNATIONAL_CODE = 'international_barcode';
    const PUBLIC_PRICE = 'audience_price';
    const PURCHASE_PRICE = 'original_price';
    const EXPIRE_DATE = 'expire_date';
    const CARTON_QUANTITY = 'carton_quantity';
    const STOCK = 'stock';
    // const TITLE_AR = 'name_ar';
    // const TITLE_EN = 'name_en';
    // const DESCRIPTION_AR = 'description_ar';
    // const DESCRIPTION_EN = 'description_en';
    const TITLE = 'name';
    const DESCRIPTION = 'description';
    const SUBTITLE = 'subtitle';
    const CATEGORY_ID = 'category';
    const CATEGORY_SLUG = 'category_slug';
    const SUBCATEGORY_ID = 'subcategory';
    const SUBCATEGORY_SLUG = 'subcategory_slug';
    const BRAND_SLUG = 'brand_slug';
    const BRAND_ID = 'brand';
    const OFFER_PRICE = 'offer_price';
    const OFFER_TYPE = 'offer_type';
    const IS_ACTIVE = 'is_active';
    const IS_FOR_SALE = 'is_for_sale';
    const FROM_DATE = 'from_date';
    const TO_DATE = 'to_date';
    const HAS_TAXES = 'has_tax';
    // const TAX_AMOUNT = 'tax_amount';
    // const TAX_TYPE = 'tax_type';
}
