<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #334155; }
        
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .navbar h1 { font-size: 1.25rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; }
        .user-info { position: relative; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: background 0.2s; }
        .user-info:hover { background: #f8fafc; }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #16a34a; display: flex; align-items: center; justify-content: center; color: white; font-weight: 500; font-size: 0.875rem; }
        .user-name { font-weight: 500; }
        .chevron { transition: transform 0.3s; font-size: 0.75rem; color: #64748b; }
        .chevron.rotate { transform: rotate(180deg); }
        .dropdown-menu { position: absolute; top: 100%; right: 0; margin-top: 0.5rem; background: white; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s; }
        .dropdown-menu.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-menu form { margin: 0; }
        .dropdown-item { width: 100%; padding: 0.75rem 1rem; border: none; background: none; text-align: left; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.875rem; transition: background 0.2s; text-decoration: none; }
        .dropdown-item:hover { background: #f8fafc; }
        .dropdown-item i { color: #ef4444; }
        .dropdown-item:first-child i { color: #3b82f6; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 1.5rem; }
        .welcome-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .welcome-card h2 { color: #1e293b; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; word-break: break-word; }
        .welcome-card p { font-size: 0.875rem; }
        
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; }
        .card h3 { color: #1e293b; margin-bottom: 1rem; font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        
        .attendance-status { text-align: center; padding: 1.5rem; }
        .status-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .status-text { font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; }
        .status-time { color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem; }
        
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s; width: 100%; max-width: 200px; }
        .btn-success { background: #16a34a; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn:hover { transform: translateY(-1px); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        
        .announcement { background: #fef3c7; border: 1px solid #fbbf24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; word-break: break-word; }
        .announcement.meeting { background: #dbeafe; border-color: #3b82f6; }
        .announcement.urgent { background: #fecaca; border-color: #ef4444; }
        .announcement-title { font-weight: 600; margin-bottom: 0.5rem; font-size: 0.875rem; }
        .announcement-date { font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; }
        
        .attendance-history { max-height: 300px; overflow-y: auto; }
        .history-item { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem; gap: 1rem; }
        .history-item:last-child { border-bottom: none; }
        
        .toast { position: fixed; top: 2rem; right: 2rem; background: white; border: 1px solid #e2e8f0; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: none; z-index: 9999; animation: slideIn 0.3s ease; max-width: 90%; }
        .toast.show { display: flex; align-items: center; gap: 0.75rem; }
        .toast.success { border-left: 4px solid #16a34a; }
        .toast.error { border-left: 4px solid #ef4444; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        .loading-screen { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; display: flex; align-items: center; justify-content: center; z-index: 9999; transition: opacity 0.8s ease-in-out; }
        .loading-screen.hide { opacity: 0; pointer-events: none; }
        .spinner { width: 60px; height: 60px; border: 5px solid #f1f5f9; border-top-color: #1e293b; border-radius: 50%; animation: spin 3s ease-in-out infinite; box-shadow: 0 0 20px rgba(30, 41, 59, 0.1); }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .navbar { padding: 0.75rem; }
            .navbar h1 { font-size: 1rem; }
            .navbar h1 i { font-size: 1rem; margin-right: 0.25rem; }
            .user-name { display: none; }
            .user-avatar { width: 32px; height: 32px; font-size: 0.75rem; }
            .user-info { gap: 0.25rem; padding: 0.25rem; }
            .dropdown-menu { min-width: 150px; }
            
            .container { padding: 1rem; }
            
            .welcome-card { padding: 1rem; margin-bottom: 1rem; }
            .welcome-card h2 { font-size: 1rem; }
            .welcome-card p { font-size: 0.75rem; }
            
            .grid { grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem; }
            
            .card { padding: 1rem; }
            .card h3 { font-size: 1rem; margin-bottom: 0.75rem; }
            
            .attendance-status { padding: 1rem; }
            .status-icon { font-size: 2rem; margin-bottom: 0.75rem; }
            .status-text { font-size: 1rem; }
            .status-time { font-size: 0.75rem; }
            
            .btn { padding: 0.625rem 1rem; font-size: 0.75rem; max-width: 150px; }
            
            .announcement { padding: 0.75rem; margin-bottom: 0.75rem; }
            .announcement-title { font-size: 0.75rem; }
            .announcement-date { font-size: 0.7rem; }
            
            .history-item { flex-direction: column; align-items: flex-start; padding: 0.5rem 0; font-size: 0.75rem; gap: 0.25rem; }
            
            .toast { top: 1rem; right: 1rem; left: 1rem; padding: 0.75rem 1rem; font-size: 0.75rem; }
        }
        
        @media (max-width: 480px) {
            .navbar h1 { font-size: 0.875rem; }
            .navbar h1 i { display: none; }
            
            .welcome-card h2 { font-size: 0.875rem; }
            .welcome-card p { font-size: 0.7rem; }
            
            .status-icon { font-size: 1.75rem; }
            .status-text { font-size: 0.875rem; }
            
            .btn { padding: 0.5rem 0.75rem; font-size: 0.7rem; }
        }
    </style>
</head>
<body>
    <div class="loading-screen" id="loadingScreen">
        <div style="text-align: center;">
            <div class="spinner"></div>
            <p style="margin-top: 1.5rem; color: #1e293b; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.5px;">Loading Portal...</p>
        </div>
    </div>

    <nav class="navbar">
        <h1><i class="fas fa-user-clock" style="color: #16a34a; margin-right: 0.5rem;"></i>Employee Portal</h1>
        <div class="user-info" onclick="toggleDropdown()">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down chevron" id="chevron"></i>
            <div class="dropdown-menu" id="dropdownMenu" onclick="event.stopPropagation()">
                <a href="{{ route('employee.change-password') }}" class="dropdown-item">
                    <i class="fas fa-cog" style="color: #3b82f6;"></i>
                    <span>Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" onsubmit="showLogoutAnimation(event)">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h2>Welcome, {{ Auth::user()->name }}!</h2>
            <p>Today is {{ now()->format('l, F d, Y') }}</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3><i class="fas fa-clock"></i> Attendance Today</h3>
                <div class="attendance-status">
                    @if($todayAttendance)
                        @if($todayAttendance->check_out)
                            <div class="status-icon" style="color: #16a34a;"><i class="fas fa-check-circle"></i></div>
                            <div class="status-text" style="color: #16a34a;">Work Complete</div>
                            <div class="status-time">Check In: {{ $todayAttendance->check_in }}</div>
                            <div class="status-time">Check Out: {{ $todayAttendance->check_out }}</div>
                        @else
                            <div class="status-icon" style="color: #3b82f6;"><i class="fas fa-play-circle"></i></div>
                            <div class="status-text" style="color: #3b82f6;">Working</div>
                            <div class="status-time">Check In: {{ $todayAttendance->check_in }}</div>
                            <form method="POST" action="{{ route('attendance.checkout') }}" style="margin-top: 1rem;">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt"></i> Check Out
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="status-icon" style="color: #64748b;"><i class="fas fa-clock"></i></div>
                        <div class="status-text" style="color: #64748b;">Not Checked In</div>
                        <form method="POST" action="{{ route('attendance.checkin') }}" style="margin-top: 1rem;">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i> Check In
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <h3><i class="fas fa-bullhorn"></i> Announcements</h3>
                @forelse($announcements as $announcement)
                    <div class="announcement {{ $announcement->type }}">
                        <div class="announcement-title">{{ $announcement->title }}</div>
                        <div>{{ $announcement->content }}</div>
                        @if($announcement->meeting_date)
                            <div class="announcement-date">
                                <i class="fas fa-calendar"></i> {{ $announcement->meeting_date->format('M d, Y H:i') }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p style="color: #64748b;">No announcements at this time.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-history"></i> Recent Attendance</h3>
            <div class="attendance-history">
                @forelse($recentAttendance as $attendance)
                    <div class="history-item">
                        <div>
                            <strong>{{ $attendance->date->format('M d, Y') }}</strong>
                            <span style="color: #64748b;">- {{ ucfirst($attendance->status) }}</span>
                        </div>
                        <div>
                            @if($attendance->check_in)
                                In: {{ $attendance->check_in }}
                            @endif
                            @if($attendance->check_out)
                                | Out: {{ $attendance->check_out }}
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="color: #64748b;">No attendance records found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle" style="color: #16a34a; font-size: 1.25rem;"></i>
        <span id="toast-message"></span>
    </div>

    <div class="loading-screen" id="logoutScreen" style="display: none;">
        <div style="text-align: center;">
            <div class="spinner"></div>
            <p style="margin-top: 1.5rem; color: #1e293b; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.5px;">Logging out...</p>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loadingScreen').classList.add('hide');
                setTimeout(() => {
                    document.getElementById('loadingScreen').style.display = 'none';
                }, 800);
            }, 300);
        });

        function showLogoutAnimation(event) {
            event.preventDefault();
            const logoutScreen = document.getElementById('logoutScreen');
            logoutScreen.style.display = 'flex';
            setTimeout(() => {
                event.target.submit();
            }, 800);
        }

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

    @if(session('success'))
    <script>
        const toast = document.getElementById('toast');
        const message = document.getElementById('toast-message');
        message.textContent = '{{ session('success') }}';
        toast.classList.add('show', 'success');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    </script>
    @endif
</body>
</html>