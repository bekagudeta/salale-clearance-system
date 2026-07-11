@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'is-active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('student.clearance.create') }}" class="sidebar-link {{ request()->routeIs('student.clearance.create') ? 'is-active' : '' }}">
        <i class="fas fa-plus-circle"></i>
        <span>New Clearance</span>
    </a>

    <a href="{{ route('student.clearance.history') }}" class="sidebar-link {{ request()->routeIs('student.clearance.history') ? 'is-active' : '' }}">
        <i class="fas fa-history"></i>
        <span>My Requests</span>
    </a>

    <a href="{{ route('student.profile.edit') }}" class="sidebar-link {{ request()->routeIs('student.profile.edit') ? 'is-active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>Profile</span>
    </a>

    <a href="{{ route('student.notifications.index') }}" class="sidebar-link {{ request()->routeIs('student.notifications.index') ? 'is-active' : '' }}">
        <i class="fas fa-bell"></i>
        <span>Notifications</span>
        @if(isset($unreadNotifications) && $unreadNotifications > 0)
            <span class="ml-auto rounded-full bg-[#FE580B] px-2 py-0.5 text-[11px] font-semibold text-white">{{ $unreadNotifications }}</span>
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
