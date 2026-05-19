@extends('layouts.registrar')

@section('title', 'Settings - Registrar')
@section('page-title', 'Settings')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Registrar Settings</h2>
                <p class="text-sm text-slate-500">Update your account security settings here.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('registrar.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700">Email Address</label>
                <input type="email" value="{{ $user->email }}" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 shadow-sm focus:border-[#084A48] focus:ring-[#084A48]/50" disabled>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Current Password</label>
                <input type="password" name="current_password" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#084A48] focus:ring-[#084A48]/50" autocomplete="current-password">
                @error('current_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">New Password</label>
                <input type="password" name="password" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#084A48] focus:ring-[#084A48]/50" autocomplete="new-password">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#084A48] focus:ring-[#084A48]/50" autocomplete="new-password">
            </div>

            <div class="pt-4 border-t border-slate-200">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#084A48] px-5 py-3 text-white shadow hover:bg-[#063d37] transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
