<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 64px; }
        .navbar h1 { font-size: 1.25rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; }
        .user-name { font-weight: 500; color: #475569; }
        .user-info { position: relative; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: background 0.2s; }
        .user-info:hover { background: #f8fafc; }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; font-weight: 500; font-size: 0.875rem; }
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
        .card h3 { color: #1e293b; margin-bottom: 1.5rem; font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .header h2 { font-size: 1.125rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem; }
        
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #dc2626; color: white; }
        .btn-primary:hover { background: #b91c1c; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.75rem; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-delete { background: #ef4444; color: white; }
        
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        
        .announcement-card { background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; }
        .announcement-card.meeting { border-left: 4px solid #3b82f6; }
        .announcement-card.urgent { border-left: 4px solid #ef4444; }
        .announcement-card.general { border-left: 4px solid #fbbf24; }
        .announcement-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem; }
        .announcement-title { font-size: 1.125rem; font-weight: 600; color: #1e293b; }
        .announcement-type { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .type-general { background: #fef3c7; color: #92400e; }
        .type-meeting { background: #dbeafe; color: #1e40af; }
        .type-urgent { background: #fecaca; color: #991b1b; }
        .announcement-content { color: #475569; margin-bottom: 0.75rem; }
        .announcement-date { font-size: 0.875rem; color: #64748b; }
        .announcement-actions { display: flex; gap: 0.5rem; margin-top: 1rem; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar { padding: 0.75rem; }
            .navbar h1 { font-size: 1rem; }
            .navbar h1 i { font-size: 1rem; margin-right: 0.25rem; }
            .hamburger { display: block; }
            .user-name { display: none; }
            .user-avatar { width: 32px; height: 32px; font-size: 0.75rem; }
            .logout-btn { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
            .logout-btn span { display: none; }
            
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
            .sidebar-overlay.active { display: block; }
            
            .main-content { margin-left: 0; padding: 1rem; }
            
            .card { padding: 1rem; }
            .header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .header h2 { font-size: 1rem; }
            .btn { padding: 0.5rem 1rem; font-size: 0.75rem; width: 100%; text-align: center; }
            .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.7rem; }
            
            .announcement-card { padding: 1rem; }
            .announcement-header { flex-direction: column; gap: 0.5rem; }
            .announcement-title { font-size: 1rem; }
            .announcement-content { font-size: 0.875rem; }
            .announcement-actions { flex-wrap: wrap; }
        }
        
        @media (max-width: 480px) {
            .navbar h1 { font-size: 0.875rem; }
            .navbar h1 i { display: none; }
            .announcement-title { font-size: 0.875rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <button class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1><i class="fas fa-chart-line" style="color: #dc2626; margin-right: 0.5rem;"></i>Admin Dashboard</h1>
        <div class="user-info" onclick="toggleDropdown()">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down chevron" id="chevron"></i>
            <div class="dropdown-menu" id="dropdownMenu">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
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
            <li><a href="{{ route('admin.leave-requests.index') }}"><i class="fas fa-file-medical-alt"></i> Pengajuan</a></li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="header">
                <h2><i class="fas fa-bullhorn"></i> Daftar Pengumuman</h2>
                <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pengumuman
                </a>
            </div>

            @forelse($announcements as $announcement)
                <div class="announcement-card {{ $announcement->type }}">
                    <div class="announcement-header">
                        <div class="announcement-title">{{ $announcement->title }}</div>
                        <span class="announcement-type type-{{ $announcement->type }}">
                            @if($announcement->type === 'meeting')
                                <i class="fas fa-calendar"></i> Rapat
                            @elseif($announcement->type === 'urgent')
                                <i class="fas fa-exclamation-triangle"></i> Mendesak
                            @else
                                <i class="fas fa-info-circle"></i> Umum
                            @endif
                        </span>
                    </div>
                    <div class="announcement-content">{{ $announcement->content }}</div>
                    @if($announcement->meeting_date)
                        <div class="announcement-date">
                            <i class="fas fa-calendar-alt"></i> Tanggal Rapat: {{ \Carbon\Carbon::parse($announcement->meeting_date)->locale('id')->isoFormat('D MMMM Y HH:mm') }}
                        </div>
                    @endif
                    <div class="announcement-actions">
                        <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('announcements.destroy', $announcement->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Hapus pengumuman ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 3rem; color: #64748b;">
                    <i class="fas fa-bullhorn" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>Belum ada pengumuman. Buat pengumuman pertama Anda!</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');
            dropdown.classList.toggle('show');
            chevron.classList.toggle('rotate');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');
            
            if (!userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
                chevron.classList.remove('rotate');
            }
        });
    </script>
</body>
</html>
