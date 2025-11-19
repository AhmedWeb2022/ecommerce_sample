<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class LogoutDTO extends BaseDTOAbstract
{
    public  $deviceToken;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
