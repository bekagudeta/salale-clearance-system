@extends('layouts.registrar')

@section('title', 'All Clearances - Registrar')
@section('page-title', 'All Clearance Requests')
@section('page-subtitle', 'Manage and monitor all student clearance requests')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="surface-card p-6">
        <form method="GET" action="{{ route('registrar.clearance.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="form-input">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Clearance Type</label>
                <select name="type" class="form-input">
                    <option value="all">All Types</option>
                    <option value="graduation" {{ request('type') == 'graduation' ? 'selected' : '' }}>Graduation</option>
                    <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                    <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="dismissal" {{ request('type') == 'dismissal' ? 'selected' : '' }}>Dismissal</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" placeholder="Reference, Name or ID" value="{{ request('search') }}" class="form-input">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Clearance Table -->
    <div class="surface-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($clearances as $clearance)
                    @php
                        $total = $clearance->approvals->count();
                        $approved = $clearance->approvals->where('status', 'approved')->count();
                        $progress = $total > 0 ? round(($approved / $total) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-900">{{ $clearance->reference_no }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $clearance->student->full_name }}</p>
                                <p class="text-xs text-gray-500">ID: {{ $clearance->student->student_id }}</p>
                                <p class="text-xs text-gray-500">{{ $clearance->student->faculty }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $clearance->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="w-32">
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $progress }}% ({{ $approved }}/{{ $total }})</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @include('components.status-badge', ['status' => $clearance->status])
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('registrar.clearance.show', $clearance->id) }}" 
                                   class="btn-secondary px-2 py-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($clearance->status == 'approved')
                                    <form action="{{ route('registrar.clearance.finalize', $clearance->id) }}" method="POST" onsubmit="return confirm('Approve and finalize this clearance? This will generate the certificate.')">
                                        @csrf
                                        <button type="submit" class="btn-accent">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No clearance requests found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($clearances, 'links'))
            <div class="px-6 py-4 border-t">
                {{ $clearances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection