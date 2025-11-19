<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ChangePasswordDTO extends BaseDTOAbstract
{
    public  $old_password;
    public  $new_password;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
