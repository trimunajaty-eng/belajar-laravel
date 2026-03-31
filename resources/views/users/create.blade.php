<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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
        .btn { padding: 0.625rem 1.25rem; border-radius: 6px; text-decoration: none; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #64748b; color: white; margin-left: 0.5rem; }
        .btn-secondary:hover { background: #475569; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; max-width: 600px; }
        .card h2 { margin-bottom: 1.5rem; color: #1e293b; font-size: 1.25rem; font-weight: 600; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569; font-size: 0.875rem; }
        .form-control { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; transition: border 0.2s; }
        .form-control:focus { outline: none; border-color: #3b82f6; }
        .error { color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; }

        /* =========================
           UPDATE: Desktop logout pindah ke sidebar (SAMA seperti dashboard)
           ========================= */

        /* Desktop: chevron/dropdown dimatikan di navbar */
        @media (min-width: 769px) {
            .user-info { cursor: default; }
            .user-info .chevron { display: none; }
            .user-info:hover { background: transparent; }
            .dropdown-menu { display: none !important; }
        }

        /* Sidebar jadi flex agar bisa taruh logout di bawah */
        .sidebar {
            display: flex;
            flex-direction: column;
        }
        .sidebar ul { flex: 1; }

        .sidebar-logout {
            padding: 0 1rem;
            margin-top: auto;
        }
        .sidebar-logout .dropdown-item {
            width: 100%;
            text-decoration: none;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .sidebar-logout .dropdown-item:hover {
            background: #f1f5f9;
            color: #475569;
        }
        .sidebar-logout .dropdown-item i {
            width: 18px;
            text-align: center;
            color: #ef4444;
        }
        
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
            .card { padding: 1rem; max-width: 100%; }
            .card h2 { font-size: 1rem; }
            .form-group label { font-size: 0.75rem; }
            .form-control { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
            .btn { padding: 0.5rem 1rem; font-size: 0.75rem; }
        }
        
        @media (max-width: 480px) {
            .navbar h1 { font-size: 0.875rem; }
            .navbar h1 i { display: none; }
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

            <div class="dropdown-menu" id="dropdownMenu" onclick="event.stopPropagation()">
                <!-- SISTEM LOGOUT SAMA seperti dashboard: route('logout') + @csrf -->
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
            <li><a href="{{ route('users.index') }}" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="{{ route('work-settings.index') }}"><i class="fas fa-clock"></i> Work Schedule</a></li>
            <li><a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="{{ route('admin.leave-requests.index') }}"><i class="fas fa-file-medical-alt"></i> Pengajuan</a></li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>

        <!-- UPDATE: logout dipindahkan ke sini saat desktop -->
        <div class="sidebar-logout" id="sidebarLogout"></div>
    </div>

    <div class="main-content">
        <div class="card">
            <h2><i class="fas fa-user-plus"></i> Add New User</h2>
            
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="role" value="employee">

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create User
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
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
            // Desktop: dropdown tidak dipakai (logout pindah ke sidebar)
            if (window.innerWidth >= 769) return;

            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');
            dropdown.classList.toggle('show');
            chevron.classList.toggle('rotate');
        }

        // Pindahkan 1 form logout yang sama sesuai device (SAMA seperti dashboard)
        function relocateLogout() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            const sidebarLogout = document.getElementById('sidebarLogout');
            if (!dropdownMenu || !sidebarLogout) return;

            const logoutForm =
                dropdownMenu.querySelector('form') ||
                sidebarLogout.querySelector('form');

            if (!logoutForm) return;

            if (window.innerWidth >= 769) {
                // Desktop -> taruh di sidebar bawah
                if (!sidebarLogout.contains(logoutForm)) {
                    sidebarLogout.appendChild(logoutForm);
                }
                // Tutup dropdown jika sebelumnya terbuka saat resize dari mobile
                dropdownMenu.classList.remove('show');
                const chevron = document.getElementById('chevron');
                chevron && chevron.classList.remove('rotate');
            } else {
                // Mobile -> balik ke dropdown
                if (!dropdownMenu.contains(logoutForm)) {
                    dropdownMenu.appendChild(logoutForm);
                }
            }
        }

        document.addEventListener('click', function(event) {
            // Desktop: tidak ada dropdown
            if (window.innerWidth >= 769) return;

            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');

            if (!userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
                chevron.classList.remove('rotate');
            }
        });

        window.addEventListener('load', relocateLogout);
        window.addEventListener('resize', relocateLogout);
    </script>
</body>
</html>