<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::firstOrCreate(['name' => 'Administrator', 'code' => 'ADM']);
        Position::firstOrCreate(['name' => 'Manager', 'code' => 'MGR']);
        Position::firstOrCreate(['name' => 'Staff', 'code' => 'STF']);
    }
}
