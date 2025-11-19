<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class ResetPasswordDTO extends BaseDTOAbstract
{
    public  $email;
    public  $phone;
    public  $code;
    public  $password;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


    public function credential(): array
    {
        return array_filter([
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }
}
