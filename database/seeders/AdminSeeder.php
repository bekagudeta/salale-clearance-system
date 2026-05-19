<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminPassword = $this->seedPassword('SEED_SUPER_ADMIN_PASSWORD');
        $registrarPassword = $this->seedPassword('SEED_REGISTRAR_PASSWORD');
        $officerPassword = $this->seedPassword('SEED_DEPARTMENT_OFFICER_PASSWORD');

        // Create or Update Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@salale.edu.et'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make($superAdminPassword),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create or Update Registrar
        $registrar = User::firstOrCreate(
            ['email' => 'registrar@salale.edu.et'],
            [
                'name' => 'Registrar Office',
                'password' => Hash::make($registrarPassword),
            ]
        );
        $registrar->assignRole('registrar');

        // Create Department Officers
        $officers = [
            ['name' => 'School Department Officer', 'email' => 'school@salale.edu.et'],
            ['name' => 'Book Store Officer', 'email' => 'bookstore@salale.edu.et'],
            ['name' => 'Library Officer', 'email' => 'library@salale.edu.et'],
            ['name' => 'Food Service Officer', 'email' => 'food@salale.edu.et'],
            ['name' => 'Housing Officer', 'email' => 'housing@salale.edu.et'],
            ['name' => 'Store Keeper Officer', 'email' => 'store@salale.edu.et'],
            ['name' => 'Security Officer', 'email' => 'security@salale.edu.et'],
            ['name' => 'ICT Center Officer', 'email' => 'ict@salale.edu.et'],
            ['name' => 'Finance Officer', 'email' => 'finance@salale.edu.et'],
            ['name' => 'Clinic Officer', 'email' => 'clinic@salale.edu.et'],
        ];

        foreach ($officers as $officer) {
            $user = User::firstOrCreate(
                ['email' => $officer['email']],
                [
                    'name' => $officer['name'],
                    'password' => Hash::make($officerPassword),
                ]
            );
            $user->assignRole('department_officer');
        }

        // Assign officers to departments
        $this->assignOfficersToDepartments();
    }

    /**
     * Assign officers to their respective departments
     */
    private function assignOfficersToDepartments(): void
    {
        $departmentMappings = [
            'school-department' => 'school@salale.edu.et',
            'book-store' => 'bookstore@salale.edu.et',
            'library' => 'library@salale.edu.et',
            'food-service' => 'food@salale.edu.et',
            'housing' => 'housing@salale.edu.et',
            'store-keeper' => 'store@salale.edu.et',
            'campus-security' => 'security@salale.edu.et',
            'ict-center' => 'ict@salale.edu.et',
            'finance-office' => 'finance@salale.edu.et',
            'clinic' => 'clinic@salale.edu.et',
        ];

        foreach ($departmentMappings as $slug => $email) {
            $department = \App\Models\Department::where('slug', $slug)->first();
            $user = User::where('email', $email)->first();
            
            if ($department && $user) {
                $department->update(['officer_user_id' => $user->id]);
            }
        }
    }

    private function seedPassword(string $envKey): string
    {
        $password = env($envKey);

        if (is_string($password) && trim($password) !== '') {
            return $password;
        }

        if (app()->environment('production')) {
            throw new RuntimeException("Missing required {$envKey} environment variable for production seeding.");
        }

        return Str::random(32);
    }
}
