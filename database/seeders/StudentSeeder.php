<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentPassword = $this->seedPassword('SEED_STUDENT_PASSWORD');

        // Sample students data
        $students = [
            [
                'name' => 'John Doe',
                'email' => 'student1@salale.edu.et',
                'student_id' => 'SAL/2024/001',
                'faculty' => 'Faculty of Computing',
                'department' => 'Computer Science',
                'year' => 4,
                'semester' => 'First',
                'phone' => '+251911111111',
                'gender' => 'male',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'student2@salale.edu.et',
                'student_id' => 'SAL/2024/002',
                'faculty' => 'Faculty of Business',
                'department' => 'Accounting',
                'year' => 3,
                'semester' => 'Second',
                'phone' => '+251922222222',
                'gender' => 'female',
            ],
            [
                'name' => 'Abebe Kebede',
                'email' => 'student3@salale.edu.et',
                'student_id' => 'SAL/2024/003',
                'faculty' => 'Faculty of Engineering',
                'department' => 'Civil Engineering',
                'year' => 5,
                'semester' => 'First',
                'phone' => '+251933333333',
                'gender' => 'male',
            ],
            [
                'name' => 'Tigist Haile',
                'email' => 'student4@salale.edu.et',
                'student_id' => 'SAL/2024/004',
                'faculty' => 'Faculty of Natural Sciences',
                'department' => 'Biology',
                'year' => 2,
                'semester' => 'Second',
                'phone' => '+251944444444',
                'gender' => 'female',
            ],
            [
                'name' => 'Bekele Tadese',
                'email' => 'student5@salale.edu.et',
                'student_id' => 'SAL/2024/005',
                'faculty' => 'Faculty of Agriculture',
                'department' => 'Plant Science',
                'year' => 4,
                'semester' => 'Summer',
                'phone' => '+251955555555',
                'gender' => 'male',
            ],
        ];

        foreach ($students as $studentData) {
            $user = User::updateOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make($studentPassword),
                ]
            );
            
            $user->assignRole('student');
            
            Student::updateOrCreate(
                ['student_id' => $studentData['student_id']],
                [
                    'user_id' => $user->id,
                    'full_name' => $studentData['name'],
                    'faculty' => $studentData['faculty'],
                    'department' => $studentData['department'],
                    'year' => $studentData['year'],
                    'semester' => $studentData['semester'],
                    'phone' => $studentData['phone'],
                    'gender' => $studentData['gender'],
                ]
            );
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
