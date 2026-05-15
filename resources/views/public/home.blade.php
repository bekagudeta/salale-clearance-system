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
        .hero-section {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('uploads/logos/logo.png') }}" alt="Salale University" class="h-8 w-auto object-contain rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 mr-2">
                    <span class="font-bold text-xl text-gray-800">Salale University</span>
                    <span class="ml-2 text-sm text-gray-500">Clearance System</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-indigo-600 font-medium transition">Home</a>
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 font-medium transition">Features</a>
                    <a href="#workflow" class="text-gray-600 hover:text-indigo-600 font-medium transition">Workflow</a>
                    <a href="#roles" class="text-gray-600 hover:text-indigo-600 font-medium transition">Roles</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 transition font-medium">Login</a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-lg hover:shadow-lg transition font-medium">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6 leading-tight">Salale University Digital Clearance Portal</h1>
                    <p class="text-xl mb-8 text-blue-100 leading-relaxed">Track, approve, and manage student clearance digitally across departments in real time.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 rounded-lg font-semibold hover:shadow-xl transition transform hover:scale-105 text-center">
                            Get Started <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="#features" class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition text-center">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 shadow-2xl border border-white/20">
                        <div class="bg-gray-900 rounded-xl p-4 shadow-inner">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-8 bg-indigo-600/30 rounded-lg animate-pulse"></div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="h-16 bg-blue-600/30 rounded-lg animate-pulse"></div>
                                    <div class="h-16 bg-purple-600/30 rounded-lg animate-pulse"></div>
                                    <div class="h-16 bg-green-600/30 rounded-lg animate-pulse"></div>
                                </div>
                                <div class="h-20 bg-indigo-600/20 rounded-lg animate-pulse"></div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="h-12 bg-blue-600/20 rounded-lg animate-pulse"></div>
                                    <div class="h-12 bg-purple-600/20 rounded-lg animate-pulse"></div>
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
                <p class="text-gray-600 max-w-2xl mx-auto">Our system provides a seamless experience for students, department officers, and administrators</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Easy Application</h3>
                    <p class="text-gray-600">Submit clearance requests online from anywhere, anytime</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tachometer-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Real-time Tracking</h3>
                    <p class="text-gray-600">Track your clearance progress in real-time</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Instant Notifications</h3>
                    <p class="text-gray-600">Get email and in-app notifications for updates</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Analytics & Reports</h3>
                    <p class="text-gray-600">Comprehensive reports and analytics dashboard</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-qrcode text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">QR Verification</h3>
                    <p class="text-gray-600">Secure QR codes for certificate verification</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Secure & Reliable</h3>
                    <p class="text-gray-600">Bank-level security for your data</p>
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
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-graduate text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Student</h3>
                    <p class="text-gray-600 text-sm">Submit clearance requests and track progress in real-time</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-building text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Department</h3>
                    <p class="text-gray-600 text-sm">Review and approve clearance requests for your department</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-certificate text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Registrar</h3>
                    <p class="text-gray-600 text-sm">Final approval and clearance certificate generation</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Super Admin</h3>
                    <p class="text-gray-600 text-sm">Full system control and user management</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works -->
    <section id="workflow" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Clearance Workflow</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Streamlined 4-step process for efficient student clearance management</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">1</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Student Submits Request</h3>
                    <p class="text-gray-500 text-sm">Student initiates clearance process by submitting request online</p>
                    <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
                </div>
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">2</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Departments Verify</h3>
                    <p class="text-gray-500 text-sm">Each department reviews and approves their specific requirements</p>
                    <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-purple-600 to-blue-600"></div>
                </div>
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">3</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Registrar Approves</h3>
                    <p class="text-gray-500 text-sm">Registrar performs final review and grants approval</p>
                    <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-blue-600 to-green-600"></div>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">4</div>
                    <h3 class="font-semibold mb-2 text-gray-800">Certificate Generated</h3>
                    <p class="text-gray-500 text-sm">Clearance certificate is generated and available for download</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="hero-section text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl mb-8">Join thousands of students who have streamlined their clearance process</p>
            <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:shadow-xl transition inline-block">
                Register Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
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
                        <li><i class="fas fa-envelope mr-2 text-indigo-400"></i> info@salale.edu.et</li>
                        <li><i class="fas fa-phone mr-2 text-indigo-400"></i> +251-XXX-XXXX</li>
                        <li><i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i> Salale University, Ethiopia</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">System Info</h4>
                    <ul class="space-y-2 text-sm">
                        <li><span class="text-gray-500">Version:</span> 1.0.0</li>
                        <li><span class="text-gray-500">Status:</span> <span class="text-green-400">Active</span></li>
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
</body>
</html>