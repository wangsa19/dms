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
            // Transaksi
            'view documents', 'create documents', 'edit documents', 'delete documents',
            'view document outs', 'create document outs', 'edit document outs', 'delete document outs',
            'view licenses', 'create licenses', 'edit licenses', 'delete licenses',
            
            // Master Data - User & Access
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'view permissions', 'create permissions', 'edit permissions', 'delete permissions',
            
            // Master Data - Company Structure
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'view positions', 'create positions', 'edit positions', 'delete positions',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view sections', 'create sections', 'edit sections', 'delete sections',
            
            // Master Data - System Attributes
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view fields', 'create fields', 'edit fields', 'delete fields', 'view racks', 'create racks', 'edit racks', 'delete racks', 'view document types', 'create document types', 'edit document types', 'delete document types',
            'view action frequency units', 'create action frequency units', 'edit action frequency units', 'delete action frequency units',
        ];

        // ==========================================
        // 2. INSERT PERMISSIONS KE DATABASE
        // ==========================================
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ==========================================
        // 3. AMBIL ROLE DARI DATABASE
        // ==========================================
        // Pastikan RoleSeeder sudah dijalankan sebelum PermissionSeeder
        $adminRole = Role::where('name', 'Admin')->first();
        $seniorSpvRole = Role::where('name', 'Senior Supervisor')->first();
        $spvRole = Role::where('name', 'Supervisor')->first();

        // ==========================================
        // 4. ASSIGN PERMISSIONS KE MASING-MASING ROLE
        // ==========================================
        
        // --- SETUP ADMIN (ALL ACCESS) ---
        if ($adminRole) {
            // Karena $permissions berisi seluruh array di atas, 
            // Admin otomatis dapat semua hak akses!
            $adminRole->syncPermissions($permissions); 
        }

        // --- SETUP SENIOR SUPERVISOR ---
        if ($seniorSpvRole) {
            // Senior SPV hanya butuh akses View untuk memantau data
            $seniorSpvRole->syncPermissions([
                'access dashboard',
                'view documents',
                'view document outs',
                'view licenses'
            ]);
        }

        // --- SETUP SUPERVISOR ---
        if ($spvRole) {
            // Supervisor bisa melakukan operasi CRUD pada transaksi, 
            // tapi tidak punya akses ke Master Data (Users, Departments, dll)
            $spvRole->syncPermissions([
                'access dashboard',
                'view documents', 'create documents', 'edit documents', 'delete documents',
                'view document outs', 'create document outs', 'edit document outs', 'delete document outs',
                'view licenses', 'create licenses', 'edit licenses', 'delete licenses',
            ]);
        }
    }
}
