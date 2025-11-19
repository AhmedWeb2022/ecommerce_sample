<?php

namespace App\Modules\Auth\Http\Enums;


enum EmployeeTypeEnum: string
{
    case EMPLOYEE = 'employee';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';
}
