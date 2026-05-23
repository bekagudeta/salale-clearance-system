<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salale University - Clearance Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --rich-black: #001722;
            --deep-jungle-green: #084A48;
            --pearl-aqua: #6BCFCB;
            --orange: #FE580B;
        }
        .hero-section {
            background: linear-gradient(135deg, rgba(8, 74, 72, 0.95) 0%, rgba(0, 23, 34, 0.95) 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 80% 20%, rgba(107, 207, 203, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(254, 88, 11, 0.08) 0%, transparent 60%);
            pointer-events: none;
        }
        nav {
            backdrop-filter: blur(20px);
            background-color: rgba(255, 255, 255, 0.95);
            transition: all 0.3s ease;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        nav.scrolled {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.15);
        }
        .nav-link {
            position: relative;
            font-weight: 500;
            color: #374151;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, #FE580B, #084A48);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link:hover {
            color: #FE580B;
        }
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
        }
        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(10px, 10px);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }
        .hamburger span {
            width: 25px;
            height: 3px;
            background: #084A48;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .mobile-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            flex-direction: column;
            gap: 0;
            border-top: 1px solid #e5e7eb;
            animation: slideDown 0.3s ease;
        }
        .mobile-menu.active {
            display: flex;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }
            .hidden.md\:flex {
                display: none !important;
            }
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(8, 74, 72, 0.15);
        }
        .stats-number {
            animation: countUp 2s ease-out;
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo and Brand -->
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-[#FE580B] to-[#084A48] rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('uploads/logos/logo.png') }}" alt="Salale" class="w-8 h-8 md:w-10 md:h-10 object-contain">
                    </div>
                    <div>
                        <div class="font-bold text-lg md:text-xl text-gray-900">Salale</div>
                        <div class="text-xs text-[#084A48] font-semibold">Clearance System</div>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#" class="nav-link">Home</a>
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#workflow" class="nav-link">Workflow</a>
                    <a href="#roles" class="nav-link">Roles</a>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-block px-6 py-2 bg-gradient-to-r from-[#FE580B] to-[#084A48] text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-[#FE580B]/40 transition-all duration-300 transform hover:scale-105">
                        Login
                    </a>
                    <!-- Mobile Hamburger -->
                    <div class="hamburger md:hidden" id="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu md:hidden" id="mobileMenu">
                <a href="#" class="px-4 py-3 text-gray-700 hover:bg-gray-50 border-b">Home</a>
                <a href="#features" class="px-4 py-3 text-gray-700 hover:bg-gray-50 border-b">Features</a>
                <a href="#workflow" class="px-4 py-3 text-gray-700 hover:bg-gray-50 border-b">Workflow</a>
                <a href="#roles" class="px-4 py-3 text-gray-700 hover:bg-gray-50 border-b">Roles</a>
                <a href="{{ route('login') }}" class="px-4 py-3 bg-gradient-to-r from-[#FE580B] to-[#084A48] text-white font-semibold">Login</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section text-white pt-24 sm:pt-28 pb-10 sm:pb-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 xl:gap-12 items-center">
                <div class="text-center xl:text-left">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-[0.7rem] sm:text-sm font-semibold uppercase tracking-[0.2em] text-[#6BCFCB]">
                        Digital clearance portal
                    </span>
                    <h1 class="mt-5 text-[1.8rem] sm:text-4xl lg:text-5xl font-bold leading-tight max-w-2xl mx-auto xl:mx-0">
                        Salale University Clearance Management System
                    </h1>
                    <p class="mt-4 text-sm sm:text-lg leading-relaxed text-[#d9f4f2] max-w-xl mx-auto xl:mx-0">
                        A secure, unified platform for student clearance requests, department approvals, and certificate delivery.
                    </p>
                    <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center xl:justify-start">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-[#FE580B] text-white rounded-lg font-semibold text-center hover:shadow-xl transition transform hover:scale-[1.02]">
                            Sign In <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="#features" class="w-full sm:w-auto px-8 py-4 border-2 border-[#6BCFCB] text-[#6BCFCB] rounded-lg font-semibold text-center hover:bg-[#6BCFCB] hover:text-[#001722] transition">
                            Learn More
                        </a>
                    </div>
                </div>

                <div class="mt-8 xl:mt-0">
                    <div class="space-y-4 sm:space-y-5">
                        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-5 sm:p-6 border border-white/20 shadow-2xl">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#6BCFCB]">Operational overview</p>
                                    <h2 class="mt-2 text-lg sm:text-xl font-semibold text-white">Current clearance activity</h2>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-[#FE580B] to-[#6BCFCB] rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fas fa-chart-bar text-white text-lg"></i>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm sm:text-base text-[#d9f4f2]">Requests in progress</span>
                                    <span class="text-2xl sm:text-3xl font-bold text-white">1,247</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-[#FE580B] to-[#6BCFCB] h-2 rounded-full" style="width: 78%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-white/10 backdrop-blur-xl rounded-xl p-4 border border-white/20 hover:border-[#FE580B]/40 transition-all duration-300">
                                <div class="w-10 h-10 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-3">
                                    <i class="fas fa-bolt text-[#FE580B] text-lg"></i>
                                </div>
                                <div class="text-sm text-[#6BCFCB]/80 mb-1">Avg speed</div>
                                <div class="text-xl font-bold text-white">3.2 min</div>
                            </div>

                            <div class="bg-white/10 backdrop-blur-xl rounded-xl p-4 border border-white/20 hover:border-[#6BCFCB]/40 transition-all duration-300">
                                <div class="w-10 h-10 bg-[#6BCFCB]/20 rounded-lg flex items-center justify-center mb-3">
                                    <i class="fas fa-shield-alt text-[#6BCFCB] text-lg"></i>
                                </div>
                                <div class="text-sm text-[#6BCFCB]/80 mb-1">Security</div>
                                <div class="text-xl font-bold text-white">100%</div>
                            </div>

                            <div class="bg-white/10 backdrop-blur-xl rounded-xl p-4 border border-white/20 hover:border-[#084A48]/40 transition-all duration-300">
                                <div class="w-10 h-10 bg-[#084A48]/20 rounded-lg flex items-center justify-center mb-3">
                                    <i class="fas fa-check-circle text-[#084A48] text-lg"></i>
                                </div>
                                <div class="text-sm text-[#6BCFCB]/80 mb-1">Success rate</div>
                                <div class="text-xl font-bold text-white">99.8%</div>
                            </div>

                            <div class="bg-white/10 backdrop-blur-xl rounded-xl p-4 border border-white/20 hover:border-[#FE580B]/40 transition-all duration-300">
                                <div class="w-10 h-10 bg-[#FE580B]/20 rounded-lg flex items-center justify-center mb-3">
                                    <i class="fas fa-building text-[#FE580B] text-lg"></i>
                                </div>
                                <div class="text-sm text-[#6BCFCB]/80 mb-1">Departments</div>
                                <div class="text-xl font-bold text-white">18+</div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-5 sm:p-6 border border-white/20">
                            <h2 class="text-lg sm:text-xl font-semibold text-white mb-4">Key benefits</h2>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-[#FE580B]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-check text-[#FE580B] text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white text-sm">Instant approvals</div>
                                        <div class="text-xs text-[#d9f4f2]/80">Real-time processing</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-check text-[#6BCFCB] text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white text-sm">Digital records</div>
                                        <div class="text-xs text-[#d9f4f2]/80">Secure & traceable</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-[#084A48]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-check text-[#084A48] text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white text-sm">24/7 access</div>
                                        <div class="text-xs text-[#d9f4f2]/80">Anytime, anywhere</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Section -->
    <section id="features" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Key Features</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">A modern clearance solution designed for student efficiency, department collaboration, and administration control.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-[#084A48] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Online Clearance Requests</h3>
                    <p class="text-gray-600">Submit clearance requests digitally, eliminating paper forms and manual follow-up.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#084A48]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tachometer-alt text-[#084A48] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Live Progress Tracking</h3>
                    <p class="text-gray-600">Monitor request status and approval stages with real-time visibility.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#FE580B]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell text-[#FE580B] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Automated Notifications</h3>
                    <p class="text-gray-600">Receive timely email and in-app alerts for approvals, rejects, and updates.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#084A48]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-[#084A48] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Advanced Reporting</h3>
                    <p class="text-gray-600">Generate actionable reports for clearance activity and departmental performance.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-qrcode text-[#084A48] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Certificate Verification</h3>
                    <p class="text-gray-600">Securely verify clearance certificates using QR-enabled authentication.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#001722]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-[#001722] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Secure Data Management</h3>
                    <p class="text-gray-600">Protect student records with secure storage, access control, and audit trails.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- User Roles Section -->
    <section id="roles" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">User Roles</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Our system supports multiple user roles for efficient clearance management</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-graduate text-[#084A48] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Student</h3>
                    <p class="text-gray-600 text-sm">Create requests, view approval stages, and download certificates from a single portal.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#084A48]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-building text-[#084A48] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Department Officer</h3>
                    <p class="text-gray-600 text-sm">Manage approvals, validate requirements, and communicate status updates efficiently.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#FE580B]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-certificate text-[#FE580B] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Registrar</h3>
                    <p class="text-gray-600 text-sm">Perform final clearance approvals and publish official certificates securely.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-[#001722]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-[#001722] text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Administrator</h3>
                    <p class="text-gray-600 text-sm">Configure system settings, manage users, and oversee clearance workflows.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works -->
    <section id="workflow" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Clearance Workflow</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Streamlined 4-step clearance workflow built for speed, transparency, and compliance.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-[#084A48] text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">1</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Student Submits Request</h3>
                    <p class="text-gray-500 text-sm">Students submit clearance requests securely through the online portal.</p>
                    <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-[#084A48] to-[#6BCFCB]"></div>
                </div>
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-[#6BCFCB] text-[#001722] rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">2</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Departments Review</h3>
                    <p class="text-gray-500 text-sm">Department officers validate clearance requirements and approve requests online.</p>
                    <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-[#6BCFCB] to-[#FE580B]"></div>
                </div>
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-[#FE580B] text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">3</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Registrar Finalizes</h3>
                    <p class="text-gray-500 text-sm">Registrar completes final verification and authorizes the clearance certificate.</p>
                    <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-[#FE580B] to-[#084A48]"></div>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-[#001722] text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">4</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Certificate Delivered</h3>
                    <p class="text-gray-500 text-sm">Official certificate is generated digitally and made available for secure download.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-[#084A48] to-[#001722] text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl mb-8 text-[#6BCFCB]">Request access from your institution administrator and start managing clearances with confidence.</p>
            <a href="{{ route('login') }}" class="px-8 py-3 bg-[#FE580B] text-white rounded-lg font-semibold hover:shadow-xl transition inline-block">
                Sign In Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-[#001722] text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <img src="{{ asset('uploads/logos/logo.png') }}" alt="Salale University" class="h-16 w-auto object-contain rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 mb-4">
                    <p class="text-sm mb-2">Salale University Digital Clearance Portal</p>
                    <p class="text-xs text-gray-500">Streamlining student clearance processes through digital innovation</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#workflow" class="hover:text-white transition">Workflow</a></li>
                        <li><a href="#roles" class="hover:text-white transition">User Roles</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fas fa-envelope mr-2 text-[#6BCFCB]"></i> info@salale.edu.et</li>
                        <li><i class="fas fa-phone mr-2 text-[#6BCFCB]"></i> +251-XXX-XXXX</li>
                        <li><i class="fas fa-map-marker-alt mr-2 text-[#6BCFCB]"></i> Salale University, Ethiopia</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">System Info</h4>
                    <ul class="space-y-2 text-sm">
                        <li><span class="text-gray-500">Version:</span> 1.0.0</li>
                        <li><span class="text-gray-500">Status:</span> <span class="text-[#6BCFCB]">Active</span></li>
                    </ul>
                    <h4 class="text-white font-semibold mb-4 mt-6">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fab fa-linkedin text-xl"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fab fa-telegram text-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; 2024 Salale University. All rights reserved. | Clearance Management System v1.0.0</p>
            </div>
        </div>
    </footer>

    <script>
        window.scrollTo(0, 0);

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (hamburger) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                mobileMenu.classList.toggle('active');
            });

            // Close mobile menu when clicking on links
            document.querySelectorAll('.mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('active');
                });
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    document.querySelector(href).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        window.addEventListener('load', () => {
            window.scrollTo(0, 0);
        });
    </script>
</body>
</html>