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
                <div class="relative group">
                    {{-- Photo display: shows existing photo OR initials fallback --}}
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}"
                             alt="Profile Photo"
                             class="w-16 h-16 rounded-full object-cover border-2 border-[#084A48]/20 shadow-sm">
                    @else
                        <div id="photoPreviewFallback"
                             class="w-16 h-16 rounded-full bg-gradient-to-br from-[#084A48] to-[#6BCFCB] flex items-center justify-center shadow-sm">
                            <span class="text-white text-xl font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    {{-- Hidden img tag for JS preview of newly selected photo (always present) --}}
                    <img id="photoPreview"
                         src="{{ $student->photo_url ? $student->photo_url : '' }}"
                         alt="Profile Photo"
                         class="w-16 h-16 rounded-full object-cover border-2 border-[#084A48]/20 shadow-sm hidden">
                </div>
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

            {{-- ── Profile Photo Upload Card ── --}}
            <div class="flex flex-col items-center gap-4 p-6 rounded-2xl border border-[#d7eeeb] bg-[#f5fafa]">
                {{-- Large preview circle --}}
                <div class="relative">
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}"
                             alt="Profile Photo"
                             class="w-28 h-28 rounded-full object-cover border-4 border-[#084A48]/20 shadow-md">
                    @else
                        <div id="photoLargePreviewFallback"
                             class="w-28 h-28 rounded-full bg-gradient-to-br from-[#084A48] to-[#6BCFCB] flex items-center justify-center shadow-md">
                            <span class="text-white text-4xl font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    {{-- Hidden img tag for JS preview of newly selected photo (always present) --}}
                    <img id="photoLargePreview"
                         src="{{ $student->photo_url ? $student->photo_url : '' }}"
                         alt="Profile Photo"
                         class="w-28 h-28 rounded-full object-cover border-4 border-[#084A48]/20 shadow-md hidden">

                    {{-- Camera icon overlay --}}
                    <label for="photoInput"
                           class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-[#084A48] flex items-center justify-center cursor-pointer shadow hover:bg-[#0a5c5a] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                </div>

                <div class="text-center">
                    <label for="photoInput"
                           class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#084A48] text-white text-sm font-semibold hover:bg-[#0a5c5a] transition-colors shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Choose Photo
                    </label>
                    <p class="mt-2 text-xs text-[#627f7c]">JPG, PNG or GIF · Max 2 MB</p>
                </div>

                {{-- Hidden actual file input --}}
                <input id="photoInput" type="file" name="photo" accept="image/*" class="hidden">
                @error('photo')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- ── Form Fields ── --}}
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
                            <option value="{{ $semester }}"
                                {{ old('semester', $student->semester) === $semester ? 'selected' : '' }}>
                                {{ $semester }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722]">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="mt-2 form-input">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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

{{-- Live photo preview script --}}
<script>
document.getElementById('photoInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (ev) {
        const src = ev.target.result;

        // Update small header preview
        const smallPreview  = document.getElementById('photoPreview');
        const smallFallback = document.getElementById('photoPreviewFallback');
        if (smallPreview) {
            smallPreview.src = src;
            smallPreview.classList.remove('hidden');
        }
        if (smallFallback) smallFallback.classList.add('hidden');

        // Update large card preview
        const largePreview  = document.getElementById('photoLargePreview');
        const largeFallback = document.getElementById('photoLargePreviewFallback');
        if (largePreview) {
            largePreview.src = src;
            largePreview.classList.remove('hidden');
        }
        if (largeFallback) largeFallback.classList.add('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
@endsection