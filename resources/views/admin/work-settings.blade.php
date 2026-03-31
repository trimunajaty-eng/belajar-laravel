<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Jadwal Kerja</title>
    <link href="/css/fontawesome/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow-x: hidden; }
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
        .card { background: #ffffff; border: 1px solid #e2e8f0; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; }
        .card h3 { color: #1e293b; margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
        .form-control { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; }
        .form-control:focus { outline: none; border-color: #dc2626; }
        
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #dc2626; color: white; }
        .btn-primary:hover { background: #b91c1c; }
        
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        
        .current-settings { background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
        .setting-item { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0; }
        .setting-item:last-child { border-bottom: none; }
        .setting-label { font-weight: 500; color: #374151; }
        .setting-value { font-weight: 600; color: #dc2626; }
        
        .info-box { background: #dbeafe; border: 1px solid #3b82f6; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .info-box i { color: #3b82f6; margin-right: 0.5rem; }
        
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
            .card h3 { font-size: 1rem; margin-bottom: 1rem; }
            
            .form-grid { grid-template-columns: 1fr; gap: 1rem; }
            .form-group { margin-bottom: 1rem; }
            .form-group label { font-size: 0.75rem; }
            .form-control { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
            .btn { padding: 0.625rem 1.25rem; font-size: 0.75rem; width: 100%; }
            
            .current-settings { padding: 1rem; margin-bottom: 1rem; }
            .current-settings h4 { font-size: 0.875rem; }
            .setting-item { padding: 0.5rem 0; font-size: 0.875rem; }
            .setting-label { font-size: 0.75rem; }
            .setting-value { font-size: 0.875rem; }
            
            .info-box { padding: 0.75rem; margin-bottom: 1rem; font-size: 0.75rem; }
        }
        
        @media (max-width: 480px) {
            .navbar h1 { font-size: 0.875rem; }
            .navbar h1 i { display: none; }
            .card h3 { font-size: 0.875rem; }
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
            <li><a href="{{ route('work-settings.index') }}" class="active"><i class="fas fa-clock"></i> Work Schedule</a></li>
            <li><a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
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
            <h3><i class="fas fa-clock"></i> Pengaturan Jadwal Kerja</h3>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <strong>Penting:</strong> Perubahan jadwal kerja akan langsung berlaku untuk semua karyawan. Batas keterlambatan menentukan kapan karyawan dianggap terlambat.
            </div>

            <div class="current-settings">
                <h4 style="margin-bottom: 1rem; color: #1e293b;">Jadwal Saat Ini</h4>
                <div class="setting-item">
                    <span class="setting-label">Jam Mulai Kerja</span>
                    <span class="setting-value">{{ $workSetting->work_start_time ? \Carbon\Carbon::parse($workSetting->work_start_time)->format('H:i') : '08:00' }}</span>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Batas Keterlambatan</span>
                    <span class="setting-value">{{ $workSetting->late_threshold ? \Carbon\Carbon::parse($workSetting->late_threshold)->format('H:i') : '09:00' }}</span>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Jam Selesai Kerja</span>
                    <span class="setting-value">{{ $workSetting->work_end_time ? \Carbon\Carbon::parse($workSetting->work_end_time)->format('H:i') : '17:00' }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('work-settings.store') }}">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="work_start_time">
                            <i class="fas fa-play-circle" style="color: #16a34a; margin-right: 0.25rem;"></i>
                            Jam Mulai Kerja
                        </label>
                        <input type="time" 
                               id="work_start_time" 
                               name="work_start_time" 
                               class="form-control" 
                               value="{{ $workSetting->work_start_time ? \Carbon\Carbon::parse($workSetting->work_start_time)->format('H:i') : '08:00' }}" 
                               required>
                        @error('work_start_time')
                            <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="late_threshold">
                            <i class="fas fa-exclamation-triangle" style="color: #d97706; margin-right: 0.25rem;"></i>
                            Batas Keterlambatan
                        </label>
                        <input type="time" 
                               id="late_threshold" 
                               name="late_threshold" 
                               class="form-control" 
                               value="{{ $workSetting->late_threshold ? \Carbon\Carbon::parse($workSetting->late_threshold)->format('H:i') : '09:00' }}" 
                               required>
                        @error('late_threshold')
                            <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                        <small style="color: #64748b; font-size: 0.75rem;">Karyawan yang absen setelah waktu ini akan ditandai terlambat</small>
                    </div>

                    <div class="form-group">
                        <label for="work_end_time">
                            <i class="fas fa-stop-circle" style="color: #ef4444; margin-right: 0.25rem;"></i>
                            Jam Selesai Kerja
                        </label>
                        <input type="time" 
                               id="work_end_time" 
                               name="work_end_time" 
                               class="form-control" 
                               value="{{ $workSetting->work_end_time ? \Carbon\Carbon::parse($workSetting->work_end_time)->format('H:i') : '17:00' }}" 
                               required>
                        @error('work_end_time')
                            <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Jadwal Kerja
                </button>
            </form>
        </div>

        <div class="card">
            <h3><i class="fas fa-info-circle"></i> Panduan Jadwal</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div style="padding: 1rem; background: #f0fdf4; border-radius: 8px; border-left: 4px solid #16a34a;">
                    <h4 style="color: #16a34a; margin-bottom: 0.5rem;">
                        <i class="fas fa-check-circle"></i> Tepat Waktu
                    </h4>
                    <p style="font-size: 0.875rem; color: #166534;">Karyawan yang absen sebelum batas keterlambatan</p>
                </div>
                <div style="padding: 1rem; background: #fffbeb; border-radius: 8px; border-left: 4px solid #d97706;">
                    <h4 style="color: #d97706; margin-bottom: 0.5rem;">
                        <i class="fas fa-clock"></i> Terlambat
                    </h4>
                    <p style="font-size: 0.875rem; color: #92400e;">Karyawan yang absen setelah batas keterlambatan</p>
                </div>
                <div style="padding: 1rem; background: #fef2f2; border-radius: 8px; border-left: 4px solid #ef4444;">
                    <h4 style="color: #ef4444; margin-bottom: 0.5rem;">
                        <i class="fas fa-times-circle"></i> Tidak Hadir
                    </h4>
                    <p style="font-size: 0.875rem; color: #991b1b;">Karyawan yang belum melakukan absen</p>
                </div>
            </div>
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