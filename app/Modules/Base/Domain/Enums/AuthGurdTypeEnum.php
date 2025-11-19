<?php

namespace App\Modules\Base\Domain\Enums;

enum AuthGurdTypeEnum: string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case API = 'api';
    case USER = 'user';
}
