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
        $seniorSpvRole = Role::where('name', 'Senior Supervisor')->first();
        $spvRole = Role::where('name', 'Supervisor')->first();
        $juniorSpvRole = Role::where('name', 'Junior Supervisor')->first(); // Ambil role baru

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
        // 2. DATA SENIOR SUPERVISOR
        // ==============================
        $employee2 = Employee::firstOrCreate(
            ['nik' => 'EMP002'],
            [
                'name' => 'Senior SPV HRD',
                'gender' => 'Perempuan',
                'phone' => '081234567802',
                'position_id' => Position::where('code', 'MGR')->first()->id,
                'section_id' => Section::where('code', 'REC')->first()->id,
                'department_id' => Department::where('code', 'HR')->first()->id,
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'seniorspv@example.com'],
            [
                'name' => 'Senior SPV HRD',
                'password' => Hash::make('password'),
                'employee_id' => $employee2->id,
            ]
        );
        $user2->assignRole($seniorSpvRole);

        // ==============================
        // 3. DATA SUPERVISOR
        // ==============================
        $employee3 = Employee::firstOrCreate(
            ['nik' => 'EMP003'],
            [
                'name' => 'Supervisor IT',
                'gender' => 'Laki-laki',
                'phone' => '081234567803',
                'position_id' => Position::where('code', 'STF')->first()->id,
                'section_id' => Section::where('code', 'NET')->first()->id,
                'department_id' => Department::where('code', 'IT')->first()->id,
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'spv@example.com'],
            [
                'name' => 'Supervisor IT',
                'password' => Hash::make('password'),
                'employee_id' => $employee3->id,
            ]
        );
        $user3->assignRole($spvRole);

        // ==============================
        // 4. DATA JUNIOR SUPERVISOR (TAMBAHAN)
        // ==============================
        $employee4 = Employee::firstOrCreate(
            ['nik' => 'EMP004'],
            [
                'name' => 'Junior SPV Prod',
                'gender' => 'Laki-laki',
                'phone' => '081234567804',
                // Pastikan code di bawah ini sesuai dengan data master lo
                'position_id' => Position::where('code', 'STF')->first()->id,
                'section_id' => Section::where('code', 'NET')->first()->id,
                'department_id' => Department::where('code', 'IT')->first()->id,
            ]
        );

        $user4 = User::firstOrCreate(
            ['email' => 'juniorspv@example.com'],
            [
                'name' => 'Junior SPV Prod',
                'password' => Hash::make('password'),
                'employee_id' => $employee4->id,
            ]
        );

        if ($juniorSpvRole) {
            $user4->assignRole($juniorSpvRole);
        }
    }
}
