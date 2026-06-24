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
        // Academic departments act as the first-stage head/coordinator gate for
        // their own students. Extend this list with your real departments.
        $academic = [
            ['name' => 'Software Engineering', 'slug' => 'software-engineering'],
            ['name' => 'Computer Science', 'slug' => 'computer-science'],
            ['name' => 'Accounting', 'slug' => 'accounting'],
            ['name' => 'Civil Engineering', 'slug' => 'civil-engineering'],
            ['name' => 'Biology', 'slug' => 'biology'],
            ['name' => 'Plant Science', 'slug' => 'plant-science'],
        ];

        // Service departments open only after the academic head approves.
        $service = [
            ['name' => 'Book Store', 'slug' => 'book-store'],
            ['name' => 'Library', 'slug' => 'library'],
            ['name' => 'Food Service', 'slug' => 'food-service'],
            ['name' => 'Housing', 'slug' => 'housing'],
            ['name' => 'Store Keeper', 'slug' => 'store-keeper'],
            ['name' => 'Campus Security / Police', 'slug' => 'campus-security'],
            ['name' => 'Registrar Office', 'slug' => 'registrar-office'],
            ['name' => 'ICT Center', 'slug' => 'ict-center'],
            ['name' => 'Finance Office', 'slug' => 'finance-office'],
            ['name' => 'Clinic', 'slug' => 'clinic'],
        ];

        $priority = 1;
        foreach ($academic as $department) {
            Department::updateOrCreate(
                ['slug' => $department['slug']],
                [
                    'name' => $department['name'],
                    'category' => 'academic',
                    'priority_order' => $priority++,
                    'is_active' => true,
                ]
            );
        }

        // Service departments ordered after all academic ones.
        $priority = 100;
        foreach ($service as $department) {
            Department::updateOrCreate(
                ['slug' => $department['slug']],
                [
                    'name' => $department['name'],
                    'category' => 'service',
                    'priority_order' => $priority++,
                    'is_active' => true,
                ]
            );
        }
    }
}