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
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: true }" class="min-h-screen">
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-br from-gray-900 to-gray-800 shadow-2xl sidebar-transition">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 px-4 bg-gray-900/50 border-b border-gray-700">
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
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
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
            <nav class="bg-white shadow-lg sticky top-0 z-20">
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="relative text-gray-600 hover:text-gray-900">
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
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                                    </div>
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