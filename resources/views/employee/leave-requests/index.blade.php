<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Saya</title>
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
                    <a href="{{ route('employee.change-password') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-cog text-blue-500"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('employee.leave-requests.index') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-amber-600 transition hover:bg-amber-50">
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

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-slate-700">Pengajuan Saya</span>
        </div>

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-700 to-indigo-600 p-6 text-white shadow-xl sm:flex-1">
                <h2 class="text-xl font-bold tracking-tight sm:text-2xl">Pengajuan Saya</h2>
                <p class="mt-1 text-sm text-slate-200">Riwayat semua pengajuan cuti, sakit, dan izin Anda.</p>
            </div>
            <a href="{{ route('employee.leave-requests.create') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700">
                <i class="fas fa-plus"></i> Buat Pengajuan
            </a>
        </div>

        {{-- Stats --}}
        @php
            $pending  = $leaveRequests->where('status', 'pending')->count();
            $approved = $leaveRequests->where('status', 'approved')->count();
            $rejected = $leaveRequests->where('status', 'rejected')->count();
        @endphp
        <div class="mb-6 grid grid-cols-3 gap-4">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-center">
                <div class="text-2xl font-bold text-amber-600">{{ $leaveRequests->total() > 0 ? $leaveRequests->getCollection()->where('status','pending')->count() : 0 }}</div>
                <div class="mt-1 text-xs font-medium text-amber-700">Menunggu</div>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-center">
                <div class="text-2xl font-bold text-emerald-600">{{ $leaveRequests->getCollection()->where('status','approved')->count() }}</div>
                <div class="mt-1 text-xs font-medium text-emerald-700">Disetujui</div>
            </div>
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $leaveRequests->getCollection()->where('status','rejected')->count() }}</div>
                <div class="mt-1 text-xs font-medium text-red-700">Ditolak</div>
            </div>
        </div>

        {{-- List --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            @forelse($leaveRequests as $request)
                @php
                    $typeConfig = [
                        'cuti'  => ['icon' => 'fa-umbrella-beach', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'label' => 'Cuti'],
                        'sakit' => ['icon' => 'fa-notes-medical',  'bg' => 'bg-red-100',     'text' => 'text-red-600',     'label' => 'Sakit'],
                        'izin'  => ['icon' => 'fa-file-alt',       'bg' => 'bg-blue-100',    'text' => 'text-blue-600',    'label' => 'Izin'],
                    ];
                    $statusConfig = [
                        'pending'  => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',  'icon' => 'fa-clock',       'label' => 'Menunggu'],
                        'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700','icon' => 'fa-check-circle','label' => 'Disetujui'],
                        'rejected' => ['bg' => 'bg-red-100',     'text' => 'text-red-700',    'icon' => 'fa-times-circle','label' => 'Ditolak'],
                    ];
                    $tc = $typeConfig[$request->type];
                    $sc = $statusConfig[$request->status];
                    $duration = $request->start_date->diffInDays($request->end_date) + 1;
                @endphp
                <div class="flex flex-col gap-4 border-b border-slate-100 p-5 last:border-b-0 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $tc['bg'] }}">
                            <i class="fas {{ $tc['icon'] }} text-lg {{ $tc['text'] }}"></i>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-semibold text-slate-900">{{ $tc['label'] }}</span>
                                <span class="rounded-full {{ $sc['bg'] }} {{ $sc['text'] }} px-2.5 py-0.5 text-xs font-medium">
                                    <i class="fas {{ $sc['icon'] }} mr-1"></i>{{ $sc['label'] }}
                                </span>
                            </div>
                            <div class="mt-1 text-sm text-slate-500">
                                {{ $request->start_date->locale('id')->isoFormat('D MMM Y') }}
                                @if($request->start_date != $request->end_date)
                                    — {{ $request->end_date->locale('id')->isoFormat('D MMM Y') }}
                                @endif
                                <span class="ml-1 text-slate-400">({{ $duration }} hari)</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">{{ Str::limit($request->reason, 120) }}</p>

                            @if($request->admin_note)
                                <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                                    <span class="font-medium text-slate-700">Catatan Admin:</span>
                                    <span class="text-slate-600"> {{ $request->admin_note }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="shrink-0 text-right text-xs text-slate-400">
                        {{ $request->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                        @if($request->reviewed_at)
                            <div class="mt-1">Ditinjau: {{ $request->reviewed_at->locale('id')->isoFormat('D MMM Y') }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center text-slate-400">
                    <i class="fas fa-inbox text-5xl mb-4"></i>
                    <p class="text-base font-medium">Belum ada pengajuan</p>
                    <p class="mt-1 text-sm">Klik tombol "Buat Pengajuan" untuk memulai.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($leaveRequests->hasPages())
            <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                @if($leaveRequests->onFirstPage())
                    <span class="cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </span>
                @else
                    <a href="{{ $leaveRequests->previousPageUrl() }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition hover:border-emerald-500 hover:text-emerald-600">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                @endif

                @for($page = 1; $page <= $leaveRequests->lastPage(); $page++)
                    @if($page == $leaveRequests->currentPage())
                        <span class="rounded-xl border border-emerald-600 bg-emerald-600 px-3 py-2 text-sm font-medium text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $leaveRequests->url($page) }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition hover:border-emerald-500 hover:text-emerald-600">{{ $page }}</a>
                    @endif
                @endfor

                @if($leaveRequests->hasMorePages())
                    <a href="{{ $leaveRequests->nextPageUrl() }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition hover:border-emerald-500 hover:text-emerald-600">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        @endif
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
