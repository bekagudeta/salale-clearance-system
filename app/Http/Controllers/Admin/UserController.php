<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $query = User::with('student');
        
        // Filter by role
        if ($request->role && $request->role != 'all') {
            $query->role($request->role);
        }
        
        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhereHas('student', function($sq) use ($request) {
                      $sq->where('student_id', 'like', "%{$request->search}%");
                  });
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $roles = Role::all();
        $stats = [
            'total' => User::count(),
            'students' => User::role('student')->count(),
            'officers' => User::role('department_officer')->count(),
            'registrars' => User::role('registrar')->count(),
            'admins' => User::role('super_admin')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'roles', 'stats', 'request'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['super_admin'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $user->assignRole($request->role);
        
        // If role is student, create student profile
        if ($request->role === 'student') {
            $request->validate([
                'student_id' => 'required|string|unique:students',
                'faculty' => 'required|string',
                'department' => 'required|string',
                'year' => 'required|integer|min:1|max:6',
                'semester' => 'required|string',
            ]);
            
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
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::with('student')->findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|exists:roles,name',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        $user->syncRoles([$request->role]);
        
        // Update student profile if exists
        if ($user->student && $request->role === 'student') {
            $user->student->update([
                'full_name' => $request->name,
                'faculty' => $request->faculty ?? $user->student->faculty,
                'department' => $request->department ?? $user->student->department,
                'year' => $request->year ?? $user->student->year,
                'semester' => $request->semester ?? $user->student->semester,
                'phone' => $request->phone ?? $user->student->phone,
                'gender' => $request->gender ?? $user->student->gender,
            ]);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deletion of own account
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Delete student profile if exists
        if ($user->student) {
            $user->student->delete();
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function impersonate($id)
    {
        $user = User::findOrFail($id);
        
        session()->put('impersonated_by', auth()->id());
        auth()->login($user);
        
        return redirect()->route('student.dashboard')
            ->with('success', "You are now impersonating {$user->name}");
    }

    public function stopImpersonation()
    {
        $originalUserId = session()->get('impersonated_by');
        
        if ($originalUserId) {
            session()->forget('impersonated_by');
            $originalUser = User::findOrFail($originalUserId);
            auth()->login($originalUser);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Impersonation stopped.');
        }
        
        return redirect()->route('admin.users.index');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        
        $deleted = 0;
        foreach ($request->user_ids as $userId) {
            if ($userId != auth()->id()) {
                $user = User::find($userId);
                if ($user && !$user->hasRole('super_admin')) {
                    if ($user->student) {
                        $user->student->delete();
                    }
                    $user->delete();
                    $deleted++;
                }
            }
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', "{$deleted} users deleted successfully.");
    }
}