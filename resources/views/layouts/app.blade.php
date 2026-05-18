<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Salale University Clearance System')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --rich-black: #001722;
            --deep-jungle: #084A48;
            --pearl-aqua: #6BCFCB;
            --accent-orange: #FE580B;
            --soft-cyan: #E5FCF9;
            --surface: rgba(255, 255, 255, 0.96);
            --surface-soft: rgba(243, 252, 251, 0.96);
            --text-default: #0c2622;
            --text-muted: #627f7c;
            --border-soft: rgba(8, 74, 72, 0.12);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: radial-gradient(circle at top left, rgba(107, 207, 203, 0.14), transparent 30%),
                linear-gradient(135deg, rgba(0, 23, 34, 0.94) 0%, rgba(8, 74, 72, 0.96) 45%, rgba(9, 61, 63, 0.96) 100%);
            min-height: 100vh;
            color: var(--soft-cyan);
        }

        button,
        input,
        textarea,
        select {
            font: inherit;
        }

        .surface-card {
            background: var(--surface);
            color: var(--text-default);
            border: 1px solid var(--border-soft);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.12);
            border-radius: 28px;
        }

        .surface-card-soft {
            background: var(--surface-soft);
            color: var(--text-default);
            border: 1px solid rgba(107, 207, 203, 0.18);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14);
            border-radius: 22px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--deep-jungle), var(--pearl-aqua));
            color: #ffffff;
            border-radius: 9999px;
            padding: 0.85rem 1.6rem;
            transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 14px 32px rgba(8, 74, 72, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .btn-accent {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--accent-orange), var(--deep-jungle));
            color: #ffffff;
            border-radius: 9999px;
            padding: 0.85rem 1.6rem;
            transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 14px 32px rgba(254, 88, 11, 0.22);
        }

        .btn-accent:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(107, 207, 203, 0.14);
            color: var(--rich-black);
            border-radius: 9999px;
            padding: 0.85rem 1.6rem;
            transition: background 0.2s ease;
        }

        .btn-secondary:hover {
            background: rgba(107, 207, 203, 0.25);
        }

        .form-input {
            background: #fbffff;
            border: 1px solid rgba(11, 34, 34, 0.08);
            color: var(--text-default);
            border-radius: 1rem;
            padding: 0.75rem 1rem;
            width: 100%;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--pearl-aqua);
            box-shadow: 0 0 0 8px rgba(107, 207, 203, 0.14);
        }

        .badge-teal {
            background: rgba(107, 207, 203, 0.18);
            color: var(--deep-jungle);
        }

        .badge-accent {
            background: rgba(254, 88, 11, 0.16);
            color: #7f2f00;
        }

        .badge-danger {
            background: rgba(238, 62, 67, 0.12);
            color: #981b1e;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 24px 44px rgba(0, 0, 0, 0.12), 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        aside a {
            color: rgba(232, 249, 245, 0.92);
        }

        aside a:hover {
            background: rgba(11, 34, 34, 0.28);
            color: #ffffff;
        }

        aside a.bg-current {
            background: rgba(107, 207, 203, 0.16) !important;
            color: #ffffff !important;
        }

        nav h2 {
            color: var(--pearl-aqua);
        }

        .surface-card a {
            color: var(--deep-jungle);
        }

        table thead th {
            color: var(--rich-black);
            font-weight: 700;
            border-bottom: 1px solid rgba(8, 74, 72, 0.06);
        }

        table tbody td {
            color: var(--text-default);
        }

        .fade-in {
            animation: fadeIn 0.55s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: true }" class="min-h-screen">
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-br from-[#001722] via-[#084A48] to-[#084A48] shadow-2xl sidebar-transition">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 px-4 bg-[#001722]/80 border-b border-[#6BCFCB]/20">
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('uploads/logos/logo.png') }}" alt="Salale University" class="h-12 w-auto object-contain rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-xs text-gray-400 mt-2 font-medium">Clearance Management System</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    @yield('sidebar')
                </nav>
                
                <!-- User Info -->
                <div class="p-4 border-t border-[#6BCFCB]/20">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#084A48] to-[#6BCFCB] flex items-center justify-center shadow-inner shadow-[#084A48]/20">
                            <span class="text-white font-bold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-white transition">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div :class="sidebarOpen ? 'lg:ml-64' : ''" class="min-h-screen transition-all duration-300">
            <!-- Top Navbar -->
            <nav class="bg-[#001722]/95 border-b border-[#6BCFCB]/10 shadow-lg sticky top-0 z-20 backdrop-blur-sm">
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button @click="sidebarOpen = !sidebarOpen" class="text-[#c8e8e2] hover:text-white focus:outline-none">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <h2 class="text-xl font-semibold text-[#e8f8f5]">@yield('page-title', 'Dashboard')</h2>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="relative text-[#c8e8e2] hover:text-white">
                                    <i class="fas fa-bell text-xl"></i>
                                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                        <span class="absolute -top-1 -right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                                            {{ $unreadNotifications }}
                                        </span>
                                    @endif
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border">
                                    <div class="p-3 border-b">
                                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @yield('notifications-dropdown')
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                @php
                                    $profilePhotoUrl = auth()->user()->student?->photo_url;
                                @endphp
                                <button @click="open = !open" class="flex items-center space-x-2 text-[#c8e8e2] hover:text-white">
                                    @if($profilePhotoUrl)
                                        <img src="{{ $profilePhotoUrl }}" alt="Profile Photo" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#084A48] to-[#6BCFCB] flex items-center justify-center shadow-sm">
                                            <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                        </div>
                                    @endif
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 border">
                                    <a href="@yield('profile-link', '#')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Profile
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
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Page Content -->
            <main class="p-6 fade-in">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-500"></i>
                            <p>{{ session('success') }}</p>
                            <button @click="show = false" class="ml-auto text-green-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                            <p>{{ session('error') }}</p>
                            <button @click="show = false" class="ml-auto text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>