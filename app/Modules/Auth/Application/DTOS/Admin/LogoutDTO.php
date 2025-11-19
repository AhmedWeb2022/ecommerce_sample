<?php

namespace App\Modules\Auth\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LogoutDTO extends BaseDTOAbstract
{
    public  $deviceToken;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
