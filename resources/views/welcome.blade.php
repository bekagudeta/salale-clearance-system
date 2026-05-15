<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salale Clearance System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#001722]">
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 bg-[#001722]/95 backdrop-blur-md border-b border-[#084A48]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="text-2xl font-bold text-[#6BCFCB]">
                    Salale Clearance
                </div>
                <div class="flex gap-6 items-center">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-[#6BCFCB] hover:text-[#FE580B] transition text-sm sm:text-base">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-[#6BCFCB] hover:text-[#FE580B] transition text-sm sm:text-base">Login</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen pt-20 sm:pt-24 md:pt-32 pb-12 sm:pb-16 md:pb-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 right-10 w-72 h-72 bg-[#6BCFCB] rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-72 h-72 bg-[#084A48] rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <span class="inline-block px-3 sm:px-4 py-1 sm:py-2 bg-[#084A48]/20 text-[#6BCFCB] rounded-full text-xs sm:text-sm font-semibold">
                            Streamlined Clearance Process
                        </span>
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">
                            Fast & Secure
                            <span class="text-[#FE580B]"> Clearance</span>
                        </h1>
                        <p class="text-base sm:text-lg md:text-xl text-[#6BCFCB]/80">
                            Simplify your student clearance process with our modern, intuitive system designed for educational institutions.
                        </p>
                    </div>

                    <div class="flex gap-4 flex-col sm:flex-row">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-[#FE580B] text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-[#FE580B]/50 transition transform hover:scale-105 text-center">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-8 py-3 bg-[#FE580B] text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-[#FE580B]/50 transition transform hover:scale-105 text-center">
                                    Login Here
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>

                <!-- Right Visual -->
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="w-full h-96 bg-gradient-to-br from-[#084A48] via-[#6BCFCB] to-[#FE580B] rounded-2xl opacity-20 blur-2xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-[#084A48]/30 to-[#FE580B]/30 rounded-2xl backdrop-blur-sm border border-[#6BCFCB]/20"></div>
                        <div class="absolute inset-4 bg-[#001722]/80 rounded-xl border border-[#6BCFCB]/10 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-24 h-24 text-[#6BCFCB] mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2 1 1 0 100 2H3a1 1 0 000 2h4a1 1 0 100-2 1 1 0 000-2 2 2 0 01-2-2zm10 0a2 2 0 012-2 1 1 0 100 2 1 1 0 000 2h4a1 1 0 100-2h-4a1 1 0 100-2 2 2 0 012-2zm-9 9a1 1 0 100-2H5v-2a1 1 0 00-2 0v2H1a1 1 0 100 2h2v2a1 1 0 002 0v-2h2z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-[#6BCFCB] font-semibold">Advanced Security</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-[#084A48]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Powerful Features</h2>
                <p class="text-base sm:text-lg text-[#6BCFCB]">Everything you need for efficient clearance management</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature 1 -->
                <div class="bg-[#001722]/50 border border-[#6BCFCB]/20 rounded-xl p-6 sm:p-8 hover:border-[#FE580B]/50 transition group">
                    <div class="w-12 h-12 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-[#FE580B]/40 transition">
                        <svg class="w-6 h-6 text-[#FE580B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-white mb-2">Fast Processing</h3>
                    <p class="text-sm sm:text-base text-[#6BCFCB]/70">Streamlined workflows for quick clearance approvals and processing.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-[#001722]/50 border border-[#6BCFCB]/20 rounded-xl p-6 sm:p-8 hover:border-[#FE580B]/50 transition group">
                    <div class="w-12 h-12 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-[#FE580B]/40 transition">
                        <svg class="w-6 h-6 text-[#FE580B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-white mb-2">Secure & Reliable</h3>
                    <p class="text-sm sm:text-base text-[#6BCFCB]/70">Enterprise-grade security to protect all student data and information.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-[#001722]/50 border border-[#6BCFCB]/20 rounded-xl p-6 sm:p-8 hover:border-[#FE580B]/50 transition group">
                    <div class="w-12 h-12 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-[#FE580B]/40 transition">
                        <svg class="w-6 h-6 text-[#FE580B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-white mb-2">Real-time Analytics</h3>
                    <p class="text-sm sm:text-base text-[#6BCFCB]/70">Track clearance progress with detailed reports and insights.</p>
                </div>
            </div>
        </div>
    </section>
                <!-- Feature 1 -->
                <div class="bg-[#001722]/50 border border-[#6BCFCB]/20 rounded-xl p-8 hover:border-[#FE580B]/50 transition group">
                    <div class="w-12 h-12 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-[#FE580B]/40 transition">
                        <svg class="w-6 h-6 text-[#FE580B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Fast Processing</h3>
                    <p class="text-[#6BCFCB]/70">Streamlined workflows for quick clearance approvals and processing.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-[#001722]/50 border border-[#6BCFCB]/20 rounded-xl p-8 hover:border-[#FE580B]/50 transition group">
                    <div class="w-12 h-12 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-[#FE580B]/40 transition">
                        <svg class="w-6 h-6 text-[#FE580B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Secure & Reliable</h3>
                    <p class="text-[#6BCFCB]/70">Enterprise-grade security to protect all student data and information.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-[#001722]/50 border border-[#6BCFCB]/20 rounded-xl p-8 hover:border-[#FE580B]/50 transition group">
                    <div class="w-12 h-12 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-[#FE580B]/40 transition">
                        <svg class="w-6 h-6 text-[#FE580B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Real-time Analytics</h3>
                    <p class="text-[#6BCFCB]/70">Track clearance progress with detailed reports and insights.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-[#001722]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-bold text-[#FE580B] mb-2">100%</div>
                    <p class="text-[#6BCFCB] text-base sm:text-lg">Secure</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-bold text-[#6BCFCB] mb-2">24/7</div>
                    <p class="text-[#6BCFCB] text-base sm:text-lg">Available</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-bold text-[#084A48] mb-2">5min</div>
                    <p class="text-[#6BCFCB] text-base sm:text-lg">Avg Processing</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-[#084A48] via-[#001722] to-[#084A48] relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 right-20 w-96 h-96 bg-[#FE580B] rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
            <p class="text-base sm:text-lg md:text-xl text-[#6BCFCB] mb-8">Contact your institution administrator to request access to the clearance system.</p>
            
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-6 sm:px-8 py-2 sm:py-3 bg-[#FE580B] text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-[#FE580B]/50 transition transform hover:scale-105 text-sm sm:text-base">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-6 sm:px-8 py-2 sm:py-3 bg-[#FE580B] text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-[#FE580B]/50 transition transform hover:scale-105 text-sm sm:text-base">
                        Sign In Now
                    </a>
                @endauth
            @endif
        </div>
    </section>
            
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-6 sm:px-8 py-2 sm:py-3 bg-[#FE580B] text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-[#FE580B]/50 transition transform hover:scale-105 text-sm sm:text-base">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-6 sm:px-8 py-2 sm:py-3 bg-[#FE580B] text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-[#FE580B]/50 transition transform hover:scale-105 text-sm sm:text-base">
                        Sign In Now
                    </a>
                @endauth
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#001722] border-t border-[#084A48]/20 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-[#6BCFCB]/60 mb-2 text-sm sm:text-base">© 2026 Salale Clearance System. All rights reserved.</p>
                <p class="text-[#6BCFCB]/40 text-xs sm:text-sm">Designed for institutional excellence</p>
            </div>
        </div>
    </footer>
</body>
</html>