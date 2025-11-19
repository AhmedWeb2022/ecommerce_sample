<?php

namespace App\Modules\Order\Infrastructure\Persistence\Repositories\Order;

use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Order\Infrastructure\Persistence\Models\Order\Order;
class OrderRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Order());
    }
}
