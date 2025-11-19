<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class UserDTO extends BaseDTOAbstract
{
    public  $user_id;
    public  $name;
    public  $email;
    public  $password;
    public  $phone;
    public $image;
    public string $imageFolder = "users";

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
