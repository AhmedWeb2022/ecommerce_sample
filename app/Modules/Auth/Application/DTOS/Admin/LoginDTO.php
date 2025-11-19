<?php

namespace App\Modules\Auth\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LoginDTO extends BaseDTOAbstract
{
    public  $email;
    public  $phone;
    public  $password;
    public  $identify_number;
    public  $device_token;
    public  $device_id;
    public  $device_type;
    public  $device_os;
    public  $device_os_version;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }






    public function EmployeeDevice(): array
    {
        return array_filter([
            'device_id' => $this->device_id,
            'device_token' => $this->device_token,
            'device_type' => $this->device_type,
            'device_os' => $this->device_os,
            'device_os_version' => $this->device_os_version,
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
