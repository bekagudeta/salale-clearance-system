<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $student = $user->student;
        
        return view('student.profile.edit', compact('user', 'student'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $student = $user->student;
        
        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        // Update student profile
        $student->update([
            'full_name' => $request->name,
            'faculty' => $request->faculty,
            'department' => $request->department,
            'year' => $request->year,
            'semester' => $request->semester,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $student->update(['photo' => $path]);
        }
        
        return redirect()->route('student.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}