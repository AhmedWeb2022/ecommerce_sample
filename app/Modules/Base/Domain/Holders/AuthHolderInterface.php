<?php

namespace App\Modules\Base\Domain\Holders;

use Illuminate\Container\Attributes\Auth;
use App\Modules\Base\Domain\Entity\AuthEntityAbstract;

interface AuthHolderInterface
{
    public function getAuthEntity(): AuthEntityAbstract;
}
