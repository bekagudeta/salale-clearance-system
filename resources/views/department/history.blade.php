@extends('layouts.officer')

@section('title', 'Approval History - Department')
@section('page-title', 'Approval History')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Approvals</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <i class="fas fa-list text-4xl text-blue-500 opacity-20"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Approved</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-500 opacity-20"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Rejected</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
                <i class="fas fa-times-circle text-4xl text-red-500 opacity-20"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <i class="fas fa-hourglass-half text-4xl text-yellow-500 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
        <form method="GET" action="{{ route('department.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Student Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Reference No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Clearance Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Submitted Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($history as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($item->request->student->full_name, 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-800">{{ $item->request->student->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->request->student->student_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $item->request->reference_no }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $item->request->type)) }}</td>
                        <td class="px-6 py-4">
                            @if($item->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Approved
                                </span>
                            @elseif($item->status == 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-hourglass-half mr-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->request->created_at->format('F d, Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('department.history.show', $item->id) }}" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-gray-400 text-4xl mb-3 block"></i>
                            <p class="text-gray-500 font-medium">No approval history found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($history->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $history->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
