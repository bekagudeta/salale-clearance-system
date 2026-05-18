<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            if ($user->hasRole('student') && !$user->student) {
                Auth::logout();
                return back()->withInput($request->only('email', 'remember'))
                    ->with('error', 'Your account is assigned as a student but your student profile is missing. Please contact the administrator.');
            }

            $redirectRoute = $this->getDashboardRoute($user);
            $intendedUrl = $request->session()->pull('url.intended');

            if ($intendedUrl && $this->isIntendedAllowedForRole($intendedUrl, $user)) {
                return redirect()->to($intendedUrl);
            }

            return redirect()->route($redirectRoute);
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    private function getDashboardRoute($user)
    {
        if ($user->hasRole('student')) {
            return 'student.dashboard';
        }

        if ($user->hasRole('department_officer')) {
            return 'department.dashboard';
        }

        if ($user->hasRole('registrar')) {
            return 'registrar.dashboard';
        }

        if ($user->hasRole('super_admin')) {
            return 'admin.dashboard';
        }

        return 'dashboard';
    }

    private function isIntendedAllowedForRole(string $url, $user): bool
    {
        if ($user->hasRole('super_admin')) {
            return Str::contains($url, '/admin');
        }

        if ($user->hasRole('registrar')) {
            return Str::contains($url, '/registrar');
        }

        if ($user->hasRole('department_officer')) {
            return Str::contains($url, '/department');
        }

        if ($user->hasRole('student')) {
            return Str::contains($url, '/student');
        }

        return false;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}