<?php

namespace App\Modules\Product\Application\DTOS\ProductOffer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ProductOfferFilterDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $id; 
    public $product_offer_id; 
    public $product_id;
    public $category_id;
    public $brand_id;
    public $title;
    public $description;
    public $word;
    public $is_active;
    public $expire_date;
    public $stock;
    public $public_price;
    public $purchase_price;
    public $offer_price;
    public $pending_stock;
    public $has_discount;
    public $discount_type;
    public $order;
    public $slug;
    public $international_code;
    public $is_out_of_stock;
    public $limit;
    public $paginate;
    public $created_by;
    public $filter;
    public $barcode;
    public $quantity;
    public function __construct(array $data = [])
    {
        $this->handleSpecialCases();
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'excludeAttributes',
            'limit',
            'paginate',
            'word',
            'filter',
            // 'category_id',
            'sub_category_ids',
            'barcode',
            'quantity',
        ];
    }

    public function handleSpecialCases()
    {
        if (isset($this->barcode) && !empty($this->barcode)) {
            $this->international_code = $this->barcode;
        }
        $this->id = $this->product_offer_id;
    }
}
