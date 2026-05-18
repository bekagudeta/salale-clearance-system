@extends('layouts.student')

@section('title', 'Notifications - Salale University')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6 shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#6BCFCB]">Notifications center</p>
                <h2 class="mt-3 text-3xl font-extrabold text-[#001722]">Your latest updates</h2>
                <p class="mt-2 text-sm text-[#627f7c]">You have <span class="font-semibold text-[#001722]">{{ $unreadCount }}</span> unread notification{{ $unreadCount === 1 ? '' : 's' }}.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="#notifications" class="inline-flex items-center rounded-full bg-[#F5FFFE] px-4 py-3 text-sm font-semibold text-[#084A48] shadow-sm hover:bg-[#E8FAF7] transition">Jump to notifications</a>
                <form action="{{ route('student.notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary">Mark all as read</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-[28px] border border-[#6BCFCB]/20 bg-[#E6FAF8] p-4 text-[#084A48] shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($notifications->isEmpty())
            <div class="rounded-[28px] border border-dashed border-[#084A48]/15 bg-[#F5FFFE] p-12 text-center text-[#627f7c]">
                <p class="text-2xl font-semibold text-[#001722] mb-3">No notifications yet</p>
                <p class="max-w-xl mx-auto">Once there is a new update about your clearance request, it will appear here instantly.</p>
            </div>
        @else
            <div id="notifications" class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="rounded-[28px] border {{ $notification->is_read ? 'border-[#E5F7F6] bg-white' : 'border-[#6BCFCB]/20 bg-[#E8FAF7]' }} p-6 shadow-sm transition hover:shadow-md">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-semibold uppercase tracking-[0.25em] text-[#084A48]">{{ $notification->title }}</span>
                                    <span class="inline-flex rounded-full bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#627f7c] shadow-sm">
                                        {{ $notification->created_at->format('M d, Y • h:i A') }}
                                    </span>
                                </div>
                                <p class="mt-4 text-sm leading-7 text-[#4f6b68]">{{ $notification->message }}</p>
                            </div>

                            <div class="flex flex-col items-start gap-3 text-sm sm:items-end">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $notification->is_read ? 'bg-[#F2F7F6] text-[#627f7c]' : 'bg-[#6BCFCB]/20 text-[#084A48]' }}">
                                    {{ $notification->is_read ? 'Read' : 'Unread' }}
                                </span>
                                <div class="flex flex-wrap items-center gap-2">
                                    @unless($notification->is_read)
                                        <form action="{{ route('student.notifications.read', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-full border border-[#084A48]/20 bg-[#FFFFFF] px-4 py-2 text-sm font-semibold text-[#084A48] transition hover:bg-[#E8FAF7]">Mark read</button>
                                        </form>
                                    @endunless

                                    <form action="{{ route('student.notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-[#FE580B]/20 bg-[#FFFAF7] px-4 py-2 text-sm font-semibold text-[#FE580B] transition hover:bg-[#FFEEE6]">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
