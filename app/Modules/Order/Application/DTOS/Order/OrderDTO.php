<?php

namespace App\Modules\Order\Application\DTOS\Order;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;

class OrderDTO extends BaseDTOAbstract
{
    public $order_id;
    public $user_id;
    public $price;
    public $status;
    public $products;
    public string $imageFolder = 'orders';
    public $created_by;
    public $platform;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'products'
        ];
    }
}
