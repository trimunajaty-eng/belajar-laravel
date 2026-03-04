<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Reports - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem 2rem; position: fixed; top: 0; left: 0; right: 0; z-index: 100; display: flex; justify-content: space-between; align-items: center; height: 64px; }
        .navbar h1 { font-size: 1.5rem; color: #1e293b; font-weight: 600; }
        .user-info { display: flex; align-items: center; gap: 1rem; }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #dc2626; color: white; display: flex; align-items: center; justify-content: center; font-weight: 500; font-size: 0.875rem; }
        .logout-btn { background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; }
        
        .sidebar { position: fixed; left: 0; top: 64px; bottom: 0; width: 256px; background: #ffffff; border-right: 1px solid #e2e8f0; padding: 1.5rem 0; overflow-y: auto; }
        .sidebar ul { list-style: none; padding: 0 1rem; }
        .sidebar li { margin-bottom: 0.25rem; }
        .sidebar a { text-decoration: none; color: #64748b; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
        .sidebar a:hover { background: #f1f5f9; color: #475569; }
        .sidebar a.active { background: #dc2626; color: white; }
        .sidebar i { width: 18px; text-align: center; font-size: 0.875rem; }
        
        .main-content { margin-left: 256px; margin-top: 64px; padding: 2rem; }
        .welcome-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; }
        .welcome-card h2 { color: #1e293b; font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
        .welcome-card p { color: #64748b; font-size: 0.875rem; }
        
        .filter-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; }
        .filter-form { display: grid; grid-template-columns: 1fr 1fr 1.5fr auto auto; gap: 1rem; align-items: end; }
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
        
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #f1f5f9; }
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
    </style>
</head>
<body>
    <nav class="navbar">
        <h1><i class="fas fa-chart-line" style="color: #dc2626; margin-right: 0.5rem;"></i>Admin Dashboard</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="sidebar">
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="{{ route('work-settings.index') }}"><i class="fas fa-clock"></i> Work Schedule</a></li>
            <li><a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="{{ route('reports.index') }}" class="active"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-card">
            <h2>Attendance Reports</h2>
            <p>View and analyze attendance data with detailed statistics</p>
        </div>

        <div class="filter-card">
            <form method="GET" action="{{ route('reports.index') }}" class="filter-form">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" required>
                </div>
                <div class="form-group">
                    <label>Employee</label>
                    <select name="user_id">
                        <option value="">All Employees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <div class="form-group">
                    <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </form>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon present"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number">{{ $totalPresent }}</div>
                <div class="stat-label">Total Present</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon late"><i class="fas fa-clock"></i></div>
                <div class="stat-number">{{ $totalLate }}</div>
                <div class="stat-label">Total Late</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon absent"><i class="fas fa-times-circle"></i></div>
                <div class="stat-number">{{ $totalAbsent }}</div>
                <div class="stat-label">Total Absent</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon hours"><i class="fas fa-business-time"></i></div>
                <div class="stat-number">{{ number_format($totalWorkingHours, 1) }}</div>
                <div class="stat-label">Total Working Hours</div>
            </div>
        </div>

        @if($reportByEmployee->count() > 0)
        <div class="card">
            <h3><i class="fas fa-users"></i> Summary by Employee</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Total Days</th>
                        <th>Present</th>
                        <th>Late</th>
                        <th>Absent</th>
                        <th>Attendance Rate</th>
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
        @endif

        <div class="card">
            <h3><i class="fas fa-list"></i> Detailed Attendance Records</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Working Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
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
                                {{ ucfirst($attendance->status) }}
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
                                <strong>{{ $hours }}h {{ $minutes }}m</strong>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2rem;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <br>No attendance records found for the selected period
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
