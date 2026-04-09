<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sampah Pengumuman</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        .sidebar-overlay { display: none; position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 998; }
        .main-content { margin-left: 256px; margin-top: 64px; padding: 2rem; transition: margin-left 0.3s ease; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem; }
        .header h2 { font-size: 1.125rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem; }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-secondary { background: #64748b; color: white; }
        .btn-secondary:hover { background: #475569; }
        .btn-sm { padding: 0.4rem 0.875rem; font-size: 0.75rem; }
        .btn-restore { background: #10b981; color: white; }
        .btn-restore:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .trash-card { background: #fef9f9; border: 1px solid #fecaca; padding: 1.25rem; border-radius: 8px; margin-bottom: 1rem; opacity: 0.85; }
        .trash-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem; }
        .trash-title { font-size: 1rem; font-weight: 600; color: #1e293b; }
        .trash-meta { font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.75rem; display: flex; flex-wrap: wrap; gap: 1rem; }
        .trash-content { color: #64748b; font-size: 0.875rem; margin-bottom: 0.75rem; }
        .trash-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        @media (max-width: 768px) {
            .hamburger { display: block; }
            .user-name { display: none; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
            .sidebar-overlay.active { display: block; }
            .main-content { margin-left: 0; padding: 1rem; }
            .card { padding: 1rem; }
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
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="header">
                <h2><i class="fas fa-trash-alt"></i> Sampah Pengumuman</h2>
                <a href="{{ route('announcements.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            @forelse($trashed as $announcement)
                <div class="trash-card">
                    <div class="trash-header">
                        <div class="trash-title">{{ $announcement->title }}</div>
                    </div>
                    <div class="trash-meta">
                        <span><i class="fas fa-trash"></i> Dihapus: {{ $announcement->deleted_at->locale('id')->isoFormat('D MMMM Y HH:mm') }}</span>
                        @if($announcement->expired_at)
                            <span><i class="fas fa-hourglass-end"></i> Kedaluwarsa: {{ $announcement->expired_at->locale('id')->isoFormat('D MMMM Y HH:mm') }}</span>
                        @endif
                    </div>
                    <div class="trash-content">{{ Str::limit($announcement->content, 120) }}</div>
                    <div class="trash-actions">
                        <form method="POST" action="{{ route('announcements.restore', $announcement->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-restore">
                                <i class="fas fa-undo"></i> Pulihkan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('announcements.force-delete', $announcement->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus permanen? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="fas fa-times"></i> Hapus Permanen
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:3rem; color:#94a3b8;">
                    <i class="fas fa-trash-alt" style="font-size:3rem; margin-bottom:1rem; opacity:0.3;"></i>
                    <p>Sampah kosong.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }
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
    </script>
</body>
</html>
