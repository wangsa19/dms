<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            'Corporate Matter & License',
            'Environment',
            'Safety',
            'Labor & Human Rights'
        ];

        foreach ($fields as $fieldName) {
            Field::firstOrCreate(['name' => $fieldName]);
        }
    }
}