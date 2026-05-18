@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('student.dashboard') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6bcfcb]/10 hover:text-white' }}">
        <i class="fas fa-tachometer-alt w-5"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('student.clearance.create') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('student.clearance.create') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6bcfcb]/10 hover:text-white' }}">
        <i class="fas fa-plus-circle w-5"></i>
        <span>New Clearance</span>
    </a>

    <a href="{{ route('student.clearance.history') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('student.clearance.history') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6bcfcb]/10 hover:text-white' }}">
        <i class="fas fa-history w-5"></i>
        <span>My Requests</span>
    </a>

    <a href="{{ route('student.profile.edit') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('student.profile.edit') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6bcfcb]/10 hover:text-white' }}">
        <i class="fas fa-user-circle w-5"></i>
        <span>Profile</span>
    </a>

    <a href="{{ route('student.notifications.index') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('student.notifications.index') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6bcfcb]/10 hover:text-white' }}">
        <i class="fas fa-bell w-5"></i>
        <span>Notifications</span>
        @if(isset($unreadNotifications) && $unreadNotifications > 0)
            <span class="ml-auto bg-red-500 text-white text-[11px] font-semibold px-2 py-0.5 rounded-full">{{ $unreadNotifications }}</span>
        @endif
    </a>
@endsection

@section('notifications-dropdown')
    @php
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();
    @endphp
    @forelse($notifications as $notification)
        <div class="p-3 border-b hover:bg-gray-50 transition">
            <p class="text-sm font-medium text-gray-800">{{ $notification->title }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($notification->message, 60) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
        </div>
    @empty
        <div class="p-4 text-center text-gray-500">
            <i class="fas fa-bell-slash text-2xl mb-2"></i>
            <p>No notifications</p>
        </div>
    @endforelse
@endsection

@section('profile-link', route('student.profile.edit'))
@section('settings-link', route('student.profile.edit'))