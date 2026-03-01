<?php

namespace Database\Seeders;

use App\Models\ActionFrequencyUnit;
use Illuminate\Database\Seeder;

class ActionFrequencyUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Days', 'code' => 'D'],
            ['name' => 'Months', 'code' => 'M'],
            ['name' => 'Years', 'code' => 'Y']
        ];

        foreach ($units as $unit) {
            ActionFrequencyUnit::firstOrCreate(['name' => $unit['name']], $unit);
        }
    }
}