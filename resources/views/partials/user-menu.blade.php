<div class="py-1">
    <a href="@yield('profile-link', '#')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
        <i class="fas fa-user mr-2"></i> My Profile
    </a>
    <a href="@yield('settings-link', '#')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
        <i class="fas fa-cog mr-2"></i> Settings
    </a>
    <hr class="my-1">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </button>
    </form>
</div>