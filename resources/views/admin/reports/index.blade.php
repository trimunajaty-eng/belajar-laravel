<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehadiran - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; display: flex; justify-content: space-between; align-items: center; height: 64px; }
        .navbar h1 { font-size: 1.25rem; color: #1e293b; font-weight: 600; display: flex; align-items: center; }
        .hamburger { display: none; background: none; border: none; font-size: 1.5rem; color: #1e293b; cursor: pointer; padding: 0.5rem; margin-right: 0.5rem; }
        .user-info { position: relative; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: background 0.2s; }
        .user-info:hover { background: #f8fafc; }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #dc2626; color: white; display: flex; align-items: center; justify-content: center; font-weight: 500; font-size: 0.875rem; }
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
        .sidebar-overlay { position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 998; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .sidebar-overlay.active { opacity: 1; pointer-events: auto; }

        .main-content { margin-left: 256px; margin-top: 64px; padding: 2rem; transition: filter 0.3s ease; }
        .main-content.blurred { filter: blur(3px); pointer-events: none; }

        body { opacity: 0; animation: pageFadeIn 0.22s ease forwards; }
        @keyframes pageFadeIn { to { opacity: 1; } }
        .page-leaving { opacity: 0; transition: opacity 0.18s ease; }
        .welcome-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; }
        .welcome-card h2 { color: #1e293b; font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
        .welcome-card p { color: #64748b; font-size: 0.875rem; }
        
        .filter-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; }
        .filter-form { display: grid; grid-template-columns: 1fr 1fr 1.5fr auto auto; gap: 1rem; align-items: end; }
        .filter-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 0.875rem; color: #475569; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group select { padding: 0.625rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; color: #1e293b; background: #ffffff; transition: border-color 0.2s; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #dc2626; }
        .btn { padding: 0.625rem 1.25rem; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-primary { background: #dc2626; color: white; }
        .btn-primary:hover { background: #b91c1c; }
        .btn-success { background: #16a34a; color: white; }
        .btn-success:hover { background: #15803d; }
        
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; transition: box-shadow 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; margin-bottom: 1rem; }
        .stat-icon.present { background: #dcfce7; color: #16a34a; }
        .stat-icon.late { background: #fef3c7; color: #d97706; }
        .stat-icon.absent { background: #fecaca; color: #ef4444; }
        .stat-icon.hours { background: #dbeafe; color: #3b82f6; }
        .stat-number { font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.25rem; }
        .stat-label { color: #64748b; font-size: 0.875rem; font-weight: 500; }
        
        .card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; }
        .card h3 { color: #1e293b; margin-bottom: 1.5rem; font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        
        .table-wrapper { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
        .table th { background: #f8fafc; font-weight: 600; color: #475569; font-size: 0.875rem; }
        .table td { font-size: 0.875rem; color: #1e293b; }
        .table tbody tr:hover { background: #f8fafc; }
        
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; display: inline-block; }
        .status-present { background: #dcfce7; color: #166534; }
        .status-late { background: #fef3c7; color: #92400e; }
        .status-absent { background: #fecaca; color: #991b1b; }
        
        .progress-bar { width: 100%; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
        .progress-fill { height: 100%; background: #16a34a; transition: width 0.3s; border-radius: 4px; }
        .progress-fill.warning { background: #d97706; }
        .progress-fill.danger { background: #ef4444; }

        @media (max-width: 1024px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .filter-form { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .hamburger { display: block; }
            .user-name { display: none; }
            .sidebar { transform: translateX(-100%); will-change: transform; }
            .sidebar.active { transform: translateX(0); box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; padding: 1rem; }
            .welcome-card { padding: 1rem; margin-bottom: 1rem; }
            .welcome-card h2 { font-size: 1.125rem; }
            .filter-card { padding: 1rem; margin-bottom: 1rem; }
            .filter-form { grid-template-columns: 1fr 1fr; }
            .stats { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 1rem; }
            .stat-card { padding: 1rem; }
            .stat-number { font-size: 1.5rem; }
            .card { padding: 1rem; margin-bottom: 1rem; }
        }

        @media (max-width: 480px) {
            .filter-form { grid-template-columns: 1fr; }
            .stats { grid-template-columns: 1fr 1fr; gap: 0.5rem; }
            .stat-number { font-size: 1.25rem; }
            .stat-label { font-size: 0.75rem; }
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
            <li><a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="{{ route('reports.index') }}" class="active"><i class="fas fa-chart-bar"></i> Reports</a></li>
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
        <div class="welcome-card">
            <h2>Laporan Kehadiran</h2>
            <p>Lihat dan analisis data kehadiran dengan statistik lengkap</p>
        </div>

        <div class="filter-card">
            <form method="GET" action="{{ route('reports.index') }}" class="filter-form">
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" required>
                </div>
                <div class="form-group">
                    <label>Karyawan</label>
                    <select name="user_id">
                        <option value="">Semua Karyawan</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Terapkan
                    </button>
                </div>
                <div class="form-group">
                    <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Ekspor CSV
                    </a>
                </div>
            </form>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon present"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number">{{ $totalPresent }}</div>
                <div class="stat-label">Total Hadir</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon late"><i class="fas fa-clock"></i></div>
                <div class="stat-number">{{ $totalLate }}</div>
                <div class="stat-label">Total Terlambat</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon absent"><i class="fas fa-times-circle"></i></div>
                <div class="stat-number">{{ $totalAbsent }}</div>
                <div class="stat-label">Total Tidak Hadir</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon hours"><i class="fas fa-business-time"></i></div>
                <div class="stat-number">{{ number_format($totalWorkingHours, 1) }}</div>
                <div class="stat-label">Total Jam Kerja</div>
            </div>
        </div>

        @if($reportByEmployee->count() > 0)
        <div class="card">
            <h3><i class="fas fa-users"></i> Ringkasan per Karyawan</h3>
            <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Total Hari</th>
                        <th>Hadir</th>
                        <th>Terlambat</th>
                        <th>Tidak Hadir</th>
                        <th>Tingkat Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportByEmployee as $report)
                    <tr>
                        <td>
                            <strong>{{ $report['user']->name }}</strong>
                            <br><small style="color: #64748b;">{{ $report['user']->email }}</small>
                        </td>
                        <td>{{ $report['total_days'] }}</td>
                        <td><span class="status-badge status-present">{{ $report['present'] }}</span></td>
                        <td><span class="status-badge status-late">{{ $report['late'] }}</span></td>
                        <td><span class="status-badge status-absent">{{ $report['absent'] }}</span></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div class="progress-bar" style="flex: 1;">
                                    <div class="progress-fill {{ $report['attendance_rate'] >= 90 ? '' : ($report['attendance_rate'] >= 75 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $report['attendance_rate'] }}%"></div>
                                </div>
                                <span style="font-weight: 600;">{{ $report['attendance_rate'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endif

        <div class="card">
            <h3><i class="fas fa-list"></i> Rincian Data Kehadiran</h3>
            <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Jam Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        <td>
                            <strong>{{ $attendance->user->name }}</strong>
                            <br><small style="color: #64748b;">{{ $attendance->user->email }}</small>
                        </td>
                        <td>
                            @if($attendance->check_in)
                                {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i:s') }}
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance->check_out)
                                {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i:s') }}
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $attendance->status }}">
                                @if($attendance->status === 'present') Hadir
                                @elseif($attendance->status === 'late') Terlambat
                                @elseif($attendance->status === 'absent') Tidak Hadir
                                @else {{ ucfirst($attendance->status) }}
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($attendance->check_in && $attendance->check_out)
                                @php
                                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                    $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                                    $hours = $checkIn->diffInHours($checkOut);
                                    $minutes = $checkIn->diffInMinutes($checkOut) % 60;
                                @endphp
                                <strong>{{ $hours }}j {{ $minutes }}m</strong>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2rem;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <br>Tidak ada data kehadiran untuk periode yang dipilih
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');
            dropdown.classList.toggle('show');
            chevron.classList.toggle('rotate');
        }

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
