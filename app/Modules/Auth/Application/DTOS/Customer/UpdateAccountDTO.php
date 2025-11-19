<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class UpdateAccountDTO extends BaseDTOAbstract
{
    public  $name;
    public $is_online;
    public  $first_name;
    public  $last_name;
    public  $username;
    public  $phone_code;
    public  $phone;
    public  $email;
    public  $identify_number;
    public  $password;
    public  $location_id;
    public  $nationality_id;
    public  $commercial_register;
    public $tax_register;
    public $image;
    public $cover;
    public string $imageFolder = 'users';
    public $national_address;
    public $another_address;
    public $user_id;
    public $allow_notification;
    public $foundation_name;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            "national_address",
            "another_address",
            "tax_register",
            "commercial_register",
        ]; 

    }

    public function handleSpecialCases() {
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        $this->user_id = $user->id;
        // $this->imageFolder = 'users/'. DIRECTORY_SEPARATOR . $user->id;
    }

        public  function handleImageFolder(): string
    {
        $folder =  'users' . DIRECTORY_SEPARATOR . $this->user_id . DIRECTORY_SEPARATOR . $this->imageFolder;
        return $folder;
    }
}
