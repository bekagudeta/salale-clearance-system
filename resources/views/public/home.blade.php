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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 transition">Login</a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-4">Student Clearance Management System</h1>
            <p class="text-xl mb-8 text-blue-100">Streamline your graduation and clearance process with our automated system</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:shadow-xl transition transform hover:scale-105">
                    Get Started <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#features" class="px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition">
                    Learn More
                </a>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <i class="fas fa-users text-blue-600 text-4xl mb-3"></i>
                    <div class="text-3xl font-bold text-gray-800 stats-number" data-count="5000">0</div>
                    <p class="text-gray-600">Active Students</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-building text-green-600 text-4xl mb-3"></i>
                    <div class="text-3xl font-bold text-gray-800 stats-number" data-count="11">0</div>
                    <p class="text-gray-600">Departments</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-check-circle text-yellow-600 text-4xl mb-3"></i>
                    <div class="text-3xl font-bold text-gray-800 stats-number" data-count="2500">0</div>
                    <p class="text-gray-600">Clearances Processed</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-clock text-purple-600 text-4xl mb-3"></i>
                    <div class="text-3xl font-bold text-gray-800 stats-number" data-count="70">0</div>
                    <p class="text-gray-600">Hours Saved</p>
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
    
    <!-- How It Works -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">How It Works</h2>
                <p class="text-gray-600">Simple 4-step process to get your clearance certificate</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                    <h3 class="font-semibold mb-2">Register Account</h3>
                    <p class="text-gray-500 text-sm">Create your student account</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                    <h3 class="font-semibold mb-2">Submit Request</h3>
                    <p class="text-gray-500 text-sm">Fill and submit clearance form</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                    <h3 class="font-semibold mb-2">Department Approval</h3>
                    <p class="text-gray-500 text-sm">Departments review and approve</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">4</div>
                    <h3 class="font-semibold mb-2">Download Certificate</h3>
                    <p class="text-gray-500 text-sm">Get your clearance certificate</p>
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
                    <p class="text-sm">Excellence in Education</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">FAQs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fas fa-envelope mr-2"></i> info@salale.edu.et</li>
                        <li><i class="fas fa-phone mr-2"></i> +251-XXX-XXXX</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="hover:text-white"><i class="fab fa-linkedin text-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; 2024 Salale University. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Animate stats counters
        const counters = document.querySelectorAll('.stats-number');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.count);
            let current = 0;
            const increment = target / 50;
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current);
                    setTimeout(updateCounter, 40);
                } else {
                    counter.textContent = target;
                }
            };
            updateCounter();
        });
    </script>
</body>
</html>