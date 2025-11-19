<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Auth\Domain\Entities\AdminEntity;
use App\Modules\Auth\Http\Requests\Api\Admin\RegisterRequest;


class CustomerDTO extends BaseDTOAbstract
{
    public  $username;
    public  $email;
    public  $password;
    public  $first_name;
    public  $last_name;
    public  $phone;
    public  $address;
    public  $gender;
    public  $image;
    public  $cover_image;
    public  $is_blocked;
    public  $is_verified;
    public  $is_online;
    public  $is_approved;
    public  $device_token;
    public  $device_id;
    public  $device_type;
    public  $device_brand;
    public  $device_model;
    public  $device_name;
    public  $os_version;
    public  $id_number;
    public $user_id;



        public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

}
