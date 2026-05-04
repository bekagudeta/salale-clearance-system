<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'School / Department', 'slug' => 'school-department', 'priority_order' => 1],
            ['name' => 'Book Store', 'slug' => 'book-store', 'priority_order' => 2],
            ['name' => 'Library', 'slug' => 'library', 'priority_order' => 3],
            ['name' => 'Food Service', 'slug' => 'food-service', 'priority_order' => 4],
            ['name' => 'Housing', 'slug' => 'housing', 'priority_order' => 5],
            ['name' => 'Store Keeper', 'slug' => 'store-keeper', 'priority_order' => 6],
            ['name' => 'Campus Security', 'slug' => 'campus-security', 'priority_order' => 7],
            ['name' => 'Registrar Office', 'slug' => 'registrar-office', 'priority_order' => 8],
            ['name' => 'ICT Center', 'slug' => 'ict-center', 'priority_order' => 9],
            ['name' => 'Finance Office', 'slug' => 'finance-office', 'priority_order' => 10],
        ];
        
        foreach ($departments as $department) {
            Department::firstOrCreate(['slug' => $department['slug']], $department);
        }
    }
}