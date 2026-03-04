<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 64px; }
        .navbar h1 { font-size: 1.25rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; }
        .hamburger { display: none; background: none; border: none; font-size: 1.5rem; color: #1e293b; cursor: pointer; padding: 0.5rem; margin-right: 0.5rem; }
        .user-info { display: flex; align-items: center; gap: 1rem; }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; font-weight: 500; font-size: 0.875rem; }
        .user-name { font-weight: 500; color: #475569; }
        .logout-btn { background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: background 0.2s; }
        .logout-btn:hover { background: #dc2626; }
        
        .sidebar { position: fixed; left: 0; top: 64px; width: 256px; height: calc(100vh - 64px); background: #ffffff; border-right: 1px solid #e2e8f0; padding: 1.5rem 0; overflow-y: auto; transition: transform 0.3s ease; z-index: 999; }
        .sidebar ul { list-style: none; padding: 0 1rem; }
        .sidebar li { margin-bottom: 0.25rem; }
        .sidebar a { text-decoration: none; color: #64748b; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
        .sidebar a:hover { background: #f1f5f9; color: #475569; }
        .sidebar a.active { background: #dc2626; color: white; }
        .sidebar i { width: 18px; text-align: center; font-size: 0.875rem; }
        
        .sidebar-overlay { display: none; position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 998; }
        
        .main-content { margin-left: 256px; margin-top: 64px; padding: 2rem; transition: margin-left 0.3s ease; }
        .welcome-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; }
        .welcome-card h2 { color: #1e293b; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; word-break: break-word; }
        .welcome-card p { font-size: 0.875rem; }
        
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; transition: box-shadow 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-header { display: flex; justify-content: between; align-items: center; margin-bottom: 1rem; }
        .stat-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; }
        .stat-icon.total { background: #dbeafe; color: #3b82f6; }
        .stat-icon.present { background: #dcfce7; color: #16a34a; }
        .stat-icon.late { background: #fef3c7; color: #d97706; }
        .stat-icon.absent { background: #fecaca; color: #ef4444; }
        .stat-number { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 0.25rem; }
        .stat-label { color: #64748b; font-size: 0.875rem; font-weight: 500; }
        
        .card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; }
        .card h3 { color: #1e293b; margin-bottom: 1.5rem; font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        
        .table-wrapper { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #f1f5f9; }
        .table th { background: #f8fafc; font-weight: 600; color: #475569; font-size: 0.875rem; white-space: nowrap; }
        .table td { font-size: 0.875rem; }
        
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; white-space: nowrap; }
        .status-present { background: #dcfce7; color: #166534; }
        .status-late { background: #fef3c7; color: #92400e; }
        .status-absent { background: #fecaca; color: #991b1b; }
        
        .time-late { color: #ef4444; font-weight: 600; }
        .time-normal { color: #16a34a; }
        
        .work-settings { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .setting-item { text-align: center; padding: 1rem; background: #f8fafc; border-radius: 8px; }
        .setting-label { font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem; }
        .setting-time { font-size: 1.125rem; font-weight: 600; color: #1e293b; }
        
        /* Responsive Styles */
        @media (max-width: 1024px) {
            .stats { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        }
        
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
            
            .stats { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
            .stat-card { padding: 1rem; }
            .stat-icon { width: 32px; height: 32px; font-size: 0.875rem; }
            .stat-number { font-size: 1.25rem; }
            .stat-label { font-size: 0.75rem; }
            
            .welcome-card { padding: 1rem; margin-bottom: 1rem; }
            .welcome-card h2 { font-size: 1rem; }
            .welcome-card p { font-size: 0.75rem; }
            
            .work-settings { grid-template-columns: 1fr; gap: 0.75rem; }
            .setting-item { padding: 0.75rem; }
            .setting-label { font-size: 0.75rem; }
            .setting-time { font-size: 1rem; }
            
            .card { padding: 1rem; margin-bottom: 1rem; }
            .card h3 { font-size: 1rem; margin-bottom: 1rem; }
            
            .table th, .table td { padding: 0.5rem 0.25rem; font-size: 0.75rem; }
            .status-badge { padding: 0.25rem 0.5rem; font-size: 0.625rem; }
        }
        
        @media (max-width: 480px) {
            .navbar h1 { font-size: 0.875rem; }
            .navbar h1 i { display: none; }
            .user-info { gap: 0.5rem; }
            
            .stats { grid-template-columns: 1fr; gap: 0.75rem; }
            .stat-card { padding: 0.875rem; }
            
            .welcome-card h2 { font-size: 0.875rem; }
            .welcome-card p { font-size: 0.7rem; }
            
            .table { min-width: 500px; }
            .table th, .table td { padding: 0.5rem 0.25rem; font-size: 0.7rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <button class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1><i class="fas fa-chart-line" style="color: #dc2626; margin-right: 0.5rem;"></i>Admin Dashboard</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="{{ route('dashboard') }}" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="{{ route('work-settings.index') }}"><i class="fas fa-clock"></i> Work Schedule</a></li>
            <li><a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-card">
            <h2>Welcome back, {{ Auth::user()->name }}!</h2>
            <p>Today is {{ now()->format('l, F d, Y') }} - Monitor your team's attendance</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon total"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-number">{{ $totalEmployees }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon present"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="stat-number">{{ $presentToday }}</div>
                <div class="stat-label">Present Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon late"><i class="fas fa-clock"></i></div>
                </div>
                <div class="stat-number">{{ $lateToday }}</div>
                <div class="stat-label">Late Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon absent"><i class="fas fa-times-circle"></i></div>
                </div>
                <div class="stat-number">{{ $absentToday }}</div>
                <div class="stat-label">Absent Today</div>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-cog"></i> Work Time Settings</h3>
            <div class="work-settings">
                <div class="setting-item">
                    <div class="setting-label">Work Start Time</div>
                    <div class="setting-time">{{ $workSetting->work_start_time ? \Carbon\Carbon::parse($workSetting->work_start_time)->format('H:i') : '08:00' }}</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Late Threshold</div>
                    <div class="setting-time">{{ $workSetting->late_threshold ? \Carbon\Carbon::parse($workSetting->late_threshold)->format('H:i') : '09:00' }}</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Work End Time</div>
                    <div class="setting-time">{{ $workSetting->work_end_time ? \Carbon\Carbon::parse($workSetting->work_end_time)->format('H:i') : '17:00' }}</div>
                </div>
            </div>
            <div style="margin-top: 1rem;">
                <a href="{{ route('work-settings.index') }}" class="btn btn-primary" style="display: inline-block; padding: 0.5rem 1rem; background: #dc2626; color: white; text-decoration: none; border-radius: 6px; font-size: 0.875rem;">
                    <i class="fas fa-edit"></i> Edit Schedule
                </a>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-calendar-check"></i> Today's Attendance ({{ now()->format('M d, Y') }})</h3>
            <div class="table-wrapper">
                <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Working Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayAttendances as $attendance)
                    <tr>
                        <td>
                            <strong>{{ $attendance->user->name }}</strong>
                            <br><small style="color: #64748b;">{{ $attendance->user->email }}</small>
                        </td>
                        <td>
                            @if($attendance->check_in)
                                <span class="{{ $attendance->status === 'late' ? 'time-late' : 'time-normal' }}">
                                    {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}
                                    @if($attendance->status === 'late')
                                        <i class="fas fa-exclamation-triangle" style="margin-left: 0.25rem;"></i>
                                    @endif
                                </span>
                            @else
                                <span style="color: #64748b;">Not checked in</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance->check_out)
                                {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                            @else
                                <span style="color: #3b82f6; font-weight: 500;">
                                    <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                                    Still working
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $attendance->status }}">
                                @if($attendance->status === 'late')
                                    <i class="fas fa-clock"></i>
                                @elseif($attendance->status === 'present')
                                    <i class="fas fa-check"></i>
                                @endif
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td>
                            @if($attendance->check_in && $attendance->check_out)
                                @php
                                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                    $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                                    $hours = $checkOut->diffInHours($checkIn);
                                    $minutes = $checkOut->diffInMinutes($checkIn) % 60;
                                @endphp
                                <strong>{{ $hours }}h {{ $minutes }}m</strong>
                            @elseif($attendance->check_in)
                                @php
                                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                    $now = now();
                                    $hours = $now->diffInHours($checkIn);
                                    $minutes = $now->diffInMinutes($checkIn) % 60;
                                @endphp
                                <span style="color: #3b82f6;">{{ $hours }}h {{ $minutes }}m (ongoing)</span>
                            @else
                                <span style="color: #64748b;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748b; padding: 2rem;">
                            <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            No attendance records for today
                        </td>
                    </tr>
                    @endforelse
                    
                    @if($absentEmployees->count() > 0)
                        @foreach($absentEmployees as $employee)
                        <tr style="background: #fef2f2;">
                            <td>
                                <strong>{{ $employee->name }}</strong>
                                <br><small style="color: #64748b;">{{ $employee->email }}</small>
                            </td>
                            <td><span style="color: #ef4444;">Not checked in</span></td>
                            <td><span style="color: #64748b;">-</span></td>
                            <td>
                                <span class="status-badge status-absent">
                                    <i class="fas fa-times"></i> Absent
                                </span>
                            </td>
                            <td><span style="color: #64748b;">-</span></td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
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
    </script>
</body>
</html>