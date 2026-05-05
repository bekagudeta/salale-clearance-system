@extends('layouts.student')

@section('title', 'My Clearance History - Salale University')
@section('page-title', 'My Clearance History')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">All Clearance Requests</h3>
            <a href="{{ route('student.clearance.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                <i class="fas fa-plus mr-2"></i> New Request
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
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
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $clearance->reference_no }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $clearance->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $clearance->completed_at ? $clearance->completed_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'completed' => 'bg-purple-100 text-purple-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$clearance->status] ?? 'bg-gray-100' }}">
                            {{ ucfirst(str_replace('_', ' ', $clearance->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="w-24">
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $progress }}%</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('student.clearance.show', $clearance->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-5xl mb-3"></i>
                        <p>No clearance requests found</p>
                        <a href="{{ route('student.clearance.create') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                            Start a new clearance request
                        </a>
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
@endsection