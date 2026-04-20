<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 64px; }
        .navbar h1 { font-size: 1.25rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; }
        .user-info { position: relative; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: background 0.2s; }
        .user-info:hover { background: #f8fafc; }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; font-weight: 500; font-size: 0.875rem; }
        .user-name { font-weight: 500; color: #475569; }
        .chevron { transition: transform 0.3s; font-size: 0.75rem; color: #64748b; }
        .chevron.rotate { transform: rotate(180deg); }
        .dropdown-menu { position: absolute; top: 100%; right: 0; margin-top: 0.5rem; background: white; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s; }
        .dropdown-menu.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-menu form { margin: 0; }
        .dropdown-item { width: 100%; padding: 0.75rem 1rem; border: none; background: none; text-align: left; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.875rem; transition: background 0.2s; text-decoration: none; }
        .dropdown-item:hover { background: #f8fafc; }
        .dropdown-item i { color: #ef4444; }
        .sidebar { position: fixed; left: 0; top: 64px; width: 256px; height: calc(100vh - 64px); background: #ffffff; border-right: 1px solid #e2e8f0; padding: 1.5rem 0; overflow-y: auto; transition: transform 0.3s ease; z-index: 999; }
        .sidebar ul { list-style: none; padding: 0 1rem; }
        .sidebar li { margin-bottom: 0.25rem; }
        .sidebar a { text-decoration: none; color: #64748b; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
        .sidebar a:hover { background: #f1f5f9; color: #475569; }
        .sidebar a.active { background: #dc2626; color: white; }
        .sidebar i { width: 18px; text-align: center; font-size: 0.875rem; }
        .hamburger { display: none; background: none; border: none; font-size: 1.5rem; color: #1e293b; cursor: pointer; padding: 0.5rem; margin-right: 0.5rem; }
        .sidebar-overlay { position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 998; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .sidebar-overlay.active { opacity: 1; pointer-events: auto; }
        .main-content { margin-left: 256px; margin-top: 64px; padding: 2rem; transition: margin-left 0.3s ease, filter 0.3s ease; }
        .main-content.blurred { filter: blur(3px); pointer-events: none; }
        body { opacity: 0; animation: pageFadeIn 0.22s ease forwards; }
        @keyframes pageFadeIn { to { opacity: 1; } }
        .page-leaving { opacity: 0; transition: opacity 0.18s ease; }
        @media (max-width: 768px) {
            .hamburger { display: block; }
            .user-name { display: none; }
            .sidebar { transform: translateX(-100%); will-change: transform; }
            .sidebar.active { transform: translateX(0); box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; padding: 1rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <h1><i class="fas fa-chart-line" style="color: #dc2626; margin-right: 0.5rem;"></i>Admin Dashboard</h1>
        <div class="user-info" onclick="toggleDropdown()">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down chevron" id="chevron"></i>
            <div class="dropdown-menu" id="dropdownMenu">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
                </form>
            </div>
        </div>
    </nav>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="{{ route('work-settings.index') }}"><i class="fas fa-clock"></i> Work Schedule</a></li>
            <li><a href="{{ route('announcements.index') }}" class="active"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li>
                <a href="{{ route('admin.leave-requests.index') }}">
                    <i class="fas fa-file-medical-alt"></i> Pengajuan
                    @if(!empty($pendingCount) && $pendingCount > 0)
                        <span style="margin-left:auto;background:#dc2626;color:white;font-size:0.65rem;font-weight:700;border-radius:9999px;min-width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;padding:0 5px;">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>

    <div class="main-content">

        {{-- Page Header --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-800">Pengumuman</h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola pengumuman yang tampil kepada seluruh karyawan.</p>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <i class="fas fa-check-circle text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Toolbar --}}
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">{{ $announcements->count() }} pengumuman ditemukan</p>
            <div class="flex items-center gap-2">
                <a href="{{ route('announcements.trash') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50">
                    <i class="fas fa-trash-alt text-slate-400"></i> Sampah
                </a>
                <a href="{{ route('announcements.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-700">
                    <i class="fas fa-plus"></i> 
                </a>
            </div>
        </div>

        {{-- Announcement List --}}
        <div class="space-y-4">
            @forelse($announcements as $announcement)
                @php
                    $borderColor = match($announcement->type) {
                        'meeting' => 'border-l-blue-500',
                        'urgent'  => 'border-l-red-500',
                        default   => 'border-l-amber-400',
                    };
                    $iconBg = match($announcement->type) {
                        'meeting' => 'bg-blue-100 text-blue-600',
                        'urgent'  => 'bg-red-100 text-red-600',
                        default   => 'bg-amber-100 text-amber-600',
                    };
                    $icon = match($announcement->type) {
                        'meeting' => 'fa-calendar-alt',
                        'urgent'  => 'fa-exclamation-triangle',
                        default   => 'fa-info-circle',
                    };
                    $typeLabel = match($announcement->type) {
                        'meeting' => 'Rapat',
                        'urgent'  => 'Mendesak',
                        default   => 'Umum',
                    };
                    $typeBadge = match($announcement->type) {
                        'meeting' => 'bg-blue-100 text-blue-700',
                        'urgent'  => 'bg-red-100 text-red-700',
                        default   => 'bg-amber-100 text-amber-700',
                    };
                @endphp
                <div class="flex gap-4 rounded-xl border border-slate-200 border-l-4 {{ $borderColor }} bg-white p-5 shadow-sm transition hover:shadow-md {{ $announcement->isExpired() ? 'opacity-60' : '' }}">

                    {{-- Icon --}}
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $iconBg }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>

                    {{-- Body --}}
                    <div class="min-w-0 flex-1">

                        {{-- Title row --}}
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <h3 class="text-sm font-semibold text-slate-800">{{ $announcement->title }}</h3>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $typeBadge }}">
                                    <i class="fas {{ $icon }} text-[10px]"></i> {{ $typeLabel }}
                                </span>
                                @if($announcement->isExpired())
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">
                                        <i class="fas fa-clock text-[10px]"></i> Kedaluwarsa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">
                                        <i class="fas fa-check-circle text-[10px]"></i> Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $announcement->content }}</p>

                        {{-- Meta --}}
                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-400">
                            @if($announcement->meeting_date)
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-calendar-alt"></i>
                                    Rapat: {{ $announcement->meeting_date->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                </span>
                            @endif
                            @if($announcement->expired_at)
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-hourglass-end"></i>
                                    Berakhir: {{ $announcement->expired_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                </span>
                            @else
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-infinity"></i> Tidak ada batas waktu
                                </span>
                            @endif
                            <span class="flex items-center gap-1">
                                <i class="fas fa-clock"></i>
                                Dibuat: {{ $announcement->created_at->locale('id')->isoFormat('D MMM Y') }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('announcements.edit', $announcement->id) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-blue-700">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form id="delete-form-{{ $announcement->id }}" method="POST" action="{{ route('announcements.destroy', $announcement->id) }}">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button"
                                onclick="openDeleteModal({{ $announcement->id }}, '{{ addslashes($announcement->title) }}')"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white py-16 text-center">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                        <i class="fas fa-bullhorn text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-600">Belum ada pengumuman</p>
                    <p class="mt-1 text-xs text-slate-400">Klik "Tambah Pengumuman" untuk membuat yang pertama.</p>
                    <a href="{{ route('announcements.create') }}"
                       class="mt-4 inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                        <i class="fas fa-plus"></i> Tambah Pengumuman
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-[2000] hidden items-center justify-center p-4 backdrop-blur-sm" style="background:rgba(0,0,0,0.4);">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                <i class="fas fa-trash text-red-600"></i>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Hapus Pengumuman?</h3>
            <p class="mt-1 text-sm text-slate-500">Pengumuman <span id="deleteTitle" class="font-medium text-slate-700"></span> akan dipindahkan ke sampah.</p>
            <div class="mt-5 flex justify-end gap-2">
                <button onclick="closeDeleteModal()"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                    Batal
                </button>
                <button onclick="submitDelete()"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                    <i class="fas fa-trash mr-1"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const main = document.querySelector('.main-content');
            const isOpening = !sidebar.classList.contains('active');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            if (window.innerWidth <= 768) {
                main.classList.toggle('blurred', isOpening);
                sessionStorage.setItem('sidebarOpen', isOpening ? '1' : '0');
            }
        }

        (function restoreSidebar() {
            if (window.innerWidth > 768) return;
            if (sessionStorage.getItem('sidebarOpen') !== '1') return;
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const main = document.querySelector('.main-content');
            sidebar.style.transition = 'none';
            sidebar.classList.add('active');
            overlay.classList.add('active');
            main.classList.add('blurred');
            requestAnimationFrame(() => { sidebar.style.transition = ''; });
        })();

        document.querySelectorAll('.sidebar a[href]').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript')) return;
                e.preventDefault();
                document.body.classList.add('page-leaving');
                setTimeout(() => { window.location.href = href; }, 180);
            });
        });
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('show');
            document.getElementById('chevron').classList.toggle('rotate');
        }
        document.addEventListener('click', function(e) {
            const ui = document.querySelector('.user-info');
            if (!ui.contains(e.target)) {
                document.getElementById('dropdownMenu').classList.remove('show');
                document.getElementById('chevron').classList.remove('rotate');
            }
        });

        let deleteTargetId = null;

        function openDeleteModal(id, title) {
            deleteTargetId = id;
            document.getElementById('deleteTitle').textContent = '"' + title + '"';
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            deleteTargetId = null;
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitDelete() {
            if (deleteTargetId) {
                document.getElementById('delete-form-' + deleteTargetId).submit();
            }
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>
</body>
</html>
