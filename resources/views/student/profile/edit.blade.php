@extends('layouts.student')

@section('title', 'Edit Profile - Salale University')
@section('page-title', 'Edit Profile')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6 shadow-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-[#6BCFCB]">Student Profile</p>
                <h2 class="mt-3 text-3xl font-bold text-[#001722]">Update your student details</h2>
                <p class="mt-2 max-w-2xl text-sm text-[#627f7c]">Keep your contact and profile information current so your clearance requests are always processed smoothly.</p>
            </div>
            <div class="flex items-center gap-3">
                @if($student->photo_url)
                    <img src="{{ $student->photo_url }}" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover border border-[#084A48]/20 shadow-sm">
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#084A48] to-[#6BCFCB] flex items-center justify-center shadow-sm">
                        <span class="text-white text-xl font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-[#084A48]">Student ID</p>
                    <p class="font-semibold text-[#001722]">{{ $student->student_id }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-3xl bg-[#E6FAF8] border border-[#6BCFCB]/30 p-4 text-[#0f3c35] shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-2 form-input" required>
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-2 form-input" required>
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Faculty</label>
                    <input type="text" name="faculty" value="{{ old('faculty', $student->faculty) }}" class="mt-2 form-input" required>
                    @error('faculty')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Department</label>
                    <input type="text" name="department" value="{{ old('department', $student->department) }}" class="mt-2 form-input" required>
                    @error('department')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Year of Study</label>
                    <input type="number" name="year" value="{{ old('year', $student->year) }}" min="1" max="6" class="mt-2 form-input" required>
                    @error('year')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Semester</label>
                    <select name="semester" class="mt-2 form-input" required>
                        <option value="">Select semester</option>
                        @foreach(['First', 'Second', 'Summer'] as $semester)
                            <option value="{{ $semester }}" {{ old('semester', $student->semester) === $semester ? 'selected' : '' }}>{{ $semester }}</option>
                        @endforeach
                    </select>
                    @error('semester')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="mt-2 form-input">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-[#001722]">Profile Photo</label>
                    <input type="file" name="photo" accept="image/*" class="mt-2 form-input">
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">New Password</label>
                    <input type="password" name="password" class="mt-2 form-input" placeholder="Leave blank to keep current password">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 form-input" placeholder="Confirm new password">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-[#d7eeeb]">
                <a href="{{ route('student.dashboard') }}" class="btn-secondary">Back to dashboard</a>
                <button type="submit" class="btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
