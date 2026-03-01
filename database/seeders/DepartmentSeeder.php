<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::firstOrCreate(['name' => 'Information Technology', 'code' => 'IT']);
        Department::firstOrCreate(['name' => 'Human Resource', 'code' => 'HR']);
        Department::firstOrCreate(['name' => 'Finance', 'code' => 'FIN']);
    }
}
