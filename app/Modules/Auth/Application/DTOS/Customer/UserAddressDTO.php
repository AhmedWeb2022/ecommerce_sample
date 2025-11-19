<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class UserAddressDTO extends BaseDTOAbstract
{
    public $id;
    public $user_id;
    public $address_id;
    public $is_master = false;
    public $address;
    public $lat;
    public $lng;
    public $file_address;

    public $another_address;
    protected string $imageFolder = 'users';

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function uniqueAttributes(): array
    {
        return [
            'user_id',
            'is_master',
            'id'
        ];
    }
    /* public function excludedAttributes(): array
    {
        return [
            'user_id',
            'is_master',
        ];
    } */
    public  function handleImageFolder(): string
    {
        return 'users' . DIRECTORY_SEPARATOR . $this->user_id . DIRECTORY_SEPARATOR . 'addresses';
    }

    public function handleSpecialCases()
    {
        if (!isset($this->id) && isset($this->address_id)) {
            $this->id = $this->address_id;
        }
        if (AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value) !== null) {
            $this->user_id = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value)->id;
        }
    }
}
