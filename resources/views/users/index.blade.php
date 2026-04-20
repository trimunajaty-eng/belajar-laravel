<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        @media (max-width: 768px) {
            .hamburger { display: block; }
            .user-name { display: none; }
            .sidebar { transform: translateX(-100%); will-change: transform; }
            .sidebar.active { transform: translateX(0); box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; padding: 1rem; }
        }
        @media (max-width: 480px) {
            .navbar h1 { font-size: 0.875rem; }
            .navbar h1 i { display: none; }
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
            <div class="dropdown-menu" id="dropdownMenu" onclick="event.stopPropagation()">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('users.index') }}" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="{{ route('work-settings.index') }}"><i class="fas fa-clock"></i> Work Schedule</a></li>
            <li><a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
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

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        {{-- Card --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">
                        <i class="fas fa-users mr-2 text-slate-400"></i>User Management
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Manage system users and their access</p>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Search --}}
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <input type="text" id="searchInput" placeholder="Search users…"
                               class="pl-8 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg w-44 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all placeholder-slate-400">
                    </div>
                    {{-- Create Button --}}
                    <a href="{{ route('users.create') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <i class="fas fa-plus text-xs"></i>
                        <span class="hidden sm:inline">Create User</span>
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]" id="usersTable">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors"
                            data-name="{{ strtolower($user->name) }}"
                            data-email="{{ strtolower($user->email) }}">

                            {{-- Name --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->profile_photo)
                                        <img src="{{ Storage::url($user->profile_photo) }}" alt="Foto"
                                             class="w-8 h-8 rounded-full object-cover flex-shrink-0 ring-2 ring-slate-100">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-slate-800">{{ $user->name }}</span>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $user->email }}</td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-500/10 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Created At --}}
                            <td class="px-6 py-4 text-sm text-slate-400">{{ $user->created_at->format('M d, Y') }}</td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">

                                    {{-- Toggle Status --}}
                                    <form method="POST" action="{{ route('users.toggle-status', $user) }}">
                                        @csrf
                                        @if($user->is_active)
                                            <button type="submit" title="Deactivate"
                                                    class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                                <i class="fas fa-ban text-sm"></i>
                                            </button>
                                        @else
                                            <button type="submit" title="Activate"
                                                    class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                                                <i class="fas fa-check-circle text-sm"></i>
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Edit --}}
                                    <a href="{{ route('users.edit', $user) }}" title="Edit"
                                       class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-pencil-alt text-sm"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                          onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <i class="fas fa-users text-3xl"></i>
                                    <p class="text-sm font-medium">No users found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
            @endif

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
        document.querySelectorAll('.sidebar a[href]').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript')) return;
                e.preventDefault();
                document.body.classList.add('page-leaving');
                setTimeout(() => { window.location.href = href; }, 180);
            });
        });
        document.getElementById('searchInput').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#usersTable tbody tr[data-name]').forEach(row => {
                row.style.display = (row.dataset.name.includes(q) || row.dataset.email.includes(q)) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
