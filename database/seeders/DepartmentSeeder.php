<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'PGA', 'PPIC', 'QA', 'ENG', 'MTC', 'FATP', 'PRODUKSI', 'NYS'
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept, 'code' => $dept]);
        }
    }
}
