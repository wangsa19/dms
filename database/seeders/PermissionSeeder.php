<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // 1. LIST LENGKAP PERMISSIONS
        // ==========================================
        $permissions = [
            'access dashboard',
            // Transaksi (Akses untuk semua Spv & Admin)
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
            'view document outs',
            'create document outs',
            'edit document outs',
            'delete document outs',
            'view licenses',
            'create licenses',
            'edit licenses',
            'delete licenses',

            // Master Data - User & Access (Hanya Admin)
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            // Master Data - Company Structure (Hanya Admin)
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view positions',
            'create positions',
            'edit positions',
            'delete positions',
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            'view sections',
            'create sections',
            'edit sections',
            'delete sections',

            // Master Data - System Attributes (Hanya Admin)
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view fields',
            'create fields',
            'edit fields',
            'delete fields',
            'view racks',
            'create racks',
            'edit racks',
            'delete racks',
            'view document types',
            'create document types',
            'edit document types',
            'delete document types',
            'view action frequency units',
            'create action frequency units',
            'edit action frequency units',
            'delete action frequency units',
        ];

        // 2. INSERT PERMISSIONS KE DATABASE
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ==========================================
        // 3. DEFINE KELOMPOK PERMISSION
        // ==========================================

        // Permission khusus transaksi yang bisa diakses semua Spv
        $transactionPermissions = [
            'access dashboard',
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
            'view document outs',
            'create document outs',
            'edit document outs',
            'delete document outs',
            'view licenses',
            'create licenses',
            'edit licenses',
            'delete licenses',
        ];

        // ==========================================
        // 4. ASSIGN PERMISSIONS KE MASING-MASING ROLE
        // ==========================================

        // --- SETUP ADMIN (ALL ACCESS) ---
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
        }

        // --- SETUP LEVEL SUPERVISOR (TRANSACTION ONLY) ---
        $supervisorRoles = [
            'Senior Supervisor',
            'Supervisor',
        ];

        foreach ($supervisorRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                // Senior Supervisor dan Supervisor mendapatkan akses penuh ke Document, Document Out, dan License
                $role->syncPermissions($transactionPermissions);
            }
        }

        // --- SETUP JUNIOR SUPERVISOR (VIEW ONLY) ---
        $juniorRole = Role::where('name', 'Junior Supervisor')->first();
        if ($juniorRole) {
            $juniorRole->syncPermissions([
                'access dashboard',
                'view documents',
                'view document outs',
                'view licenses',
            ]);
        }
    }
}
