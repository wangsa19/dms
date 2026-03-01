<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Spatie\Permission\Models\Role;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil Role yang sudah diseed dari RoleSeeder
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $employeeRole = Role::where('name', 'Employee')->first();

        // ==============================
        // 1. DATA ADMIN
        // ==============================
        $employee1 = Employee::firstOrCreate(
            ['nik' => 'EMP001'],
            [
                'name' => 'Admin Utama',
                'gender' => 'Laki-laki',
                'phone' => '081234567801',
                'position_id' => Position::where('code', 'ADM')->first()->id,
                'section_id' => Section::where('code', 'DEV')->first()->id,
                'department_id' => Department::where('code', 'IT')->first()->id,
            ]
        );

        $user1 = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'employee_id' => $employee1->id,
            ]
        );
        $user1->assignRole($adminRole);

        // ==============================
        // 2. DATA MANAGER
        // ==============================
        $employee2 = Employee::firstOrCreate(
            ['nik' => 'EMP002'],
            [
                'name' => 'Manager HRD',
                'gender' => 'Perempuan',
                'phone' => '081234567802',
                'position_id' => Position::where('code', 'MGR')->first()->id,
                'section_id' => Section::where('code', 'REC')->first()->id,
                'department_id' => Department::where('code', 'HR')->first()->id,
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager HRD',
                'password' => Hash::make('password'),
                'employee_id' => $employee2->id,
            ]
        );
        $user2->assignRole($managerRole);

        // ==============================
        // 3. DATA STAFF / EMPLOYEE
        // ==============================
        $employee3 = Employee::firstOrCreate(
            ['nik' => 'EMP003'],
            [
                'name' => 'Staff IT Net',
                'gender' => 'Laki-laki',
                'phone' => '081234567803',
                'position_id' => Position::where('code', 'STF')->first()->id,
                'section_id' => Section::where('code', 'NET')->first()->id,
                'department_id' => Department::where('code', 'IT')->first()->id,
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff IT Net',
                'password' => Hash::make('password'),
                'employee_id' => $employee3->id,
            ]
        );
        $user3->assignRole($employeeRole);
    }
}
