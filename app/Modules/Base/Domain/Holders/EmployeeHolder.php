<?php

namespace App\Modules\Base\Domain\Holders;

use App\Modules\Base\Domain\Entity\EmployeeEntity;


class EmployeeHolder
{

    public static function getEmployee(): EmployeeEntity
    {
        // Return the Employee entity
        $employee =  EmployeeEntity::example();
        return $employee;
    }
}
