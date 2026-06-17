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
        // 2. 3 USER UNTUK MASING-MASING 8 DEPARTEMEN (SSPV, SPV, JSPV)
        // ==========================================
        
        $departments = [
            ['dept' => 'PGA', 'sec' => 'HR'],
            ['dept' => 'PPIC', 'sec' => 'PPC'],
            ['dept' => 'QA', 'sec' => 'QA ENG'],
            ['dept' => 'ENG', 'sec' => 'PE'],
            ['dept' => 'MTC', 'sec' => 'MTC'],
            ['dept' => 'FATP', 'sec' => 'FA'],
            ['dept' => 'PRODUKSI', 'sec' => 'PROD'],
            ['dept' => 'NYS', 'sec' => 'NYS'],
        ];

        foreach ($departments as $d) {
            $deptCode = $d['dept'];
            $secCode = $d['sec'];
            $deptLower = strtolower($deptCode);

            // USER SSPV
            $this->createUser($deptCode, $secCode, 'SSPV', "sspv_{$deptLower}@example.com", "Senior SPV {$deptCode}", $seniorSpvRole);
            
            // USER SPV
            $this->createUser($deptCode, $secCode, 'SPV', "spv_{$deptLower}@example.com", "Supervisor {$deptCode}", $spvRole);
            
            // USER JSPV
            $this->createUser($deptCode, $secCode, 'JSPV', "jspv_{$deptLower}@example.com", "Junior SPV {$deptCode}", $juniorSpvRole);
        }
    }

    private function createUser($deptCode, $sectionCode, $positionCode, $email, $name, $role)
    {
        $dept = Department::where('code', $deptCode)->first();
        $section = Section::where('code', $sectionCode)->first();
        $position = Position::where('code', $positionCode)->first();

        // Gunakan kombinasi position dan department untuk NIK agar unik
        $employee = Employee::firstOrCreate(
            ['nik' => 'EMP_' . $positionCode . '_' . $deptCode],
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
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'employee_id' => $employee->id,
            ]
        );

        if ($role) {
            $user->assignRole($role);
        }
    }
}
