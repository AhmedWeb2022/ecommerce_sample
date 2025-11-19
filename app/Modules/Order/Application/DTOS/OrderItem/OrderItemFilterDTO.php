<?php

namespace App\Modules\Order\Application\DTOS\OrderItem;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class OrderItemFilterDTO extends BaseDTOAbstract
{
    public $order_item_id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $single_price;
    public $price;
    public $discount;
    public $price_after_discount;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function handleSpecialCases()
    {
        if ($this->discount > 0) {
            $this->price_after_discount = $this->discount;
        }
        if ($this->quantity > 0 && $this->single_price > 0) {
            $this->price = $this->single_price * $this->quantity;
        }
    }
}
