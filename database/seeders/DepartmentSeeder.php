<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'School / Department',
                'slug' => 'school-department',
                'priority_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Book Store',
                'slug' => 'book-store',
                'priority_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Library',
                'slug' => 'library',
                'priority_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Food Service',
                'slug' => 'food-service',
                'priority_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Housing',
                'slug' => 'housing',
                'priority_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Store Keeper',
                'slug' => 'store-keeper',
                'priority_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Campus Security / Police',
                'slug' => 'campus-security',
                'priority_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Registrar Office',
                'slug' => 'registrar-office',
                'priority_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'ICT Center',
                'slug' => 'ict-center',
                'priority_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Finance Office',
                'slug' => 'finance-office',
                'priority_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Clinic',
                'slug' => 'clinic',
                'priority_order' => 11,
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['slug' => $department['slug']], $department);
        }
    }
}