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
        $dept = Department::first();

        Section::firstOrCreate([
            'name' => 'Software Development',
            'code' => 'DEV',
            'department_id' => $dept->id
        ]);

        Section::firstOrCreate([
            'name' => 'Network & Infrastructure',
            'code' => 'NET',
            'department_id' => $dept->id
        ]);
    }
}
