<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class RegisterDTO extends BaseDTOAbstract
{
    // public string $name;
    public  $name;
    public  $first_name ;
    public  $last_name ;
    public $username;
    public  $phone_code ;
    public  $phone;
    public $email;
    public  $identify_number;
    public  $password;
    public  $location_id ;
    public  $nationality_id ;
    // device information
    public  $device_id ;
    public  $device_token ;
    public  $device_type ;
    public $os_version;
    public  $device_name  ;
    public  $device_brand  ;
    public  $device_model  ;
    public $foundation_name;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }






    public function UserDevice(): array
    {
        return array_filter([
            'device_id' => $this->device_id,
            'device_token' => $this->device_token,
            'device_type' => $this->device_type,
            'os_version' => $this->os_version,
            'device_name' => $this->device_name,
            'device_brand' => $this->device_brand,
            'device_model' => $this->device_model,
        ]);
    }

    public function credential(): array
    {
        return array_filter([
            'phone' => $this->phone,
            'email' => $this->email,
        ]);
    }
}
