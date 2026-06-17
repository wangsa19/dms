<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Spatie\Permission\Models\Role;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $seniorSpvRole = Role::where('name', 'Senior Supervisor')->first();
        $spvRole = Role::where('name', 'Supervisor')->first();
        $juniorSpvRole = Role::where('name', 'Junior Supervisor')->first();

        // ==========================================
        // 1. ADMIN UTAMA (Bebas Departemen/Seksi)
        // ==========================================
        $adminEmployee = Employee::firstOrCreate(
            ['nik' => 'EMP_ADMIN'],
            [
                'name' => 'Admin Utama',
                'gender' => 'Laki-laki',
                'phone' => '081200000000',
                'position_id' => null,
                'section_id' => null,
                'department_id' => null,
            ]
        );

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'employee_id' => $adminEmployee->id,
            ]
        );
        if ($adminRole) $adminUser->assignRole($adminRole);


        // ==========================================
        // 2. 8 USER UNTUK 8 DEPARTEMEN
        // ==========================================
        
        // USER PGA
        $this->createUser('PGA', 'HR', 'SSPV', 'sspv_pga@example.com', 'Senior SPV PGA', $seniorSpvRole);

        // USER PPIC
        $this->createUser('PPIC', 'PPC', 'SSPV', 'sspv_ppic@example.com', 'Senior SPV PPIC', $seniorSpvRole);

        // USER QA
        $this->createUser('QA', 'QA ENG', 'SPV', 'spv_qa@example.com', 'Supervisor QA', $spvRole);

        // USER ENG
        $this->createUser('ENG', 'PE', 'JSPV', 'jspv_eng@example.com', 'Junior SPV ENG', $juniorSpvRole);

        // USER MTC
        $this->createUser('MTC', 'MTC', 'SPV', 'spv_mtc@example.com', 'Supervisor MTC', $spvRole);

        // USER FATP
        $this->createUser('FATP', 'FA', 'SSPV', 'sspv_fatp@example.com', 'Senior SPV FATP', $seniorSpvRole);

        // USER PRODUKSI
        $this->createUser('PRODUKSI', 'PROD', 'SPV', 'spv_produksi@example.com', 'Supervisor Produksi', $spvRole);

        // USER NYS
        $this->createUser('NYS', 'NYS', 'JSPV', 'jspv_nys@example.com', 'Junior SPV NYS', $juniorSpvRole);
    }

    private function createUser($deptCode, $sectionCode, $positionCode, $email, $name, $role)
    {
        $dept = Department::where('code', $deptCode)->first();
        $section = Section::where('code', $sectionCode)->first();
        $position = Position::where('code', $positionCode)->first();

        $employee = Employee::firstOrCreate(
            ['nik' => 'EMP_' . $deptCode],
            [
                'name' => $name,
                'gender' => 'Laki-laki',
                'phone' => '0812' . rand(10000000, 99999999),
                'position_id' => $position ? $position->id : 1,
                'section_id' => $section ? $section->id : 1,
                'department_id' => $dept ? $dept->id : 1,
            ]
        );

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'employee_id' => $employee->id,
            ]
        );

        if ($role) {
            $user->assignRole($role);
        }
    }
}
