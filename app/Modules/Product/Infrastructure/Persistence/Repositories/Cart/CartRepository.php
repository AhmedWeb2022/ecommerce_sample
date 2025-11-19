<?php

namespace App\Modules\Product\Infrastructure\Persistence\Repositories\Cart;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Product\Infrastructure\Persistence\Models\Cart\Cart;

class CartRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Cart());
    }

    public function getCartsQuantitySummation($productId, $userId): int
    {
        return $this->getModel()::where(['user_id' => $userId,'product_id'=> $productId])->sum('quantity');
    }


}
