<?php

namespace App\Modules\Auth\Infrastructure\DataBase\Seeders;

use App\Modules\Auth\Infrastructure\Persistence\Models\Admin\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
