<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $department = isset($row['department']) ? Department::where('name', $row['department'])->first() : null;
        $section    = isset($row['section'])    ? Section::where('name', $row['section'])->first()       : null;
        $position   = isset($row['position'])   ? Position::where('name', $row['position'])->first()     : null;

        return new Employee([
            'nik'           => $row['nik'],
            'name'          => $row['name'],
            'gender'        => $row['gender'],
            'phone'         => $row['phone'] ?? null,
            'department_id' => $department ? $department->id : null,
            'section_id'    => $section ? $section->id : null,
            'position_id'   => $position ? $position->id : null,
        ]);
    }

    public function batchSize(): int
    {
        return 1000; // Insert per 1000 data
    }

    public function chunkSize(): int
    {
        return 1000; // Baca memory per 1000 baris
    }
}
