<?php

namespace App\Modules\Auth\Domain\Repositories\Api\Customer;
use App\Modules\Auth\Domain\Entities\CustomerEntity;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\Customer;


interface CustomerRepositoryInterface
{
    public function findByUsernameOrPhone(string $value): ?Customer;
    public function create(array $data): Customer;
}

