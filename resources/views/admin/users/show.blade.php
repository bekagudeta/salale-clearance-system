@extends('layouts.admin')

@section('title', 'User Details - Admin')
@section('page-title', 'User Details')
@section('page-subtitle', 'View user information and activity')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Card -->
    <div class="lg:col-span-2">
        <div class="surface-card p-6">
            <!-- Header -->
            <div class="flex items-center gap-6 mb-6 pb-6 border-b">
                <div class="w-20 h-20 rounded-full bg-[#084A48] flex items-center justify-center text-white text-3xl font-bold">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    @php
                        $userRole = $user->roles->first();
                        $roleColors = [
                            'super_admin' => 'bg-red-100 text-red-800',
                            'registrar' => 'bg-purple-100 text-purple-800',
                            'department_officer' => 'bg-green-100 text-green-800',
                            'student' => 'bg-blue-100 text-blue-800',
                        ];
                    @endphp
                    @if($userRole)
                        <span class="inline-block px-3 py-1 text-sm rounded-full {{ $roleColors[$userRole->name] ?? 'bg-gray-100' }} mt-2">
                            {{ ucfirst(str_replace('_', ' ', $userRole->name)) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Basic Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Full Name</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Email Address</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <p class="text-lg font-medium text-gray-900">
                            @if($user->email_verified_at)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Verified</span>
                            @else
                                <span class="text-yellow-600"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Joined Date</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            @if($user->student)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Student Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Student ID</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->student->student_id }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Faculty</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->student->faculty }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Department</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->student->department }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Year</p>
                        <p class="text-lg font-medium text-gray-900">Year {{ $user->student->year }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Semester</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->student->semester }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Gender</p>
                        <p class="text-lg font-medium text-gray-900">{{ ucfirst($user->student->gender ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Department Information -->
            @if($user->departments->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Department Assignment</h3>
                <div class="space-y-2">
                    @foreach($user->departments as $dept)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="font-medium text-gray-900">{{ $dept->name }}</p>
                            <div class="grid grid-cols-2 gap-4 mt-2 text-sm">
                                <p class="text-gray-600">
                                    <span class="font-medium">Position:</span>
                                    {{ ucfirst($dept->pivot->position) }}
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-medium">Can Approve:</span>
                                    {{ $dept->pivot->can_approve ? 'Yes' : 'No' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Last Updated -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                <p class="text-gray-900">
                    {{ $user->updated_at->format('M d, Y \a\t g:i A') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Actions -->
        <div class="surface-card p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary w-full text-center block px-4 py-2">
                    <i class="fas fa-edit mr-2"></i> Edit User
                </a>
                @if(!$user->hasRole('super_admin') && $user->id !== auth()->id())
                    <button onclick="confirmDelete({{ $user->id }})" class="btn-accent w-full px-4 py-2">
                        <i class="fas fa-trash mr-2"></i> Delete User
                    </button>
                @endif
                @if(auth()->user()->hasRole('super_admin') && $user->id !== auth()->id())
                    <form action="{{ route('admin.users.impersonate', $user->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="btn-secondary w-full px-4 py-2">
                            <i class="fas fa-mask mr-2"></i> Impersonate
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Account Info -->
        <div class="surface-card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Account Info</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600 mb-1">Account Created</p>
                    <p class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Email Status</p>
                    <p class="font-medium">
                        @if($user->email_verified_at)
                            <span class="text-green-600">✓ Verified</span>
                        @else
                            <span class="text-yellow-600">⚠ Pending</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Role</p>
                    <p class="font-medium text-gray-900">
                        {{ $user->roles->pluck('name')->map(fn($r) => ucfirst(str_replace('_', ' ', $r)))->join(', ') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="mt-6">
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i> Back to Users
    </a>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        document.getElementById('deleteForm').action = '{{ route("admin.users.destroy", ":id") }}'.replace(':id', userId);
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection
