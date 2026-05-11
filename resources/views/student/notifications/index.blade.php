@extends('layouts.student')

@section('title', 'Notifications - Salale University')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                <p class="text-sm text-gray-500">You have <strong>{{ $unreadCount }}</strong> unread notification{{ $unreadCount === 1 ? '' : 's' }}.</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('student.notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">
                        Mark all as read
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($notifications->isEmpty())
            <div class="rounded-3xl border border-dashed border-gray-200 p-8 text-center text-gray-500">
                <p class="text-xl font-semibold mb-2">No notifications yet</p>
                <p>When there is an update on your clearance requests, you will see it here.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <tr class="transition {{ $notification->is_read ? '' : 'bg-blue-50' }} hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-700">
                                    <div class="font-medium text-gray-900">{{ $notification->title }}</div>
                                    <div class="text-sm text-gray-600 mt-1">{{ $notification->message }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $notification->is_read ? 'bg-gray-100 text-gray-700' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $notification->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    @unless($notification->is_read)
                                        <form action="{{ route('student.notifications.read', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-800">Mark read</button>
                                        </form>
                                    @endunless
                                    <form action="{{ route('student.notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
