<?php

// App\Support\AuthGuardServiceResolver.php
namespace App\Modules\Base\Domain\Support;


use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Support\AuthenticatesViaToken;
use App\Modules\Auth\Infrastructure\Persistence\ApiService\Auth\UserApiService;
use App\Modules\Auth\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;

class AuthGuardServiceResolver
{
    public static function resolve(string $guard): ?AuthenticatesViaToken
    {
        return match ($guard) {
            AuthGurdTypeEnum::USER->value     => new UserApiService(),
            AuthGurdTypeEnum::EMPLOYEE->value => new EmployeeApiService(),
            // Add new guards/services here easily
            default => null,
        };
    }
}
