<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Salale University Clearance System</title>
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

        /* ── FORGOT PASSWORD PAGE STYLES ───────────────── */
        .forgot-wrapper {
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

        .forgot-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
        }

        .forgot-card {
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

        .icon-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .key-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(245,158,11,.12);
            border: 2px solid rgba(245,158,11,.3);
            box-shadow: 0 0 0 4px rgba(245,158,11,.15), 0 8px 32px rgba(245,158,11,.25);
            font-size: 24px;
            color: var(--gold);
        }

        .forgot-title {
            font-family: var(--f-display);
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.02em;
            line-height: 1.15;
            margin-bottom: 8px;
        }

        .forgot-desc {
            font-size: 14px;
            font-weight: 300;
            line-height: 1.65;
            color: rgba(224,244,248,.65);
        }

        /* Form styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .03em;
            color: rgba(200,240,248,.85);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            transition: all .25s ease;
        }

        .input-wrapper:focus-within {
            transform: translateY(-1px);
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            font-size: 14px;
            font-weight: 400;
            color: #fff;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--r-sm);
            outline: none;
            transition: all .22s ease;
        }

        .input-wrapper input::placeholder {
            color: rgba(200,240,248,.4);
        }

        .input-wrapper input:focus {
            background: rgba(255,255,255,.10);
            border-color: var(--teal-mid);
            box-shadow: 0 0 0 3px rgba(27,163,198,.15);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(200,240,248,.4);
            font-size: 14px;
            pointer-events: none;
        }

        .success-message {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: var(--r-sm);
            background: rgba(34,197,94,.12);
            border: 1px solid rgba(34,197,94,.25);
            color: #86efac;
            font-size: 13px;
            line-height: 1.5;
        }

        .field-error {
            margin-top: 6px;
            font-size: 12px;
            color: #fca5a5;
        }

        .submit-btn {
            width: 100%;
            padding: 15px 24px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .025em;
            color: #fff;
            background: linear-gradient(135deg, var(--gold), #D97706);
            border: none;
            border-radius: var(--r-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform .18s, box-shadow .22s;
            box-shadow: 0 6px 24px rgba(245,158,11,.35);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 36px rgba(245,158,11,.50);
        }

        .submit-btn i {
            font-size: 14px;
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,.08);
            font-size: 13px;
            color: rgba(200,240,248,.6);
        }

        .back-link a {
            color: var(--teal-light);
            font-weight: 600;
            text-decoration: none;
            transition: color .2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: var(--teal-mid);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .forgot-wrapper {
                padding: 1.5rem;
            }
            .forgot-card {
                padding: 32px 24px;
            }
            .forgot-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-wrapper">
        <div class="glow-1"></div>
        <div class="glow-2"></div>
        <div class="glow-3"></div>
        <div class="grid-pattern"></div>

        <div class="forgot-container">
            <div class="forgot-card">
                <!-- Icon Section -->
                <div class="icon-section">
                    <div class="key-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2 class="forgot-title">Reset Password</h2>
                    <p class="forgot-desc">Enter your email to receive a password reset link.</p>
                </div>

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    @if (session('status'))
                        <div class="success-message">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        Send Reset Link
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>