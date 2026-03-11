<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-50">
    <div class="min-h-screen grid lg:grid-cols-2">
        <!-- Left Side -->
        <div class="hidden lg:flex flex-col justify-between bg-slate-900 px-12 py-10 text-white">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500 text-lg font-bold shadow-lg shadow-blue-500/30">
                        A
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold tracking-wide">Attendance App</h1>
                        <p class="text-sm text-slate-300">Sistem Manajemen Karyawan</p>
                    </div>
                </div>
            </div>

            <div class="max-w-md">
                <p class="mb-4 inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-slate-200">
                    Login Modern SaaS
                </p>
                <h2 class="text-4xl font-bold leading-tight">
                    Selamat datang kembali ke ruang kerja admin Anda.
                </h2>
                <p class="mt-5 text-base leading-7 text-slate-300">
                    Kelola karyawan, absensi, jadwal, dan laporan dalam satu dashboard yang bersih untuk operasional harian.
                </p>

                <div class="mt-10 grid grid-cols-3 gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold">24/7</div>
                        <div class="mt-1 text-xs text-slate-300">Akses Dimana Saja</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold">Cepat</div>
                        <div class="mt-1 text-xs text-slate-300">Alur Kerja Harian</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold">Aman</div>
                        <div class="mt-1 text-xs text-slate-300">Berbasis Sesi</div>
                    </div>
                </div>
            </div>

            <div class="text-sm text-slate-400">
                © {{ date('Y') }} Attendance App. Hak cipta dilindungi.
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex items-center justify-center px-5 py-10 sm:px-8 lg:px-12">
            <div class="w-full max-w-md">
                <!-- Mobile Branding -->
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-base font-bold text-white">
                        A
                    </div>
                    <div>
                        <h1 class="text-base font-semibold text-slate-900">Attendance App</h1>
                        <p class="text-xs text-slate-500">Sistem Manajemen Karyawan</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/60 sm:p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-slate-900">Masuk</h2>
                        <p class="mt-2 text-sm text-slate-500">
                            Silakan masukkan detail akun Anda untuk melanjutkan.
                        </p>
                    </div>
                    
                    @if($errors->has('email') && str_contains($errors->first('email'), 'deactivated'))
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <strong class="block font-semibold">Akun anda telah dinonaktifkan</strong>
                            <span>{{ $errors->first('email') }}</span>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                                Email
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    placeholder="Masukkan email Anda"
                                >
                            </div>
                            @error('email')
                                @if(!str_contains($message, 'deactivated'))
                                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                                Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-11 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    placeholder="Masukkan password Anda"
                                >
                                <i class="fas fa-eye toggle-password absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-slate-400 transition hover:text-blue-500" onclick="togglePassword()"></i>
                            </div>
                            @error('password')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"
                        >
                            <i class="fas fa-right-to-bracket"></i>
                            <span>Login</span>
                        </button>
                    </form>

                    <div class="mt-6 border-t border-slate-100 pt-5 text-center text-xs text-slate-400">
                        Login aman hanya untuk pengguna yang berwenang.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>