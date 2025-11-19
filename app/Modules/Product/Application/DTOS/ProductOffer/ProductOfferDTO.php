<?php

namespace App\Modules\Product\Application\DTOS\ProductOffer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ProductOfferDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $id;
    public $product_offer_id;
    public $product_id;
    public $is_active;
    public $created_by;
    public $updated_by;
    public $offer_price;
    public $from_date;
    public $to_date;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function handleSpecialCases()
    {
        $this->id = $this->product_offer_id;
    }
}
