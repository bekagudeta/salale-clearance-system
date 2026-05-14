@extends('layouts.admin')

@section('title', 'Create User - Admin')
@section('page-title', 'Create New User')
@section('page-subtitle', 'Add a new user to the system')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Card -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm" enctype="multipart/form-data">
                @csrf
                
                <!-- Basic Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Security</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="flex gap-2 items-center">
                                <input type="password" name="password" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror" required>
                                <button type="button" onclick="generatePassword()" class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-200 transition">Generate</button>
                            </div>
                            <small class="text-gray-500">Minimum 8 characters</small>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Role & Permission</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($roles as $role)
                            @if($role->name !== 'super_admin')
                                @php
                                    $roleLabel = ucfirst(str_replace('_', ' ', $role->name));
                                    $description = match ($role->name) {
                                        'student' => 'Student able to submit and track clearance requests.',
                                        'department_officer' => 'Department staff responsible for approvals.',
                                        'registrar' => 'Registrar with academic record access.',
                                        default => 'System user with assigned role.',
                                    };
                                @endphp
                                <label class="cursor-pointer rounded-2xl border p-4 transition hover:border-blue-500 {{ old('role') == $role->name ? 'border-blue-600 bg-blue-50 shadow-sm' : 'border-gray-200 bg-white' }}">
                                    <input type="radio" name="role" value="{{ $role->name }}" class="sr-only role-radio" {{ old('role') == $role->name ? 'checked' : ($loop->first && !old('role') ? 'checked' : '') }}>
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-white font-bold">{{ strtoupper(substr($roleLabel, 0, 2)) }}</div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $roleLabel }}</p>
                                            <p class="text-sm text-gray-500">{{ $description }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>
                    @error('role')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Student Fields (Hidden by default) -->
                <div id="studentFields" style="display: none;">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Student Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                            <input type="text" name="student_id" value="{{ old('student_id') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('student_id') border-red-500 @enderror">
                            @error('student_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
                            <input type="text" name="faculty" value="{{ old('faculty') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('faculty') border-red-500 @enderror">
                            @error('faculty')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" name="department" value="{{ old('department') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror">
                            @error('department')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                            <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-500 @enderror">
                                <option value="">-- Select Year --</option>
                                <option value="1" {{ old('year') == '1' ? 'selected' : '' }}>Year 1</option>
                                <option value="2" {{ old('year') == '2' ? 'selected' : '' }}>Year 2</option>
                                <option value="3" {{ old('year') == '3' ? 'selected' : '' }}>Year 3</option>
                                <option value="4" {{ old('year') == '4' ? 'selected' : '' }}>Year 4</option>
                                <option value="5" {{ old('year') == '5' ? 'selected' : '' }}>Year 5</option>
                                <option value="6" {{ old('year') == '6' ? 'selected' : '' }}>Year 6</option>
                            </select>
                            @error('year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                            <select name="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('semester') border-red-500 @enderror">
                                <option value="">-- Select Semester --</option>
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                            </select>
                            @error('semester')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gender') border-red-500 @enderror">
                                <option value="">-- Select Gender --</option>
                                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photo (Optional)</label>
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 rounded-lg bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center" id="photoPreview">
                                    <i class="fas fa-camera text-gray-400 text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="photo" accept="image/*" id="photoInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <small class="text-gray-500">JPG, PNG max 2MB</small>
                                    @error('photo')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Officer Fields -->
                <div id="officerFields" style="display: none;" class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Department Officer Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select name="department_id" id="departmentSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department_id') border-red-500 @enderror">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                            <input type="text" name="position" value="{{ old('position', 'staff') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('position') border-red-500 @enderror" placeholder="e.g. Head, Staff, Assistant">
                            @error('position')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-3 mt-1">
                                <input type="checkbox" name="can_approve" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('can_approve', true) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">Can approve clearance requests</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i> Create User
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
            <h3 class="text-lg font-semibold text-blue-900 mb-4">User Roles</h3>
            <ul class="space-y-3 text-sm text-blue-800">
                <li><strong>Student:</strong> Can submit clearance requests</li>
                <li><strong>Department Officer:</strong> Approves clearance requests from departments</li>
                <li><strong>Registrar:</strong> Manages academic records</li>
                <li><strong>Super Admin:</strong> Full system access</li>
            </ul>
        </div>

        <div class="bg-amber-50 rounded-xl shadow-lg p-6 mt-4">
            <h3 class="text-lg font-semibold text-amber-900 mb-4">Password Requirements</h3>
            <ul class="space-y-2 text-sm text-amber-800">
                <li>✓ Minimum 8 characters</li>
                <li>✓ Mix of uppercase and lowercase</li>
                <li>✓ Include numbers and symbols</li>
                <li>✓ Avoid dictionary words</li>
            </ul>
        </div>
    </div>
</div>

<script>
    function toggleRoleSections() {
        const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
        const studentFields = document.getElementById('studentFields');
        const officerFields = document.getElementById('officerFields');

        studentFields.style.display = selectedRole === 'student' ? 'block' : 'none';
        officerFields.style.display = selectedRole === 'department_officer' ? 'block' : 'none';

        document.querySelectorAll('#studentFields input, #studentFields select').forEach(field => {
            field.required = selectedRole === 'student' && field.name !== 'phone';
        });

        document.querySelectorAll('#officerFields select, #officerFields input').forEach(field => {
            field.required = selectedRole === 'department_officer' && field.name !== 'position';
        });
    }

    function generatePassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.querySelector('input[name="password"]').value = password;
        document.querySelector('input[name="password_confirmation"]').value = password;
    }

    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover rounded-lg">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', toggleRoleSections);
        });
        toggleRoleSections();
    });
</script>
@endsection
