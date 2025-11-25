<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Modules\Auth\Infrastructure\DataBase\Seeders\SuperAdminSeeder;
use App\Modules\Auth\Infrastructure\Persistence\Models\Admin\Employee;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Employee::create([
            'name' => 'Super Admin',
            'email' => 'admin@medicines.com',
            'password' => '123123123',
            'phone' => '123123123',
        ]);
    }
}
