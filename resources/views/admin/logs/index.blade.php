@extends('layouts.admin')

@section('title', 'Activity Logs - Admin')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'Monitor and track system activities')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-500 text-sm">Total Logs</p>
            <p class="text-2xl font-bold">{{ $stats['total_logs'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl shadow-lg p-4">
            <p class="text-blue-600 text-sm">Today's Logs</p>
            <p class="text-2xl font-bold text-blue-700">{{ $stats['today_logs'] }}</p>
        </div>
        <div class="bg-green-50 rounded-xl shadow-lg p-4">
            <p class="text-green-600 text-sm">Unique Users</p>
            <p class="text-2xl font-bold text-green-700">{{ $stats['unique_users'] }}</p>
        </div>
        <div class="bg-purple-50 rounded-xl shadow-lg p-4">
            <p class="text-purple-600 text-sm">Most Active</p>
            <p class="text-sm font-bold text-purple-700">{{ $stats['most_active_action']->action ?? 'N/A' }}</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-4">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                    <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="all">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Table</label>
                    <select name="table_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Tables</option>
                        @foreach($tables as $table)
                            <option value="{{ $table }}" {{ request('table_name') == $table ? 'selected' : '' }}>{{ $table }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <div class="flex space-x-2">
                        <input type="date" name="from_date" value="{{ request('from_date') }}" placeholder="From" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                        <input type="date" name="to_date" value="{{ request('to_date') }}" placeholder="To" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Clear
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Actions Bar -->
    <div class="bg-white rounded-xl shadow-lg p-4">
        <div class="flex justify-between items-center">
            <div class="flex space-x-3">
                <button onclick="confirmClearLogs()" class="border border-red-600 text-red-600 px-4 py-2 rounded-lg hover:bg-red-50 transition">
                    <i class="fas fa-trash mr-2"></i> Clear Old Logs
                </button>
                <a href="{{ route('admin.logs.export') }}" class="border border-green-600 text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-download mr-2"></i> Export
                </a>
            </div>
            <div class="text-sm text-gray-500">
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs
            </div>
        </div>
    </div>
    
    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Table/Record
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" src="{{ $log->user->photo ?? asset('images/default-avatar.png') }}" alt="">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">{{ $log->table_name }}</div>
                                    @if($log->record_id)
                                        <div class="text-gray-500">#{{ $log->record_id }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $log->description }}">
                                    {{ $log->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('M j, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.logs.show', $log->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-list text-4xl mb-4 block text-gray-300"></i>
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $logs->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $logs->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $logs->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $logs->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Clear Logs Modal -->
<div id="clearModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Clear Old Logs</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Select how many days of logs to keep. Logs older than this will be permanently deleted.
                </p>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keep logs from last</label>
                    <select name="days" id="daysSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="7">7 days</option>
                        <option value="30" selected>30 days</option>
                        <option value="90">90 days</option>
                        <option value="365">1 year</option>
                    </select>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <form id="clearForm" method="POST" action="{{ route('admin.logs.clear') }}">
                    @csrf
                    <button type="button" onclick="closeClearModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 hover:bg-red-700">
                        Clear
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmClearLogs() {
    document.getElementById('clearModal').classList.remove('hidden');
}

function closeClearModal() {
    document.getElementById('clearModal').classList.add('hidden');
}

// Auto-refresh logs every 30 seconds
setInterval(function() {
    if (!document.hidden) {
        location.reload();
    }
}, 30000);
</script>
@endsection
