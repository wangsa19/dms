<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['name' => 'Junior Supervisor', 'code' => 'JSPV'],
            ['name' => 'Supervisor', 'code' => 'SPV'],
            ['name' => 'Senior Supervisor', 'code' => 'SSPV'],
        ];

        foreach ($positions as $pos) {
            Position::firstOrCreate(['name' => $pos['name'], 'code' => $pos['code']]);
        }
    }
}
