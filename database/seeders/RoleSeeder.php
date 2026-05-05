<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Department management
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            
            // Clearance management
            'view clearances',
            'create clearances',
            'approve clearances',
            'reject clearances',
            'finalize clearances',
            
            // Reports
            'view reports',
            'export reports',
            
            // System settings
            'manage settings',
            'view logs',
            'manage backups',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $registrar = Role::create(['name' => 'registrar']);
        $departmentOfficer = Role::create(['name' => 'department_officer']);
        $student = Role::create(['name' => 'student']);

        // Assign permissions to roles
        $superAdmin->givePermissionTo(Permission::all());

        $registrar->givePermissionTo([
            'view clearances',
            'finalize clearances',
            'view reports',
            'export reports',
        ]);

        $departmentOfficer->givePermissionTo([
            'view clearances',
            'approve clearances',
            'reject clearances',
        ]);

        $student->givePermissionTo([
            'view clearances',
            'create clearances',
        ]);
    }
}