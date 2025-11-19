<?php

namespace App\Modules\Product\Application\DTOS\Cart;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CartDTO extends BaseDTOAbstract
{

    public int $cart_id;
    public int $user_id;
    public int $product_id;
    public int $quantity;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
