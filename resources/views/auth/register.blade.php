<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Salale University Clearance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .register-card {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="register-card w-full max-w-2xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Student Registration</h2>
                <p class="text-blue-100 mt-1">Create your account to get started</p>
            </div>
            
            <div class="p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="John Doe">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Email -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="you@salale.edu.et">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Student ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student ID <span class="text-red-500">*</span></label>
                            <input type="text" name="student_id" value="{{ old('student_id') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="SAL/2024/001">
                            @error('student_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="+251911111111">
                            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Faculty -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faculty <span class="text-red-500">*</span></label>
                            <select name="faculty" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Faculty</option>
                                <option value="Faculty of Computing">Faculty of Computing</option>
                                <option value="Faculty of Business">Faculty of Business</option>
                                <option value="Faculty of Engineering">Faculty of Engineering</option>
                                <option value="Faculty of Natural Sciences">Faculty of Natural Sciences</option>
                                <option value="Faculty of Agriculture">Faculty of Agriculture</option>
                                <option value="Faculty of Social Sciences">Faculty of Social Sciences</option>
                            </select>
                            @error('faculty') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Department -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                            <select name="department_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select your department</option>
                                @foreach($academicDepartments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year of Study <span class="text-red-500">*</span></label>
                            <select name="year" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Year</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                                <option value="5">5th Year</option>
                                <option value="6">6th Year</option>
                            </select>
                            @error('year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                            <select name="semester" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Semester</option>
                                <option value="First">First Semester</option>
                                <option value="Second">Second Semester</option>
                                <option value="Summer">Summer Semester</option>
                            </select>
                            @error('semester') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="••••••••">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="••••••••">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i> Register
                        </button>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>