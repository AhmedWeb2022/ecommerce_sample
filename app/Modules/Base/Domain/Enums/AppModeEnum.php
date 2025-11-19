<?php


namespace App\Modules\Base\Domain\Enums;

enum AppModeEnum: string
{
    case DEV = 'dev';
    case PRODUCTION = 'production';
    case TEST = 'test';
}
