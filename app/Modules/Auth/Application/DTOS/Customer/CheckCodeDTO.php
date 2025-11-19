<?php
namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;


class CheckCodeDTO extends BaseDTOAbstract
{
    public  $email;
    public  $phone;
    public  $phone_code;
    public  $code;
    public $user_id;
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
