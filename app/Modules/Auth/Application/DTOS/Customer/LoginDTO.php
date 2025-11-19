<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LoginDTO extends BaseDTOAbstract
{
    public  $credential; // can be email or phone
    public  $email;
    public  $phone;
    public  $phone_code;
    public  $password;
        // device information
    public ?string $device_id = null;
    public ?string $device_token = null;
    public ?string $device_type = null;
    public $os_version;
    public $device_name;
    public $device_brand;
    public $device_model;
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
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }
}
