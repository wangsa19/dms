<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Section;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip jika NIK atau Name kosong (antisipasi baris kosong di excel)
            if (empty($row['nik']) || empty($row['name'])) {
                continue;
            }

            DB::beginTransaction();
            try {
                // 1. Cari ID relasi berdasarkan nama dari Excel
                $department = isset($row['department']) ? Department::where('name', $row['department'])->first() : null;
                $section    = isset($row['section'])    ? Section::where('name', $row['section'])->first()       : null;
                $position   = isset($row['position'])   ? Position::where('name', $row['position'])->first()     : null;

                // 2. Simpan atau Update Data Employee
                $employee = Employee::updateOrCreate(
                    ['nik' => $row['nik']], // Patokannya NIK, kalau udah ada di-update
                    [
                        'name'          => $row['name'],
                        'gender'        => $row['gender'],
                        'phone'         => $row['phone'] ?? null,
                        'department_id' => $department ? $department->id : null,
                        'section_id'    => $section ? $section->id : null,
                        'position_id'   => $position ? $position->id : null,
                    ]
                );

                // 3. Cek apakah ada kolom email untuk buatin Akun User
                if (!empty($row['email'])) {

                    $userData = [
                        'name'        => $row['name'],
                        'employee_id' => $employee->id,
                    ];

                    // Cek apakah user ini baru atau udah ada
                    $userExists = User::where('email', $row['email'])->first();

                    if (!$userExists) {
                        // Kalau user baru, set password dari excel. Kalau kosong, set default '12345678'
                        $password = !empty($row['password']) ? $row['password'] : '12345678';
                        $userData['password'] = Hash::make($password);
                    }

                    // Buat atau Update User
                    $user = User::updateOrCreate(
                        ['email' => $row['email']],
                        $userData
                    );

                    // 4. Assign Role Spatie (Jika diisi di Excel)
                    if (!empty($row['role'])) {
                        // Pastikan role-nya beneran ada di database biar nggak error
                        if (Role::where('name', $row['role'])->exists()) {
                            $user->syncRoles([$row['role']]);
                        }
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // Tulis ke log Laravel kalau ada baris yang gagal biar gampang di-trace IT
                Log::error("Gagal import Excel NIK " . $row['nik'] . " - Error: " . $e->getMessage());
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000; // Baca memory per 1000 baris, aman untuk data besar
    }
}
