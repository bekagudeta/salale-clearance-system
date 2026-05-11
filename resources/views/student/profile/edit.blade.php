@extends('layouts.student')

@section('title', 'Edit Profile - Salale University')
@section('page-title', 'Edit Profile')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Update your profile</h2>
                <p class="text-sm text-gray-500">Keep your student contact and profile details up to date.</p>
            </div>
            <div class="flex items-center gap-3">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover border border-gray-200">
                @else
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xl font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Student ID</p>
                    <p class="font-semibold text-gray-900">{{ $student->student_id }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Faculty</label>
                    <input type="text" name="faculty" value="{{ old('faculty', $student->faculty) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('faculty')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Department</label>
                    <input type="text" name="department" value="{{ old('department', $student->department) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('department')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Year of Study</label>
                    <input type="number" name="year" value="{{ old('year', $student->year) }}" min="1" max="6" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('year')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Semester</label>
                    <select name="semester" class="mt-2 block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select semester</option>
                        @foreach(['First', 'Second', 'Summer'] as $semester)
                            <option value="{{ $semester }}" {{ old('semester', $student->semester) === $semester ? 'selected' : '' }}>{{ $semester }}</option>
                        @endforeach
                    </select>
                    @error('semester')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" class="mt-2 block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                    <input type="file" name="photo" accept="image/*" class="mt-2 block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Leave blank to keep current password">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Confirm new password">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Back to dashboard</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-95">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
