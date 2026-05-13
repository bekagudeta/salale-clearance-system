<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class DepartmentStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample staff users for each department
        $departments = [
            'school-department' => ['Dr. John Smith', 'Mrs. Sarah Wilson'],
            'book-store' => ['Mr. Ahmed Hassan', 'Ms. Fatima Ali'],
            'library' => ['Mr. James Brown', 'Ms. Rose Johnson', 'Mr. David Lee'],
            'food-service' => ['Mr. Ibrahim Yusuf', 'Ms. Amina Mohamed'],
            'housing' => ['Mr. Peter Okoro', 'Ms. Grace Okafor'],
            'store-keeper' => ['Mr. Kofi Mensah'],
            'campus-security' => ['Mr. Samuel Osei', 'Mr. Marcus Kwesi', 'Mr. Amos Amoah'],
            'registrar-office' => ['Mrs. Abigail Mensah', 'Mr. Benjamin Owusu'],
            'ict-center' => ['Dr. Kwame Asante', 'Mr. Robert Boateng'],
            'finance-office' => ['Mr. Stephen Agyeman', 'Ms. Elizabeth Darko'],
            'clinic' => ['Dr. Nana Agyeman', 'Ms. Comfort Adeyemi'],
        ];

        foreach ($departments as $deptSlug => $staffNames) {
            $department = Department::where('slug', $deptSlug)->first();

            if (!$department) {
                continue;
            }

            foreach ($staffNames as $index => $staffName) {
                // Create user with email based on name
                $email = strtolower(str_replace(' ', '.', $staffName)) . '@salale.edu';
                
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $staffName,
                        'password' => Hash::make('password123'),
                    ]
                );

                // Assign user to department (skip if already exists)
                // First staff member is 'head', others are 'staff'
                $position = $index === 0 ? 'head' : 'staff';
                
                // Only attach if not already attached
                if (!$department->staff()->where('user_id', $user->id)->exists()) {
                    $department->staff()->attach(
                        $user->id,
                        [
                            'position' => $position,
                            'can_approve' => true,
                        ]
                    );
                }
            }
        }
    }
}
