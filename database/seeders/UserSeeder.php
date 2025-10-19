<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
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
        $department = Department::first();
        $section = Section::first();
        $position = Position::first();

        $role = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);

        $employee = Employee::firstOrCreate(
            ['nik' => 'EMP001'],
            [
                'name' => 'System Administrator',
                'gender' => 'L',
                'phone' => '08123456789',
                'position_id' => $position->id,
                'section_id' => $section->id,
                'department_id' => $department->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'employee_id' => $employee->id,
                'role_id' => $role->id,
            ]
        );
    }
}
