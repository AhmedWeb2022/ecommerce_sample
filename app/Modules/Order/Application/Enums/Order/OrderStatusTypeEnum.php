<?php

namespace App\Modules\Order\Application\Enums\Order;

use App\Modules\Base\Domain\Traits\HasMetadata;

enum OrderStatusTypeEnum: int
{
    use HasMetadata;

    case AWAITING_APPROVAL = 1; // Order is pending // Awaiting approval from admin
    case AWAITING_RECEIPT = 2; // Order is being processed // Awaiting receipt from customer this status the admin transfer to it after approval
    case WAITING_INVOICE_CHECK = 7; // Order is waiting for invoice check
    case PEREPERATION = 3; // Order has been completed in progress
    case INSHIPPING = 8; // Order is being shipped // Awaiting shipping from admin
    case DELIVERED = 5; // Order has been refunded
    case CANCELLED = 4; // Order has been cancelled
    case REJECTED = 6; // Order has failed

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getMetadata(): array
    {
        return match ($this) {
            self::AWAITING_APPROVAL => [
                'label' => 'Awaiting Approval',
                'value' => 1
            ],
            self::AWAITING_RECEIPT => [
                'label' => 'Awaiting Receipt',
                'value' => 2
            ],
            self::PEREPERATION => [
                'label' => 'Perperation',
                'value' => 3
            ],
            self::INSHIPPING => [
                'label' => 'In Shipping',
                'value' => 8
            ],
            self::DELIVERED => [
                'label' => 'Delivered',
                'value' => 5
            ],
            self::CANCELLED => [
                'label' => 'Cancelled',
                'value' => 4
            ],
            self::REJECTED => [
                'label' => 'Rejected',
                'value' => 6
            ],
            self::WAITING_INVOICE_CHECK => [
                'label' => 'Waiting Invoice Check',
                'value' => 7,
                'color' => [
                'background' => 'red',
                'text' => 'white'   
                ]
            ],
            default => [
                'label' => 'Unknown',
                'value' => 0
            ]
        };
    }
}
