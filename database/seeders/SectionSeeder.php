<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'PGA' => ['HR', 'IR', 'GA', 'UTILITY', 'QSA', 'PURCHASE'],
            'PPIC' => ['PPC', 'MPC', 'EXIM', 'WHS'],
            'QA' => ['QA ENG', 'QC MSA', 'QC CHECK W/H', 'QC REC', 'QC LAB', 'QC STD'],
            'ENG' => ['PE', 'PD'],
            'MTC' => ['MTC', 'DESIGN'],
            'FATP' => ['FA'],
            'PRODUKSI' => ['PROD', 'TRN', 'PP'],
            'NYS' => ['NYS']
        ];

        foreach ($mapping as $deptCode => $sections) {
            $dept = Department::where('code', $deptCode)->first();
            if ($dept) {
                foreach ($sections as $sec) {
                    Section::firstOrCreate([
                        'name' => $sec,
                        'code' => $sec,
                        'department_id' => $dept->id
                    ]);
                }
            }
        }
    }
}
