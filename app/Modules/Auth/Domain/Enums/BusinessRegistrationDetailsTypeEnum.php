<?php


namespace App\Modules\Auth\Domain\Enums;

use App\Modules\Base\Domain\Traits\HasMetadata;

enum BusinessRegistrationDetailsTypeEnum: int
{
    use HasMetadata;

    case COMMERCIAL_REGISTER = 1;
    case TAX_REGISTER = 2;

    public  function getMetadata(): array
    {
        return match ($this) {
            self::COMMERCIAL_REGISTER => [
                'label' => 'Commercial Register',
                'value' => 1
            ],
            self::TAX_REGISTER => [
                'label' => 'Tax Register',
                'value' => 2
            ],
            default => [
                'label' => '',
                'value' => 0
            ]
        };
    }
}
