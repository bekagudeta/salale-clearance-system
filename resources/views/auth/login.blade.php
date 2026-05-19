<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Salale University Clearance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: #08101f;
        }
        .login-card {
            animation: fadeInUp 0.65s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input-group { transition: all 0.25s ease; }
        .input-group:focus-within { transform: translateY(-1px); }
        .glass-panel { background: rgba(255,255,255,0.95); backdrop-filter: blur(14px); }
        /* Ensure the panel can shrink on short viewports and allow internal scrolling */
        .glass-panel {
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
        }
        /* Slightly reduce large shadows on small screens */
        @media (max-height: 700px) {
            .shadow-[0_30px_100px_-50px_rgba(0,0,0,0.8)] { box-shadow: 0 10px 30px -10px rgba(0,0,0,0.6); }
        }
    </style>
</head>
<body class="relative overflow-auto">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-[#111829] via-[#0f1527] to-[#08121e]"></div>
    <div class="pointer-events-none absolute -top-28 -left-24 h-80 w-80 rounded-full bg-[#6bcfcb]/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-28 -right-28 h-96 w-96 rounded-full bg-[#fe580b]/15 blur-3xl"></div>

    <div class="relative z-10 flex min-h-screen items-start sm:items-center justify-center px-4 py-8 sm:py-12">
        <div class="w-full max-w-xl">
            <div class="text-center mb-10">
                <div class="mx-auto mb-5 flex h-24 w-24 items-center justify-center rounded-full bg-white/10 ring-1 ring-white/15 shadow-2xl shadow-[#020617]/40 overflow-hidden">
                    <img src="{{ asset('uploads/logos/logo.png') }}" alt="Salale University" class="h-full w-full rounded-full object-cover" />
                </div>
                <h1 class="text-4xl font-semibold text-white">Salale University</h1>
                <p class="mt-2 text-sm text-slate-300">Clearance Management System</p>
            </div>

            <div class="glass-panel overflow-hidden rounded-[2rem] border border-white/10 shadow-[0_30px_100px_-50px_rgba(0,0,0,0.8)]">
                <div class="px-8 py-10 sm:px-10">
                    <div class="mb-8">
                        <span class="inline-flex rounded-full bg-[#6bcfcb]/15 px-4 py-1 text-sm font-semibold text-[#084A48]">Secure access</span>
                        <h2 class="mt-5 text-3xl font-bold text-slate-900">Welcome Back</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Sign in to access your dashboard and manage clearance workflows.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        @if(session('error'))
                            <div class="mb-5 rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-medium text-slate-700">Email Address</label>
                            <div class="input-group relative rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="w-full rounded-3xl border-none bg-transparent py-3 pl-12 pr-4 text-sm text-slate-900 outline-none focus:ring-2 focus:ring-[#6bcfcb]/50"
                                    placeholder="you@example.com">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                            <div class="input-group relative rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <input id="password" type="password" name="password" required
                                    class="w-full rounded-3xl border-none bg-transparent py-3 pl-12 pr-12 text-sm text-slate-900 outline-none focus:ring-2 focus:ring-[#6bcfcb]/50"
                                    placeholder="••••••••">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 transition hover:text-slate-900 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-[#084A48] focus:ring-[#084A48]/60">
                                Remember me
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#084A48] hover:text-[#0b3e34] transition">Forgot password?</a>
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-3xl bg-gradient-to-r from-[#fe580b] to-[#084a48] px-5 py-3 text-base font-semibold text-white shadow-lg shadow-[#083e3b]/20 transition hover:scale-[1.01] hover:shadow-[#fe580b]/30">
                            <i class="fas fa-sign-in-alt"></i>
                            Sign In
                        </button>
                    </form>

                    <div class="mt-7 text-center text-sm text-slate-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-[#084A48] hover:text-[#0b3e34] transition">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordField = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        if (passwordField && togglePassword) {
            togglePassword.addEventListener('click', function () {
                const visible = passwordField.type === 'password';
                passwordField.type = visible ? 'text' : 'password';
                this.innerHTML = visible ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
            });
        }
    </script>
</body>
</html>
