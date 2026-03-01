<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = 'web';

        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'Manager', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'Employee', 'guard_name' => $guard]);
    }
}
