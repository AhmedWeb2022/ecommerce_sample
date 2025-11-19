<?php

namespace App\Modules\Auth\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class EmployeeFilterDTO extends BaseDTOAbstract
{
    public  $name;
    public  $email;
    public $word;
    public $limit;
    public $paginate;
    public $employee_type;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'employee_type',
            'word'
        ];
    }
}
