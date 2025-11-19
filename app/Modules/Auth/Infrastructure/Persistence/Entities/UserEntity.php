<?php

namespace App\Modules\Auth\Infrastructure\Persistence\Entities;

use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\UserAddress\UserAddress;

class UserEntity
{
    public function __construct(public User $user) {}

    public function internationalAddress(): ?UserAddress
    {
        $international_address = $this->user->addresses->where('is_master', true)->first();
        return $international_address;
    }
    public function isCompleteData(): bool
    {
        if ($this->user->name && /* $this->user->email &&  */$this->user->phone && $this->internationalAddress() !== null) {
            return true;
        } else {
            return false;
        }
    }
}
