<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions first
        $permissions = [
            'manage users',
            'manage departments', 
            'view reports',
            'finalize clearances'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Create roles
        $roles = ['super_admin', 'student', 'department_officer', 'registrar'];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
        
        // Assign permissions to roles
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->givePermissionTo([
            Permission::findByName('manage users'),
            Permission::findByName('manage departments'),
            Permission::findByName('view reports'),
            Permission::findByName('finalize clearances')
        ]);
        
        $registrar = Role::findByName('registrar');
        $registrar->givePermissionTo([
            Permission::findByName('view reports'),
            Permission::findByName('finalize clearances')
        ]);
    }
}