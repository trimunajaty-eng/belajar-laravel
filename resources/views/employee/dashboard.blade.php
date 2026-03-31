<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-700">
    <div id="loadingScreen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-700">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 animate-spin rounded-full border-[5px] border-slate-100 border-t-slate-800 shadow-lg"></div>
            <p class="mt-6 text-sm font-medium tracking-wide text-slate-800">Memuat Portal...</p>
        </div>
    </div>

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

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-6 overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-700 to-indigo-600 p-6 text-white shadow-xl sm:p-8">
            <h2 class="text-xl font-bold tracking-tight sm:text-2xl">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="mt-2 text-sm text-slate-200">Hari ini {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-5 flex items-center gap-3 text-lg font-semibold text-slate-900">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i class="fas fa-clock"></i>
                    </span>
                    Kehadiran Hari Ini
                </h3>

                <div class="py-4 text-center">
                    @if($todayAttendance)
                        @if($todayAttendance->check_out)
                            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-50 text-4xl text-emerald-600">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="mb-2 text-lg font-semibold text-emerald-600">Pekerjaan Selesai</div>
                            <div class="text-sm text-slate-500">Masuk: {{ $todayAttendance->check_in }}</div>
                            <div class="mt-1 text-sm text-slate-500">Keluar: {{ $todayAttendance->check_out }}</div>
                        @else
                            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-50 text-4xl text-blue-500">
                                <i class="fas fa-play-circle"></i>
                            </div>
                            <div class="mb-2 text-lg font-semibold text-blue-500">Sedang Bekerja</div>
                            <div class="text-sm text-slate-500">Masuk: {{ $todayAttendance->check_in }}</div>
                            <form method="POST" action="{{ route('attendance.checkout') }}" class="mt-5">
                                @csrf
                                <button type="submit" class="inline-flex w-full max-w-[200px] items-center justify-center gap-2 rounded-2xl bg-red-500 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-red-600">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-100 text-4xl text-slate-400">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="mb-2 text-lg font-semibold text-slate-500">Belum Absen</div>
                        <form method="POST" action="{{ route('attendance.checkin') }}" class="mt-5">
                            @csrf
                            <button type="submit" class="inline-flex w-full max-w-[200px] items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-5 flex items-center gap-3 text-lg font-semibold text-slate-900">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                        <i class="fas fa-bullhorn"></i>
                    </span>
                    Pengumuman
                </h3>

                @forelse($announcements as $announcement)
                    <div class="mb-4 rounded-2xl border px-4 py-4 text-sm text-slate-700
                        {{ $announcement->type === 'meeting' ? 'border-blue-200 bg-blue-50' : ($announcement->type === 'urgent' ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50') }}">
                        <div class="mb-2 font-semibold text-slate-900">{{ $announcement->title }}</div>
                        <div>{{ $announcement->content }}</div>
                        @if($announcement->meeting_date)
                            <div class="mt-3 text-xs text-slate-500">
                                <i class="fas fa-calendar mr-1"></i> {{ $announcement->meeting_date->format('M d, Y H:i') }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Tidak ada pengumuman saat ini.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 flex items-center gap-3 text-lg font-semibold text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <i class="fas fa-history"></i>
                </span>
                Riwayat Kehadiran
            </h3>

            <div class="max-h-[300px] overflow-y-auto pr-1">
                @forelse($recentAttendance as $attendance)
                    <div class="flex flex-col gap-1 border-b border-slate-100 py-4 text-sm last:border-b-0 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <div>
                            <strong>{{ $attendance->date->format('M d, Y') }}</strong>
                            <span class="text-slate-500">- {{ ucfirst($attendance->status) }}</span>
                        </div>
                        <div class="text-slate-600">
                            @if($attendance->check_in)
                                In: {{ $attendance->check_in }}
                            @endif
                            @if($attendance->check_out)
                                | Out: {{ $attendance->check_out }}
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Tidak ada catatan kehadiran.</p>
                @endforelse
            </div>
            
            @if($recentAttendance->hasPages())
                <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                    @if ($recentAttendance->onFirstPage())
                        <span class="cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </span>
                    @else
                        <a href="{{ $recentAttendance->previousPageUrl() }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition hover:border-emerald-500 hover:text-emerald-600">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </a>
                    @endif

                    @php
                        $currentPage = $recentAttendance->currentPage();
                        $lastPage = $recentAttendance->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $start + 4);
                        $start = max(1, $end - 4);
                    @endphp

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $currentPage)
                            <span class="rounded-xl border border-emerald-600 bg-emerald-600 px-3 py-2 text-sm font-medium text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $recentAttendance->url($page) }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition hover:border-emerald-500 hover:text-emerald-600">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($recentAttendance->hasMorePages())
                        <a href="{{ $recentAttendance->nextPageUrl() }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition hover:border-emerald-500 hover:text-emerald-600">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <div id="toast" class="fixed right-4 top-4 z-[9999] hidden max-w-[90%] items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-xl sm:right-8 sm:top-8">
        <i class="fas fa-check-circle text-xl text-emerald-600"></i>
        <span id="toast-message" class="text-sm text-slate-700"></span>
    </div>

    <div id="logoutScreen" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-white">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 animate-spin rounded-full border-[5px] border-slate-100 border-t-slate-800 shadow-lg"></div>
            <p class="mt-6 text-sm font-medium tracking-wide text-slate-800">Keluar...</p>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                const loadingScreen = document.getElementById('loadingScreen');
                loadingScreen.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 800);
            }, 300);
        });

        function showLogoutAnimation(event) {
            event.preventDefault();
            const logoutScreen = document.getElementById('logoutScreen');
            logoutScreen.classList.remove('hidden');
            logoutScreen.classList.add('flex');
            setTimeout(() => {
                event.target.submit();
            }, 800);
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');

            dropdown.classList.toggle('invisible');
            dropdown.classList.toggle('opacity-0');
            dropdown.classList.toggle('translate-y-2');

            chevron.classList.toggle('rotate-180');
        }

        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.relative.flex.items-center');
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');

            if (!userInfo.contains(event.target)) {
                dropdown.classList.add('invisible', 'opacity-0', 'translate-y-2');
                chevron.classList.remove('rotate-180');
            }
        });
    </script>

    @if(session('success'))
    <script>
        const toast = document.getElementById('toast');
        const message = document.getElementById('toast-message');
        message.textContent = '{{ session('success') }}';
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => {
            toast.classList.remove('flex');
            toast.classList.add('hidden');
        }, 3000);
    </script>
    @endif

    <script>
    // ── Notification Polling ──────────────────────────────────────
    const POLL_INTERVAL = 10000; // 10 detik
    let shownIds = new Set();

    function getIcon(status) {
        return status === 'approved'
            ? '<i class="fas fa-check-circle" style="color:#16a34a;font-size:1.25rem;"></i>'
            : '<i class="fas fa-times-circle" style="color:#ef4444;font-size:1.25rem;"></i>';
    }

    function showNotifToast(notif) {
        const data    = notif.data;
        const wrapper = document.createElement('div');
        wrapper.className = 'notif-toast';
        wrapper.style.cssText = [
            'position:fixed', 'top:1.25rem', 'right:1.25rem', 'z-index:9999',
            'display:flex', 'align-items:flex-start', 'gap:0.75rem',
            'background:white', 'border:1px solid #e2e8f0',
            'border-radius:1rem', 'padding:1rem 1.25rem',
            'box-shadow:0 10px 25px rgba(0,0,0,0.12)',
            'max-width:340px', 'width:90%',
            'animation:slideIn 0.3s ease',
        ].join(';');

        wrapper.innerHTML = `
            ${getIcon(data.status)}
            <div style="flex:1;">
                <div style="font-size:0.875rem;font-weight:600;color:#1e293b;margin-bottom:0.2rem;">
                    Pengajuan ${data.type.charAt(0).toUpperCase()+data.type.slice(1)}
                    ${data.status === 'approved' ? '<span style="color:#16a34a;">Disetujui</span>' : '<span style="color:#ef4444;">Ditolak</span>'}
                </div>
                <div style="font-size:0.8rem;color:#64748b;">${data.message}</div>
                ${data.admin_note ? `<div style="font-size:0.75rem;color:#94a3b8;margin-top:0.3rem;"><i class="fas fa-reply"></i> ${data.admin_note}</div>` : ''}
                <div style="font-size:0.7rem;color:#cbd5e1;margin-top:0.3rem;">${notif.created}</div>
            </div>
            <button onclick="dismissToast(this, '${notif.id}')" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1rem;padding:0;line-height:1;">&times;</button>
        `;

        document.body.appendChild(wrapper);

        // Auto dismiss setelah 8 detik
        setTimeout(() => dismissToast(wrapper.querySelector('button'), notif.id), 8000);
    }

    function dismissToast(btn, id) {
        const el = btn.closest('.notif-toast');
        if (!el) return;
        el.style.opacity = '0';
        el.style.transform = 'translateX(100%)';
        el.style.transition = 'all 0.3s ease';
        setTimeout(() => el.remove(), 300);

        // Tandai sudah dibaca
        fetch(`/api/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        });
    }

    async function pollNotifications() {
        try {
            const res  = await fetch('/api/notifications/unread');
            const list = await res.json();

            list.forEach(notif => {
                if (!shownIds.has(notif.id)) {
                    shownIds.add(notif.id);
                    showNotifToast(notif);
                }
            });
        } catch (e) {
            // silent fail
        }
    }

    // Jalankan saat halaman load + tiap 10 detik
    pollNotifications();
    setInterval(pollNotifications, POLL_INTERVAL);
    </script>

    <style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(100%); }
        to   { opacity: 1; transform: translateX(0); }
    }
    </style>
</body>
</html>