<?php

namespace App\Modules\Base\Domain\Holders;

use App\Modules\Base\Domain\Entity\UserEntity;


class UserHolder
{

    public static function getUser(): UserEntity
    {
        // Return the user entity
        $user =  UserEntity::example();
        return $user;
    }
}
