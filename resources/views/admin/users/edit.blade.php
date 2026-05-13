@extends('layouts.admin')

@section('title', 'Edit User - Admin')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user information and permissions')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Card -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password (Optional) -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Security (Optional)</h3>
                    <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                            <small class="text-gray-500">Minimum 8 characters</small>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Role & Permission</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                        <select name="role" id="roleSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror" required onchange="toggleStudentFields()">
                            <option value="">-- Select a Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Student Fields (Shown if user is student) -->
                <div id="studentFields" style="display: {{ $user->hasRole('student') ? 'block' : 'none' }};">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Student Information</h3>
                    
                    @if($user->student)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                                <input type="text" name="student_id" value="{{ old('student_id', $user->student->student_id ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('student_id') border-red-500 @enderror">
                                @error('student_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
                                <input type="text" name="faculty" value="{{ old('faculty', $user->student->faculty ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('faculty') border-red-500 @enderror">
                                @error('faculty')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <input type="text" name="department" value="{{ old('department', $user->student->department ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror">
                                @error('department')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                                <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-500 @enderror">
                                    <option value="">-- Select Year --</option>
                                    <option value="1" {{ old('year', $user->student->year ?? '') == '1' ? 'selected' : '' }}>Year 1</option>
                                    <option value="2" {{ old('year', $user->student->year ?? '') == '2' ? 'selected' : '' }}>Year 2</option>
                                    <option value="3" {{ old('year', $user->student->year ?? '') == '3' ? 'selected' : '' }}>Year 3</option>
                                    <option value="4" {{ old('year', $user->student->year ?? '') == '4' ? 'selected' : '' }}>Year 4</option>
                                    <option value="5" {{ old('year', $user->student->year ?? '') == '5' ? 'selected' : '' }}>Year 5</option>
                                    <option value="6" {{ old('year', $user->student->year ?? '') == '6' ? 'selected' : '' }}>Year 6</option>
                                </select>
                                @error('year')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                                <select name="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('semester') border-red-500 @enderror">
                                    <option value="">-- Select Semester --</option>
                                    <option value="1" {{ old('semester', $user->student->semester ?? '') == '1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ old('semester', $user->student->semester ?? '') == '2' ? 'selected' : '' }}>Semester 2</option>
                                </select>
                                @error('semester')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gender') border-red-500 @enderror">
                                    <option value="">-- Select Gender --</option>
                                    <option value="M" {{ old('gender', $user->student->gender ?? '') == 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('gender', $user->student->gender ?? '') == 'F' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $user->student->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->student->phone ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i> Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-blue-50 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">User Info</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li><strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}</li>
                <li><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y') }}</li>
                <li><strong>Status:</strong> <span class="text-green-600 font-semibold">Active</span></li>
            </ul>
        </div>

        <div class="bg-green-50 rounded-xl shadow-lg p-6 mt-4">
            <h3 class="text-lg font-semibold text-green-900 mb-4">Current Roles</h3>
            <div class="space-y-2">
                @forelse($userRoles as $role)
                    <span class="inline-block bg-green-200 text-green-800 px-3 py-1 rounded-full text-sm">
                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                    </span>
                @empty
                    <p class="text-sm text-gray-600">No roles assigned</p>
                @endforelse
            </div>
        </div>

        @if($user->student)
        <div class="bg-purple-50 rounded-xl shadow-lg p-6 mt-4">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">Student Details</h3>
            <ul class="space-y-2 text-sm text-purple-800">
                <li><strong>Student ID:</strong> {{ $user->student->student_id }}</li>
                <li><strong>Faculty:</strong> {{ $user->student->faculty }}</li>
                <li><strong>Department:</strong> {{ $user->student->department }}</li>
                <li><strong>Year:</strong> {{ $user->student->year }}</li>
            </ul>
        </div>
        @endif
    </div>
</div>

<script>
    function toggleStudentFields() {
        const role = document.getElementById('roleSelect').value;
        const studentFields = document.getElementById('studentFields');
        
        if (role === 'student') {
            studentFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
        }
    }

    // Check on page load
    document.addEventListener('DOMContentLoaded', toggleStudentFields);
</script>
@endsection
