<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        
        $user = $this->create($request->all());
        
        event(new Registered($user));
        
        // Assign student role
        $user->assignRole('student');
        
        // Create student profile
        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'full_name' => $request->name,
            'faculty' => $request->faculty,
            'department' => $request->department,
            'year' => $request->year,
            'semester' => $request->semester,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);
        
        auth()->login($user);
        
        return redirect()->route('student.dashboard');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'student_id' => ['required', 'string', 'unique:students'],
            'faculty' => ['required', 'string'],
            'department' => ['required', 'string'],
            'year' => ['required', 'integer', 'min:1', 'max:6'],
            'semester' => ['required', 'string'],
            'phone' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}