<?php

namespace App\Modules\Order\Infrastructure\Persistence\Repositories\OrderItem;

use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Order\Infrastructure\Persistence\Models\OrderItem\OrderItem;

class OrderItemRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new OrderItem());
    }

}
