<div class="p-3 border-b bg-gray-50">
    <div class="flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Notifications</h3>
        <a href="{{ route('student.notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800">View All</a>
    </div>
</div>
<div class="max-h-96 overflow-y-auto">
    @php
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();
    @endphp
    @forelse($notifications as $notification)
        <div class="p-3 border-b hover:bg-gray-50 transition cursor-pointer" onclick="window.location.href='{{ route('student.notifications.index') }}'">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($notification->type == 'approval')
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                    @elseif($notification->type == 'rejection')
                        <i class="fas fa-times-circle text-red-500 mt-1"></i>
                    @elseif($notification->type == 'completion')
                        <i class="fas fa-certificate text-purple-500 mt-1"></i>
                    @else
                        <i class="fas fa-bell text-blue-500 mt-1"></i>
                    @endif
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $notification->title }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($notification->message, 60) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->is_read)
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                @endif
            </div>
        </div>
    @empty
        <div class="p-6 text-center text-gray-500">
            <i class="fas fa-bell-slash text-3xl mb-2"></i>
            <p class="text-sm">No notifications yet</p>
        </div>
    @endforelse
</div>