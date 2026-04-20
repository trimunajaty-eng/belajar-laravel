<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-700">

    {{-- Loading Screen --}}
    <div id="loadingScreen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-700">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 animate-spin rounded-full border-[5px] border-slate-100 border-t-slate-800 shadow-lg"></div>
            <p class="mt-6 text-sm font-medium tracking-wide text-slate-800">Memuat Portal...</p>
        </div>
    </div>

    {{-- Navbar --}}
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
                    @if($user->profile_photo)
                        <img src="{{ Storage::url($user->profile_photo) }}" alt="Foto Profil"
                             class="h-9 w-9 rounded-xl object-cover ring-2 ring-emerald-100">
                    @else
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-sm font-medium text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="hidden text-sm font-medium text-slate-700 sm:inline">{{ $user->name }}</span>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-300" id="chevron"></i>
                </div>

                <div id="dropdownMenu" class="invisible absolute right-0 top-full z-50 mt-3 w-52 translate-y-2 rounded-2xl border border-slate-200 bg-white p-2 opacity-0 shadow-xl transition-all duration-300">
                    <a href="{{ route('employee.profile') }}" class="flex w-full items-center gap-3 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        <i class="fas fa-user text-emerald-500"></i>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('employee.change-password') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-cog text-blue-500"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('employee.leave-requests.index') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-file-medical-alt text-amber-500"></i>
                        <span>Pengajuan</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="showLogoutAnimation(event)">
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

    {{-- Main --}}
    <main class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Back --}}
        <a href="{{ route('dashboard') }}" class="mb-6 inline-flex items-center gap-2 text-sm text-slate-500 transition hover:text-slate-800">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
        </a>

        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            {{-- Header Card --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-500 px-6 py-8 text-center text-white">
                <div class="relative mx-auto mb-4 h-24 w-24">
                    @if($user->profile_photo)
                        <img id="avatarPreview" src="{{ Storage::url($user->profile_photo) }}" alt="Foto Profil"
                             class="h-24 w-24 rounded-full object-cover ring-4 ring-white/50 shadow-lg">
                    @else
                        <div id="avatarInitial" class="flex h-24 w-24 items-center justify-center rounded-full bg-white/20 text-4xl font-bold text-white ring-4 ring-white/30 shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <img id="avatarPreview" src="" alt="Preview" class="hidden h-24 w-24 rounded-full object-cover ring-4 ring-white/50 shadow-lg">
                    @endif
                </div>
                <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                <p class="mt-1 text-sm text-emerald-100">{{ $user->email }}</p>
                <span class="mt-2 inline-block rounded-full bg-white/20 px-3 py-1 text-xs font-medium capitalize">
                    {{ $user->role }}
                </span>
            </div>

            {{-- Upload Form --}}
            <div class="px-6 py-8">
                <h3 class="mb-5 text-base font-semibold text-slate-800">
                    <i class="fas fa-camera mr-2 text-emerald-500"></i>Foto Profil
                </h3>

                @if($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employee.profile.upload-photo') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    {{-- Drop Zone --}}
                    <label for="photoInput"
                           class="group flex cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center transition hover:border-emerald-400 hover:bg-emerald-50"
                           id="dropZone">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm transition group-hover:bg-emerald-100">
                            <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 transition group-hover:text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Klik untuk pilih foto</p>
                            <p class="mt-1 text-xs text-slate-400">JPG, JPEG, PNG — maks. 2 MB</p>
                        </div>
                        <span id="fileName" class="hidden rounded-lg bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700"></span>
                    </label>

                    <input type="file" id="photoInput" name="photo" accept=".jpg,.jpeg,.png" class="hidden">

                    <button type="submit" id="submitBtn"
                            class="mt-5 hidden w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700">
                        <i class="fas fa-upload"></i> Upload Foto
                    </button>
                </form>

                @if($user->profile_photo)
                    <p class="mt-4 text-center text-xs text-slate-400">
                        <i class="fas fa-info-circle mr-1"></i>Upload foto baru akan menggantikan foto saat ini.
                    </p>
                @endif
            </div>
        </div>
    </main>

    {{-- Toast --}}
    <div id="toast" class="fixed right-4 top-4 z-[9999] hidden max-w-[90%] items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-xl sm:right-8 sm:top-8">
        <i class="fas fa-check-circle text-xl text-emerald-600"></i>
        <span id="toast-message" class="text-sm text-slate-700"></span>
    </div>

    {{-- Logout Screen --}}
    <div id="logoutScreen" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-white">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 animate-spin rounded-full border-[5px] border-slate-100 border-t-slate-800 shadow-lg"></div>
            <p class="mt-6 text-sm font-medium tracking-wide text-slate-800">Keluar...</p>
        </div>
    </div>

    <script>
        // Loading screen
        window.addEventListener('load', function () {
            setTimeout(() => {
                const ls = document.getElementById('loadingScreen');
                ls.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => ls.style.display = 'none', 800);
            }, 300);
        });

        // Dropdown
        function toggleDropdown() {
            const d = document.getElementById('dropdownMenu');
            const c = document.getElementById('chevron');
            d.classList.toggle('invisible');
            d.classList.toggle('opacity-0');
            d.classList.toggle('translate-y-2');
            c.classList.toggle('rotate-180');
        }
        document.addEventListener('click', function (e) {
            const wrap = document.querySelector('.relative.flex.items-center');
            const d = document.getElementById('dropdownMenu');
            const c = document.getElementById('chevron');
            if (!wrap.contains(e.target)) {
                d.classList.add('invisible', 'opacity-0', 'translate-y-2');
                c.classList.remove('rotate-180');
            }
        });

        // Logout animation
        function showLogoutAnimation(e) {
            e.preventDefault();
            const ls = document.getElementById('logoutScreen');
            ls.classList.remove('hidden');
            ls.classList.add('flex');
            setTimeout(() => e.target.submit(), 800);
        }

        // File preview
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('avatarPreview');
        const initial = document.getElementById('avatarInitial');
        const fileName = document.getElementById('fileName');
        const submitBtn = document.getElementById('submitBtn');

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (initial) initial.classList.add('hidden');
            };
            reader.readAsDataURL(file);

            fileName.textContent = file.name;
            fileName.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
            submitBtn.classList.add('flex');
        });
    </script>

    @if(session('success'))
    <script>
        const toast = document.getElementById('toast');
        const msg = document.getElementById('toast-message');
        msg.textContent = '{{ session('success') }}';
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => { toast.classList.remove('flex'); toast.classList.add('hidden'); }, 3500);
    </script>
    @endif

</body>
</html>
