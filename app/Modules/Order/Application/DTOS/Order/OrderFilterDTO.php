<?php

namespace App\Modules\Order\Application\DTOS\Order;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class OrderFilterDTO extends BaseDTOAbstract
{
    public $order_id;
    public $address_id;
    public $reciept;
    public $status;
    public $user_id;
    public $order_number;
    public $duration_id;
    public string $imageFolder = 'orders';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

      public function excludedAttributes(): array
    {
        return [
            'duration_id', // This field is not used in the filter
        ]; // Default empty array
    }
}
