@extends('layouts.admin')

@section('title', 'Activity Logs - Admin')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'Monitor and track system activities with clarity')

@section('content')
<div class="space-y-6">
    <div class="grid gap-4 xl:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Total Logs</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['total_logs'] }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-slate-100 text-slate-700">
                    <i class="fas fa-file-alt"></i>
                </span>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-400">Today's Logs</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['today_logs'] }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-sky-100 text-sky-700">
                    <i class="fas fa-calendar-day"></i>
                </span>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400">Unique Users</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $stats['unique_users'] }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-users"></i>
                </span>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-violet-400">Most Active</p>
                    <p class="mt-4 text-base font-semibold text-slate-900">{{ $stats['most_active_action']->action ?? 'N/A' }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ optional($stats['most_active_action'])->total ? optional($stats['most_active_action'])->total . ' events' : 'No activity yet' }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-violet-100 text-violet-700">
                    <i class="fas fa-chart-line"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2">
                <h2 class="text-xl font-semibold text-slate-900">Filter activity history</h2>
                <p class="text-sm text-slate-500">Use search and filters to quickly surface the logs you need.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.logs.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    <i class="fas fa-eraser"></i>
                    Reset Filters
                </a>
                <button type="button" onclick="confirmClearLogs()" class="inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                    <i class="fas fa-trash"></i>
                    Clear Old Logs
                </button>
            </div>
        </div>

        <form method="GET" class="mt-6 grid gap-4 xl:grid-cols-[1.4fr_1fr]">
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by description, IP, or keyword" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Action</label>
                    <select name="action" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                        <option value="all">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Table</label>
                    <select name="table_name" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                        <option value="">All Tables</option>
                        @foreach($tables as $table)
                            <option value="{{ $table }}" {{ request('table_name') == $table ? 'selected' : '' }}>{{ $table }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">User</label>
                    <select name="user_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2 lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Date range</label>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200" />
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200" />
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-end gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    <i class="fas fa-filter mr-2"></i> Apply filters
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Activity logs</h3>
                <p class="mt-1 text-sm text-slate-500">Sorted by newest activity first.</p>
            </div>
            <div class="flex flex-wrap gap-3 items-center">
                <p class="text-sm text-slate-500">Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}</p>
                <button type="button" onclick="location.reload()" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full min-w-[900px] divide-y divide-slate-200 text-sm text-slate-700">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Action</th>
                        <th class="px-6 py-3">Table / Record</th>
                        <th class="px-6 py-3">Description</th>
                        <th class="px-6 py-3">IP Address</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">View</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                @if($log->user)
                                    <div class="flex items-center gap-3">
                                        <img class="h-10 w-10 rounded-2xl object-cover" src="{{ $log->user->photo ?? asset('images/default-avatar.png') }}" alt="{{ $log->user->name }}">
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $log->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $log->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                                        <i class="fas fa-cogs"></i>
                                        System
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">{{ $log->action }}</span>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-medium text-slate-900">{{ $log->table_name }}</div>
                                @if($log->record_id)
                                    <div class="text-xs text-slate-500">#{{ $log->record_id }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top max-w-[320px]">
                                <div class="text-slate-700 line-clamp-2" title="{{ $log->description }}">{{ $log->description }}</div>
                            </td>
                            <td class="px-6 py-4 align-top text-slate-500">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 align-top text-slate-500">{{ $log->created_at->format('M j, Y H:i') }}</td>
                            <td class="px-6 py-4 align-top">
                                <a href="{{ route('admin.logs.show', $log->id) }}" class="inline-flex items-center gap-2 text-slate-700 hover:text-slate-900">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <i class="fas fa-clipboard-list text-4xl text-slate-300"></i>
                                <p class="mt-4 text-base">No activity logs found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Showing <span class="font-medium text-slate-900">{{ $logs->firstItem() }}</span> to <span class="font-medium text-slate-900">{{ $logs->lastItem() }}</span> of <span class="font-medium text-slate-900">{{ $logs->total() }}</span> entries
                </p>
                <div>{{ $logs->links() }}</div>
            </div>
        @endif
    </div>
</div>

<div id="clearModal" class="fixed inset-0 z-50 hidden bg-slate-950/50 p-4 backdrop-blur-sm">
    <div class="mx-auto w-full max-w-lg overflow-hidden rounded-[32px] bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Clear Old Logs</h3>
                <p class="mt-2 text-sm text-slate-500">Permanently delete older activity logs to keep the audit trail manageable.</p>
            </div>
            <button type="button" onclick="closeClearModal()" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:border-slate-300 hover:text-slate-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="clearForm" method="POST" action="{{ route('admin.logs.clear') }}" class="space-y-6 px-6 py-6">
            @csrf
            <div class="space-y-2">
                <label for="daysSelect" class="block text-sm font-medium text-slate-700">Keep logs from last</label>
                <select id="daysSelect" name="days" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    <option value="7">7 days</option>
                    <option value="30" selected>30 days</option>
                    <option value="90">90 days</option>
                    <option value="365">1 year</option>
                </select>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeClearModal()" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">Clear logs</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmClearLogs() {
    document.getElementById('clearModal').classList.remove('hidden');
}

function closeClearModal() {
    document.getElementById('clearModal').classList.add('hidden');
}
</script>
@endsection
