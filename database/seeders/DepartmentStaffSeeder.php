<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class DepartmentStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffPassword = $this->seedPassword('SEED_DEPARTMENT_STAFF_PASSWORD');

        // Create sample staff users for each department. The first name in each
        // list becomes the department 'head' (the academic coordinator gate).
        $departments = [
            // Academic department heads / coordinators
            'software-engineering' => ['Dr. Selam Bekele'],
            'computer-science' => ['Dr. Henok Girma'],
            'accounting' => ['Mr. Yonas Tesfaye'],
            'civil-engineering' => ['Dr. Meron Alemu'],
            'biology' => ['Dr. Hanna Mekonnen'],
            'plant-science' => ['Dr. Dawit Fikru'],
            // Service departments
            'book-store' => ['Mr. Ahmed Hassan', 'Ms. Fatima Ali'],
            'library' => ['Mr. James Brown', 'Ms. Rose Johnson', 'Mr. David Lee'],
            'food-service' => ['Mr. Ibrahim Yusuf', 'Ms. Amina Mohamed'],
            'housing' => ['Mr. Peter Okoro', 'Ms. Grace Okafor'],
            'store-keeper' => ['Mr. Kofi Mensah'],
            'campus-security' => ['Mr. Samuel Osei', 'Mr. Marcus Kwesi', 'Mr. Amos Amoah'],
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
                $email = strtolower(str_replace(' ', '.', $staffName)) . '@salale.edu.et';
                
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $staffName,
                        'password' => Hash::make($staffPassword),
                    ]
                );

                // Department staff (academic heads included) need this role to
                // reach the approval dashboard (see IsOfficer middleware).
                $user->assignRole('department_officer');

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

    private function seedPassword(string $envKey): string
    {
        $password = env($envKey);

        if (is_string($password) && trim($password) !== '') {
            return $password;
        }

        throw new RuntimeException("Missing required {$envKey} environment variable for seeding. Set it in your local .env before running db seed.");
    }
}
