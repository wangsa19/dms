<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deptIT = Department::where('code', 'IT')->first();
        $deptHR = Department::where('code', 'HR')->first();
        $deptFin = Department::where('code', 'FIN')->first();

        // Section untuk IT
        Section::firstOrCreate(['name' => 'Software Development', 'code' => 'DEV', 'department_id' => $deptIT->id]);
        Section::firstOrCreate(['name' => 'Network & Infrastructure', 'code' => 'NET', 'department_id' => $deptIT->id]);

        // Section untuk HR
        Section::firstOrCreate(['name' => 'Recruitment & Training', 'code' => 'REC', 'department_id' => $deptHR->id]);

        // Section untuk Finance
        Section::firstOrCreate(['name' => 'Accounting', 'code' => 'ACC', 'department_id' => $deptFin->id]);
    }
}
