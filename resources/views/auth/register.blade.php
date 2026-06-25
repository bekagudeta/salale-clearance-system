<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Salale University Clearance System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ── DESIGN TOKENS ─────────────────────────────── */
        :root {
            --teal-deep:    #0E7490;
            --teal-mid:     #1BA3C6;
            --teal-light:   #38C9EB;
            --green-deep:   #14532D;
            --green-mid:    #166534;
            --green-light:  #22C55E;
            --gold:         #F59E0B;
            --white:        #FFFFFF;
            --off-white:    #F0FAFB;
            --ink:          #0B1F2A;
            --muted:        #64748B;

            --f-display:  'Cormorant Garamond', Georgia, serif;
            --f-body:     'Plus Jakarta Sans', system-ui, sans-serif;

            --r-sm: 10px;
            --r-md: 16px;
            --r-lg: 24px;
            --r-xl: 36px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--f-body);
            color: var(--ink);
            background: #fff;
            overflow-x: hidden;
            line-height: 1.65;
        }

        /* ── REGISTER PAGE STYLES ───────────────────────── */
        .register-wrapper {
            min-height: 100vh;
            background: linear-gradient(145deg, var(--ink) 0%, #0d2a3a 40%, #0e3d50 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* Decorative radial glows */
        .glow-1 {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(27,163,198,.22) 0%, transparent 65%);
            top: -200px;
            right: -150px;
        }
        .glow-2 {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(20,83,45,.50) 0%, transparent 70%);
            bottom: -100px;
            left: -80px;
        }
        .glow-3 {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245,158,11,.08) 0%, transparent 70%);
            bottom: 120px;
            right: 200px;
        }

        /* Subtle grid lines */
        .grid-pattern {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(27,163,198,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(27,163,198,.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .register-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 700px;
        }

        .register-card {
            background: rgba(255,255,255,.055);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: var(--r-lg);
            padding: 40px 36px;
            animation: fadeInUp 0.65s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.08);
            border: 2px solid rgba(27,163,198,.3);
            box-shadow: 0 0 0 4px rgba(27,163,198,.15), 0 8px 32px rgba(14,116,144,.3);
            overflow: hidden;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-title {
            font-family: var(--f-display);
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.02em;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .brand-subtitle {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--teal-light);
        }

        .register-header {
            margin-bottom: 28px;
        }

        .register-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 16px;
            background: rgba(34,197,94,.10);
            border: 1px solid rgba(34,197,94,.22);
            color: var(--green-light);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green-light);
            box-shadow: 0 0 10px var(--green-light);
            animation: pulse 2.2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .4; transform: scale(1.6); }
        }

        .register-title {
            font-family: var(--f-display);
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.02em;
            line-height: 1.15;
            margin-bottom: 8px;
        }

        .register-desc {
            font-size: 14px;
            font-weight: 300;
            line-height: 1.65;
            color: rgba(224,244,248,.65);
        }

        /* Form styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid .col-span-2 {
            grid-column: span 2;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .03em;
            color: rgba(200,240,248,.85);
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #fca5a5;
            margin-left: 2px;
        }

        .input-wrapper {
            position: relative;
            transition: all .25s ease;
        }

        .input-wrapper:focus-within {
            transform: translateY(-1px);
        }

        .input-wrapper input,
        .input-wrapper select {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 400;
            color: #fff;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--r-sm);
            outline: none;
            transition: all .22s ease;
            appearance: none;
            -webkit-appearance: none;
        }

        .input-wrapper select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2338C9EB'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        .input-wrapper input::placeholder {
            color: rgba(200,240,248,.4);
        }

        .input-wrapper input:focus,
        .input-wrapper select:focus {
            background: rgba(255,255,255,.10);
            border-color: var(--teal-mid);
            box-shadow: 0 0 0 3px rgba(27,163,198,.15);
        }

        .input-wrapper select option {
            background: var(--ink);
            color: #fff;
        }

        .error-message {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: var(--r-sm);
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.25);
            color: #fca5a5;
            font-size: 13px;
            line-height: 1.5;
        }

        .field-error {
            margin-top: 6px;
            font-size: 11px;
            color: #fca5a5;
        }

        .submit-btn {
            width: 100%;
            padding: 15px 24px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .025em;
            color: #fff;
            background: linear-gradient(135deg, var(--green-mid), var(--green-deep));
            border: none;
            border-radius: var(--r-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform .18s, box-shadow .22s;
            box-shadow: 0 6px 24px rgba(34,197,94,.35);
            margin-top: 24px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 36px rgba(34,197,94,.50);
        }

        .submit-btn i {
            font-size: 14px;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,.08);
            font-size: 13px;
            color: rgba(200,240,248,.6);
        }

        .login-link a {
            color: var(--teal-light);
            font-weight: 600;
            text-decoration: none;
            transition: color .2s;
        }

        .login-link a:hover {
            color: var(--teal-mid);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .register-wrapper {
                padding: 1.5rem;
            }
            .register-card {
                padding: 32px 24px;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-grid .col-span-2 {
                grid-column: span 1;
            }
            .brand-title {
                font-size: 24px;
            }
            .register-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="glow-1"></div>
        <div class="glow-2"></div>
        <div class="glow-3"></div>
        <div class="grid-pattern"></div>

        <div class="register-container">
            <div class="register-card">
                <!-- Brand Section -->
                <div class="brand-section">
                    <div class="brand-logo">
                        <img src="{{ asset('uploads/logos/logo.png') }}" alt="Salale University Logo">
                    </div>
                    <h1 class="brand-title">Salale University</h1>
                    <p class="brand-subtitle">Clearance Management System</p>
                </div>

                <!-- Register Header -->
                <div class="register-header">
                    <div class="register-badge">
                        <div class="badge-dot"></div>
                        New Student Registration
                    </div>
                    <h2 class="register-title">Create Your Account</h2>
                    <p class="register-desc">Join the digital clearance portal and start your journey.</p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-grid">
                        <!-- Full Name -->
                        <div class="form-group col-span-2">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe">
                            </div>
                            @error('name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group col-span-2">
                            <label class="form-label">Email Address <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@salale.edu.et">
                            </div>
                            @error('email')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Student ID -->
                        <div class="form-group">
                            <label class="form-label">Student ID <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="text" name="student_id" value="{{ old('student_id') }}" required placeholder="SAL/2024/001">
                            </div>
                            @error('student_id')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <div class="input-wrapper">
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+251911111111">
                            </div>
                            @error('phone')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Faculty -->
                        <div class="form-group">
                            <label class="form-label">Faculty <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <select name="faculty" required>
                                    <option value="">Select Faculty</option>
                                    <option value="Faculty of Computing" {{ old('faculty') == 'Faculty of Computing' ? 'selected' : '' }}>Faculty of Computing</option>
                                    <option value="Faculty of Business" {{ old('faculty') == 'Faculty of Business' ? 'selected' : '' }}>Faculty of Business</option>
                                    <option value="Faculty of Engineering" {{ old('faculty') == 'Faculty of Engineering' ? 'selected' : '' }}>Faculty of Engineering</option>
                                    <option value="Faculty of Natural Sciences" {{ old('faculty') == 'Faculty of Natural Sciences' ? 'selected' : '' }}>Faculty of Natural Sciences</option>
                                    <option value="Faculty of Agriculture" {{ old('faculty') == 'Faculty of Agriculture' ? 'selected' : '' }}>Faculty of Agriculture</option>
                                    <option value="Faculty of Social Sciences" {{ old('faculty') == 'Faculty of Social Sciences' ? 'selected' : '' }}>Faculty of Social Sciences</option>
                                </select>
                            </div>
                            @error('faculty')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="form-group">
                            <label class="form-label">Department <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <select name="department_id" required>
                                    <option value="">Select your department</option>
                                    @foreach($academicDepartments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('department_id')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year -->
                        <div class="form-group">
                            <label class="form-label">Year of Study <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <select name="year" required>
                                    <option value="">Select Year</option>
                                    <option value="1" {{ old('year') == '1' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year') == '2' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year') == '3' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year') == '4' ? 'selected' : '' }}>4th Year</option>
                                    <option value="5" {{ old('year') == '5' ? 'selected' : '' }}>5th Year</option>
                                    <option value="6" {{ old('year') == '6' ? 'selected' : '' }}>6th Year</option>
                                </select>
                            </div>
                            @error('year')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semester -->
                        <div class="form-group">
                            <label class="form-label">Semester <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <select name="semester" required>
                                    <option value="">Select Semester</option>
                                    <option value="First" {{ old('semester') == 'First' ? 'selected' : '' }}>First Semester</option>
                                    <option value="Second" {{ old('semester') == 'Second' ? 'selected' : '' }}>Second Semester</option>
                                    <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer Semester</option>
                                </select>
                            </div>
                            @error('semester')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="form-group">
                            <label class="form-label">Gender</label>
                            <div class="input-wrapper">
                                <select name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label class="form-label">Password <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="password" name="password" required placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label class="form-label">Confirm Password <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="password" name="password_confirmation" required placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>