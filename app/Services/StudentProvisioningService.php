<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentProvisioningService
{
    /**
     * Create a user account and linked student profile.
     *
     * @param  array{name: string, email: string, password: string, student_id: string, faculty: string, department: string, year: int|string, semester: string, phone?: string|null, gender?: string|null}  $data
     */
    public function createStudent(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('student');

            Student::create([
                'user_id' => $user->id,
                'student_id' => $data['student_id'],
                'full_name' => $data['name'],
                'faculty' => $data['faculty'],
                'department' => $data['department'],
                'year' => (int) $data['year'],
                'semester' => $data['semester'],
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);

            return $user;
        });
    }
}
