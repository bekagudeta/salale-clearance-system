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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --ink: #0B1F2A;
            --ink-2: #00232F;
            --teal-deep: #084A48;
            --teal-brand: #0E7490;
            --teal-mid: #1BA3C6;
            --teal-light: #38C9EB;
            --aqua: #6BCFCB;
            --green-deep: #14532D;
            --green-mid: #166534;
            --green-light: #22C55E;
            --gold: #F59E0B;
            --accent-orange: #FE580B;
            --surface: rgba(255, 255, 255, 0.97);
            --surface-soft: #F0FAFB;
            --surface-tint: #EAF7F6;
            --text-default: #102A32;
            --text-muted: #64748B;
            --border-soft: rgba(14, 116, 144, 0.14);
        }

        * {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        body {
            background:
                linear-gradient(rgba(56, 201, 235, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 201, 235, 0.035) 1px, transparent 1px),
                linear-gradient(145deg, var(--ink) 0%, var(--ink-2) 42%, var(--teal-deep) 100%);
            background-size: 56px 56px, 56px 56px, auto;
            background-attachment: fixed;
            min-height: 100vh;
            color: #EAF7F6;
        }

        button,
        input,
        textarea,
        select {
            font: inherit;
        }

        a,
        button {
            transition: color 0.18s ease, background 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid rgba(56, 201, 235, 0.45);
            outline-offset: 2px;
        }

        .dashboard-sidebar {
            background:
                linear-gradient(rgba(56, 201, 235, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 201, 235, 0.035) 1px, transparent 1px),
                linear-gradient(160deg, rgba(11, 31, 42, 0.98) 0%, rgba(8, 74, 72, 0.98) 58%, rgba(14, 116, 144, 0.94) 100%);
            background-size: 42px 42px, 42px 42px, auto;
            border-right: 1px solid rgba(56, 201, 235, 0.12);
        }

        .dashboard-brand-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            letter-spacing: -0.01em;
        }

        .dashboard-topbar {
            background: rgba(11, 31, 42, 0.88);
            border-bottom: 1px solid rgba(56, 201, 235, 0.12);
            box-shadow: 0 12px 34px rgba(2, 12, 16, 0.16);
            backdrop-filter: blur(18px);
        }

        .dashboard-main {
            background: rgba(240, 250, 251, 0.78);
            color: var(--text-default);
            min-height: calc(100vh - 64px);
        }

        .surface-card {
            background: var(--surface);
            color: var(--text-default);
            border: 1px solid var(--border-soft);
            box-shadow: 0 18px 46px rgba(11, 31, 42, 0.11);
            border-radius: 24px;
        }

        .surface-card-soft {
            background: var(--surface-soft);
            color: var(--text-default);
            border: 1px solid rgba(14, 116, 144, 0.12);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14);
            border-radius: 18px;
        }

        .dashboard-hero {
            background:
                linear-gradient(135deg, rgba(11, 31, 42, 0.96) 0%, rgba(14, 116, 144, 0.92) 58%, rgba(22, 101, 52, 0.86) 100%);
            color: #ffffff;
            border: 1px solid rgba(56, 201, 235, 0.18);
            box-shadow: 0 24px 58px rgba(11, 31, 42, 0.24);
            border-radius: 24px;
        }

        .dashboard-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            width: fit-content;
            border: 1px solid rgba(56, 201, 235, 0.24);
            background: rgba(56, 201, 235, 0.1);
            color: #CFFAFE;
            border-radius: 9999px;
            padding: 0.45rem 0.8rem;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .dashboard-kicker::before {
            content: '';
            width: 0.42rem;
            height: 0.42rem;
            border-radius: 9999px;
            background: var(--teal-light);
            box-shadow: 0 0 12px rgba(56, 201, 235, 0.8);
        }

        .dashboard-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            letter-spacing: -0.02em;
            line-height: 1.05;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-top: 3px solid rgba(27, 163, 198, 0.58);
            pointer-events: none;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .stat-value {
            color: var(--ink);
            font-size: clamp(1.9rem, 3vw, 2.65rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .icon-tile {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 16px;
            background: rgba(27, 163, 198, 0.12);
            color: var(--teal-brand);
        }

        .icon-tile-accent {
            background: rgba(245, 158, 11, 0.14);
            color: #B45309;
        }

        .icon-tile-success {
            background: rgba(34, 197, 94, 0.12);
            color: var(--green-mid);
        }

        .icon-tile-danger {
            background: rgba(220, 38, 38, 0.1);
            color: #B91C1C;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--teal-mid), var(--teal-brand));
            color: #ffffff;
            border: 1px solid rgba(56, 201, 235, 0.18);
            border-radius: 12px;
            padding: 0.85rem 1.6rem;
            font-weight: 700;
            transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 12px 28px rgba(14, 116, 144, 0.24);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .btn-accent {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--accent-orange), var(--gold));
            color: #ffffff;
            border-radius: 12px;
            padding: 0.85rem 1.6rem;
            font-weight: 700;
            transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 12px 28px rgba(254, 88, 11, 0.2);
        }

        .btn-accent:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(14, 116, 144, 0.08);
            color: var(--teal-deep);
            border: 1px solid rgba(14, 116, 144, 0.14);
            border-radius: 12px;
            padding: 0.85rem 1.6rem;
            font-weight: 700;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-secondary:hover {
            background: rgba(14, 116, 144, 0.14);
            transform: translateY(-1px);
        }

        .form-input {
            background: #fbffff;
            border: 1px solid rgba(14, 116, 144, 0.16);
            color: var(--text-default);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            width: 100%;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--teal-light);
            box-shadow: 0 0 0 4px rgba(56, 201, 235, 0.16);
        }

        .badge-info,
        .badge-teal {
            background: rgba(27, 163, 198, 0.12);
            color: var(--teal-brand);
            border: 1px solid rgba(27, 163, 198, 0.16);
        }

        .badge-accent {
            background: rgba(245, 158, 11, 0.14);
            color: #92400E;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-warning {
            background: rgba(254, 88, 11, 0.12);
            color: #9A3412;
            border: 1px solid rgba(254, 88, 11, 0.18);
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.12);
            color: var(--green-mid);
            border: 1px solid rgba(34, 197, 94, 0.18);
        }

        .badge-danger {
            background: rgba(220, 38, 38, 0.1);
            color: #B91C1C;
            border: 1px solid rgba(220, 38, 38, 0.16);
        }

        .badge-muted {
            background: rgba(100, 116, 139, 0.11);
            color: #475569;
            border: 1px solid rgba(100, 116, 139, 0.15);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 52px rgba(11, 31, 42, 0.14);
        }

        .sidebar-link,
        aside a.sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid transparent;
            border-radius: 14px;
            padding: 0.78rem 0.95rem;
            font-size: 0.92rem;
            font-weight: 700;
            color: rgba(232, 249, 245, 0.92);
        }

        .sidebar-link:hover {
            background: rgba(56, 201, 235, 0.1);
            border-color: rgba(56, 201, 235, 0.14);
            color: #ffffff;
        }

        .sidebar-link.is-active,
        aside a.bg-current {
            background: rgba(56, 201, 235, 0.16) !important;
            border-color: rgba(56, 201, 235, 0.26);
            color: #ffffff !important;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.06), 0 10px 28px rgba(3, 24, 30, 0.18);
        }

        .sidebar-link i {
            width: 1.2rem;
            color: var(--teal-light);
        }

        nav h2 {
            color: #EAF7F6;
        }

        .surface-card a {
            color: var(--teal-deep);
        }

        .surface-card a.btn-primary,
        .surface-card a.btn-accent {
            color: #ffffff;
        }

        .surface-card a.btn-secondary {
            color: var(--teal-deep);
        }

        table thead th {
            color: var(--ink);
            font-weight: 800;
            letter-spacing: 0.07em;
            border-bottom: 1px solid rgba(14, 116, 144, 0.08);
        }

        table tbody td {
            color: var(--text-default);
        }

        .table-shell thead {
            background: linear-gradient(135deg, var(--ink) 0%, var(--teal-deep) 58%, var(--teal-brand) 100%);
        }

        .table-shell thead th {
            color: #ffffff;
        }

        .empty-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4.5rem;
            height: 4.5rem;
            border-radius: 20px;
            background: rgba(27, 163, 198, 0.12);
            color: var(--teal-brand);
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
    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="min-h-screen">
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-20 bg-[#0B1F2A]/60 backdrop-blur-sm lg:hidden"></div>
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="dashboard-sidebar fixed inset-y-0 left-0 z-30 w-72 shadow-2xl sidebar-transition">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center gap-3 px-5 py-5 border-b border-[#38C9EB]/15 bg-white/[0.03]">
                    <img src="{{ asset('uploads/logos/logo.png') }}" alt="Salale University" class="h-12 w-12 object-cover rounded-full bg-white shadow-lg ring-2 ring-[#38C9EB]/25">
                    <div class="min-w-0">
                        <p class="dashboard-brand-title text-xl font-bold leading-tight text-white">Salale University</p>
                        <p class="text-[11px] uppercase tracking-[0.16em] text-[#38C9EB]">Clearance System</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    @yield('sidebar')
                </nav>
                
                <!-- User Info -->
                <div class="p-4 border-t border-[#38C9EB]/15 bg-[#0B1F2A]/25">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1BA3C6] to-[#22C55E] flex items-center justify-center shadow-inner shadow-[#084A48]/20">
                            <span class="text-white font-bold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-[#CFFAFE]/65 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-[#CFFAFE]/65 hover:text-white transition" aria-label="Sign out">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div :class="sidebarOpen ? 'lg:ml-72' : ''" class="min-h-screen transition-all duration-300">
            <!-- Top Navbar -->
            <nav class="dashboard-topbar sticky top-0 z-20">
                <div class="px-4 py-3 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button @click="sidebarOpen = !sidebarOpen" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#38C9EB]/15 bg-white/[0.04] text-[#CFFAFE] hover:bg-[#38C9EB]/10 hover:text-white" aria-label="Toggle navigation">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <div>
                                <h2 class="text-lg font-bold text-[#e8f8f5] sm:text-xl">@yield('page-title', 'Dashboard')</h2>
                                @hasSection('page-subtitle')
                                    <p class="text-xs text-[#CFFAFE]/60">@yield('page-subtitle')</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#38C9EB]/15 bg-white/[0.04] text-[#CFFAFE] hover:bg-[#38C9EB]/10 hover:text-white" aria-label="Open notifications">
                                    <i class="fas fa-bell text-xl"></i>
                                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                        <span class="absolute -top-1 -right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                                            {{ $unreadNotifications }}
                                        </span>
                                    @endif
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-80 surface-card overflow-hidden z-50">
                                    <div class="p-3 border-b border-[#0E7490]/10 bg-[#F0FAFB]">
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
                                <button @click="open = !open" class="flex items-center space-x-2 rounded-xl border border-[#38C9EB]/15 bg-white/[0.04] px-2 py-1.5 text-[#CFFAFE] hover:bg-[#38C9EB]/10 hover:text-white" aria-label="Open profile menu">
                                    @if($profilePhotoUrl)
                                        <img src="{{ $profilePhotoUrl }}" alt="Profile Photo" class="w-9 h-9 rounded-xl object-cover border-2 border-white shadow-sm">
                                    @else
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#1BA3C6] to-[#22C55E] flex items-center justify-center shadow-sm">
                                            <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                        </div>
                                    @endif
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-52 surface-card overflow-hidden z-50">
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
            <main class="dashboard-main p-4 fade-in sm:p-6 lg:p-8">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-5 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-500"></i>
                            <p>{{ session('success') }}</p>
                            <button @click="show = false" class="ml-auto text-green-700" aria-label="Dismiss success message">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-800 shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3 text-yellow-600"></i>
                            <p>{{ session('warning') }}</p>
                            <button @click="show = false" class="ml-auto text-yellow-700" aria-label="Dismiss warning message">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                            <p>{{ session('error') }}</p>
                            <button @click="show = false" class="ml-auto text-red-700" aria-label="Dismiss error message">
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
