<?php

namespace App\Modules\Auth\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class EmployeeDTO extends BaseDTOAbstract
{
    public  $employee_id;

    public  $first_name;
    public  $last_name;
    public  $name;
    public  $username;
    public  $email;
    public  $phone;
    public  $password;
    public  $identify_number;
    public $image;
    public $cover;
    public $date_of_birth;
    public $country_code;
    public $gender;
    public $fcm_token;
    public $status;
    public $avatar;
    public $id_type;
    public $id_image;

    protected string $imageFolder = 'employees'; // fallback folder

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
