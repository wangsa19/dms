<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::with(['department', 'section', 'position'])->get();
    }

    /**
    * Menentukan Header Kolom di Excel
    */
    public function headings(): array
    {
        return [
            'NIK',
            'Name',
            'Gender',
            'Phone',
            'Department',
            'Section',
            'Position',
            'Created At',
        ];
    }

    /**
    * Mapping data per baris
    */
    public function map($employee): array
    {
        return [
            $employee->nik,
            $employee->name,
            $employee->gender,
            $employee->phone,
            $employee->department->name ?? '-', 
            $employee->section->name ?? '-',    
            $employee->position->name ?? '-',   
            $employee->created_at->format('d-m-Y'),
        ];
    }

    /**
    * Styling Header 
    */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
