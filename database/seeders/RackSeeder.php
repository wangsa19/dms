<?php

namespace Database\Seeders;

use App\Models\Rack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $racks = [
            [
                'code'   => 'RAK-A',
                'name'   => 'Rak A',
                'column' => '1',
                'row'    => '1',
            ],
            [
                'code'   => 'RAK-B',
                'name'   => 'Rak B',
                'column' => '1',
                'row'    => '2',
            ],
            [
                'code'   => 'RAK-C',
                'name'   => 'Rak C',
                'column' => '2',
                'row'    => '1',
            ],
            [
                'code'   => 'RAK-D',
                'name'   => 'Rak D',
                'column' => '2',
                'row'    => '2',
            ],
        ];

        foreach ($racks as $rack) {
            Rack::create($rack);
        }
    }
}
