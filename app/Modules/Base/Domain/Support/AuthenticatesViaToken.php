<?php

// App\Services\Contracts\AuthenticatesViaToken.php
namespace App\Modules\Base\Domain\Support;

interface AuthenticatesViaToken
{
    public function checkAuth(string $token): array;
}
