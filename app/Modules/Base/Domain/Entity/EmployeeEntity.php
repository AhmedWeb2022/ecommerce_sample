<?php

namespace App\Modules\Base\Domain\Entity;


class EmployeeEntity
{

    public int $id;
    public string $name;

    public function __construct(int $id = 0, string $name = '')
    {
        $this->id = $id;
        $this->name = $name;
    }


    public function getId()
    {
        // Get the ID of the Employee
        return   $this->id;
    }
    public function getName()
    {
        // Get the name of the Employee
        return  $this->name;
    }

    public static function example(): EmployeeEntity
    {
        return new EmployeeEntity(id: 1, name: 'John Doe');
    }
}
