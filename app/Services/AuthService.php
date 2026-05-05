<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new student user
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign student role
        $user->assignRole('student');

        // Create student profile
        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => $data['student_id'],
            'full_name' => $data['name'],
            'faculty' => $data['faculty'],
            'department' => $data['department'],
            'year' => $data['year'],
            'semester' => $data['semester'],
            'phone' => $data['phone'] ?? null,
            'gender' => $data['gender'] ?? null,
        ]);

        return [
            'user' => $user,
            'student' => $student,
        ];
    }

    /**
     * Login user
     */
    public function login(array $credentials, $remember = false)
    {
        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $user = Auth::user();
        
        // Update last login timestamp
        $user->update(['last_login_at' => now()]);
        
        return $user;
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return true;
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword)
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return true;
    }

    /**
     * Reset password using token
     */
    public function resetPassword(User $user, string $password)
    {
        $user->update([
            'password' => Hash::make($password),
        ]);

        return true;
    }

    /**
     * Get redirect URL based on user role
     */
    public function getRedirectUrl(User $user)
    {
        if ($user->hasRole('student')) {
            return route('student.dashboard');
        } elseif ($user->hasRole('department_officer')) {
            return route('department.dashboard');
        } elseif ($user->hasRole('registrar')) {
            return route('registrar.dashboard');
        } elseif ($user->hasRole('super_admin')) {
            return route('admin.dashboard');
        }
        
        return '/dashboard';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(User $user, string $role)
    {
        return $user->hasRole($role);
    }

    /**
     * Get user dashboard route
     */
    public function getDashboardRoute(User $user)
    {
        if ($user->hasRole('student')) {
            return 'student.dashboard';
        } elseif ($user->hasRole('department_officer')) {
            return 'department.dashboard';
        } elseif ($user->hasRole('registrar')) {
            return 'registrar.dashboard';
        } elseif ($user->hasRole('super_admin')) {
            return 'admin.dashboard';
        }
        
        return 'home';
    }
}