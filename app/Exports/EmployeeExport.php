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
        // Tambahkan relasi 'user.roles' agar query-nya efisien dan gak N+1 problem
        return Employee::with(['department', 'section', 'position', 'user.roles'])->get();
    }

    /**
     * Menentukan Header Kolom di Excel
     */
    public function headings(): array
    {
        return [
            'nik',           // Disamakan lowercase biar kalau HRD mau re-import
            'name',          // file hasil download ini, format headernya udah pas
            'gender',
            'phone',
            'department',
            'section',
            'position',
            'email',         // Tambahan: Kolom Email Akun
            'role',          // Tambahan: Kolom Hak Akses
            'created_at',
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
            $employee->department->name ?? '',
            $employee->section->name ?? '',
            $employee->position->name ?? '',
            // Cek apakah punya user, kalau ada tampilkan email, kalau gak kosongkan  
            $employee->user->email ?? '',
            // Cek apakah punya user dan role, ambil nama role pertamanya
            $employee->user ? ($employee->user->roles->first()->name ?? '') : '',
            $employee->created_at->format('Y-m-d'), // Format standar database
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
