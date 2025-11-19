<?php

namespace App\Modules\Order\Application\DTOS\OrderItem;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class OrderItemDTO extends BaseDTOAbstract
{
    public $order_item_id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $single_price; // for single product before tax and offer|discount
    public $price; // for all products with its quantity
    public $discount; // for single product 
    public $tax_amount;
    public $price_after_discount;
    public $price_after_tax;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function handleSpecialCases()
    {
        $this->price = $this->single_price * $this->quantity;

        $this->price_after_discount = $this->discount > 0 ? $this->discount * $this->quantity : $this->price;

        $this->price_after_tax =  $this->tax_amount &&  $this->tax_amount > 0 ?  $this->price_after_discount +  $this->tax_amount : $this->price_after_discount;
    }
}
