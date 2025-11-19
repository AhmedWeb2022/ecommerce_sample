<?php

namespace App\Modules\Auth\Application\DTOS\Customer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class UserFilterDTO extends BaseDTOAbstract
{
    public  $name;
    public  $email;
    public  $phone;
    public  $username;
    public $stage_id;
    public $user_ids;
    public $word;
    public $is_approved;
    public $is_verified;
    public $is_blocked;
    public $has_commercial_register;
    public $has_tax_register;
    public $has_orders;
    public $has_current_orders;
    public $from_date;
    public $to_date;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

        public function excludedAttributes(): array
    {
        return [
            'word'
        ];
    }
}
