<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-700">

    <nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <h1 class="flex items-center text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">
                <span class="mr-3 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                    <i class="fas fa-user-clock"></i>
                </span>
                Employee Portal
            </h1>

            <div class="relative flex items-center">
                <div onclick="toggleDropdown()" class="flex cursor-pointer items-center gap-2 rounded-xl border border-transparent px-2 py-2 transition hover:border-slate-200 hover:bg-slate-50 sm:gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-sm font-medium text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="hidden text-sm font-medium text-slate-700 sm:inline">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-300" id="chevron"></i>
                </div>

                <div id="dropdownMenu" class="invisible absolute right-0 top-full z-50 mt-3 w-52 translate-y-2 rounded-2xl border border-slate-200 bg-white p-2 opacity-0 shadow-xl transition-all duration-300">
                    <a href="{{ route('dashboard') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-home text-emerald-500"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('employee.change-password') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-blue-600 transition hover:bg-blue-50">
                        <i class="fas fa-cog text-blue-500"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('employee.leave-requests.index') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-file-medical-alt text-amber-500"></i>
                        <span>Pengajuan</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-sm text-slate-700 transition hover:bg-slate-50">
                            <i class="fas fa-sign-out-alt text-red-500"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-slate-700">Ubah Kata Sandi</span>
        </div>

        {{-- Header --}}
        <div class="mb-6 overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-700 to-indigo-600 p-6 text-white shadow-xl">
            <h2 class="text-xl font-bold tracking-tight sm:text-2xl">Ubah Kata Sandi</h2>
            <p class="mt-1 text-sm text-slate-200">Perbarui kata sandi akun Anda secara berkala untuk keamanan.</p>
        </div>

        {{-- Card Form --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('employee.change-password.update') }}" class="space-y-5">
                @csrf

                {{-- Kata Sandi Saat Ini --}}
                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium text-slate-700">
                        Kata Sandi Saat Ini
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 pr-11 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <button type="button" onclick="togglePassword('current_password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-emerald-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kata Sandi Baru --}}
                <div>
                    <label for="new_password" class="mb-2 block text-sm font-medium text-slate-700">
                        Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 pr-11 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <button type="button" onclick="togglePassword('new_password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-emerald-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Kata Sandi Baru --}}
                <div>
                    <label for="new_password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">
                        Konfirmasi Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 pr-11 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <button type="button" onclick="togglePassword('new_password_confirmation', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-emerald-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700">
                        <i class="fas fa-save"></i> Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- Toast --}}
    <div id="toast" class="fixed right-4 top-4 z-[9999] hidden max-w-[90%] items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-xl sm:right-8 sm:top-8">
        <i class="fas fa-check-circle text-xl text-emerald-600"></i>
        <span id="toast-message" class="text-sm text-slate-700"></span>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');
            dropdown.classList.toggle('invisible');
            dropdown.classList.toggle('opacity-0');
            dropdown.classList.toggle('translate-y-2');
            chevron.classList.toggle('rotate-180');
        }

        document.addEventListener('click', function(e) {
            const nav = document.querySelector('.relative.flex.items-center');
            const dropdown = document.getElementById('dropdownMenu');
            if (!nav.contains(e.target)) {
                dropdown.classList.add('invisible', 'opacity-0', 'translate-y-2');
                document.getElementById('chevron').classList.remove('rotate-180');
            }
        });

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

    @if(session('success'))
    <script>
        const toast = document.getElementById('toast');
        toast.querySelector('#toast-message').textContent = '{{ session('success') }}';
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => { toast.classList.replace('flex', 'hidden'); }, 4000);
    </script>
    @endif

</body>
</html>
