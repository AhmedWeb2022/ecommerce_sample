<?php

namespace App\Modules\Product\Infrastructure\Persistence\Repositories\Product;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;

class ProductRepository extends BaseRepositoryAbstract
{

    public function __construct()
    {
        $this->setModel(new Product());
    }
}
