<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class BusinessRegisterDetailsDTO extends BaseDTOAbstract
{
    public $id;
    public $user_id;
    public $file;
    public $number;
    public $is_active;
    public $type;

    protected string $imageFolder = 'business_registration_details';

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
            'type',
        ];
    }
    
    public function handleSpecialCases()
    {
        if (AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value) !== null && $this->user_id == null) {
            $this->user_id = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value)->id;
        }elseif ($this->user_id !== null) {
            $this->user_id = $this->user_id;
        }

    }
    public  function handleImageFolder(): string
    {
        $folder =  isset($this->user_id) && $this->user_id !== null ? 'users' . DIRECTORY_SEPARATOR . $this->user_id . DIRECTORY_SEPARATOR . $this->imageFolder : $this->imageFolder;
        return $folder;
    }
}
