<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 64px; }
        .navbar h1 { font-size: 1.5rem; font-weight: 600; color: #1e293b; }
        .user-info { display: flex; align-items: center; gap: 1rem; }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #3b82f6; display: flex; align-items: center; justify-content: center; color: white; font-weight: 500; font-size: 0.875rem; }
        .user-name { font-weight: 500; color: #475569; }
        .logout-btn { background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: background 0.2s; }
        .logout-btn:hover { background: #dc2626; }
        
        .sidebar { position: fixed; left: 0; top: 64px; width: 256px; height: calc(100vh - 64px); background: #ffffff; border-right: 1px solid #e2e8f0; padding: 1.5rem 0; overflow-y: auto; }
        .sidebar ul { list-style: none; padding: 0 1rem; }
        .sidebar li { margin-bottom: 0.25rem; }
        .sidebar a { text-decoration: none; color: #64748b; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
        .sidebar a:hover { background: #f1f5f9; color: #475569; }
        .sidebar a.active { background: #3b82f6; color: white; }
        .sidebar i { width: 18px; text-align: center; font-size: 0.875rem; }
        
        .main-content { margin-left: 256px; margin-top: 64px; padding: 2rem; }
        .welcome-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; }
        .welcome-card h2 { color: #1e293b; font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
        .welcome-card p { color: #64748b; }
        
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; transition: box-shadow 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-header { display: flex; justify-content: between; align-items: center; margin-bottom: 1rem; }
        .stat-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; }
        .stat-icon.users { background: #dbeafe; color: #3b82f6; }
        .stat-icon.orders { background: #dcfce7; color: #16a34a; }
        .stat-icon.revenue { background: #fef3c7; color: #d97706; }
        .stat-icon.performance { background: #f3e8ff; color: #9333ea; }
        .stat-number { font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.25rem; }
        .stat-label { color: #64748b; font-size: 0.875rem; font-weight: 500; }
        
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 12px; }
        .card h3 { color: #1e293b; margin-bottom: 1.5rem; font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        
        .chart-container { height: 240px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; margin-bottom: 1.5rem; }
        
        .activity-item { padding: 1rem 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.75rem; }
        .activity-item:last-child { border-bottom: none; }
        .activity-icon { width: 32px; height: 32px; border-radius: 6px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 0.875rem; }
        .activity-content { flex: 1; }
        .activity-title { font-weight: 500; color: #1e293b; font-size: 0.875rem; }
        .activity-time { color: #64748b; font-size: 0.75rem; margin-top: 0.125rem; }
        
        .action-btn { display: block; width: 100%; padding: 0.75rem 1rem; margin-bottom: 0.75rem; background: #3b82f6; color: white; text-decoration: none; text-align: center; border-radius: 8px; font-size: 0.875rem; font-weight: 500; transition: background 0.2s; }
        .action-btn:hover { background: #2563eb; }
        
        .system-info { background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: 8px; margin-top: 1.5rem; }
        .system-info h4 { color: #1e293b; margin-bottom: 1rem; font-size: 0.875rem; font-weight: 600; }
        .info-item { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; font-size: 0.875rem; }
        .info-label { color: #64748b; }
        .info-value { color: #1e293b; font-weight: 500; }
        .status-online { color: #16a34a; }
        .status-warning { color: #d97706; }
        
        .loading-screen { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; display: flex; align-items: center; justify-content: center; z-index: 9999; transition: opacity 0.8s ease-in-out; }
        .loading-screen.hide { opacity: 0; pointer-events: none; }
        .spinner { width: 60px; height: 60px; border: 5px solid #f1f5f9; border-top-color: #1e293b; border-radius: 50%; animation: spin 3s ease-in-out infinite; box-shadow: 0 0 20px rgba(30, 41, 59, 0.1); }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .content-grid { grid-template-columns: 1fr; }
            .stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="loading-screen" id="loadingScreen">
        <div style="text-align: center;">
            <div class="spinner"></div>
            <p style="margin-top: 1.5rem; color: #1e293b; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.5px;">Loading Dashboard...</p>
        </div>
    </div>

    <nav class="navbar">
        <h1><i class="fas fa-chart-line" style="color: #3b82f6; margin-right: 0.5rem;"></i>Dashboard</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;" onsubmit="showLogoutAnimation(event)">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>logout
                </button>
            </form>
        </div>
    </nav>

    <div class="sidebar">
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('users.index') }}" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="{{ route('work-settings.index') }}"><i class="fas fa-clock"></i> Work Schedule</a></li>
            <li><a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-card">
            <h2>Welcome back, {{ Auth::user()->name }}</h2>
            <p>Here's what's happening with your dashboard today.</p>
            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b;">Last login: {{ now()->format('M d, Y \a\t H:i') }}</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon users"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-number">2,847</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon orders"><i class="fas fa-shopping-bag"></i></div>
                </div>
                <div class="stat-number">1,234</div>
                <div class="stat-label">Orders Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon revenue"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <div class="stat-number">$45,678</div>
                <div class="stat-label">Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon performance"><i class="fas fa-chart-line"></i></div>
                </div>
                <div class="stat-number">98.5%</div>
                <div class="stat-label">Performance</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card">
                <h3><i class="fas fa-chart-area"></i> Analytics Overview</h3>
                <div class="chart-container">
                    <div style="text-align: center;">
                        <i class="fas fa-chart-line" style="font-size: 2rem; color: #64748b; margin-bottom: 0.5rem;"></i>
                        <div style="color: #64748b;">Chart visualization will appear here</div>
                    </div>
                </div>
                
                <h4 style="margin-bottom: 1rem; color: #1e293b; font-size: 1rem; font-weight: 600;">Recent Activity</h4>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                    <div class="activity-content">
                        <div class="activity-title">New user registered</div>
                        <div class="activity-time">2 minutes ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="activity-content">
                        <div class="activity-title">Order #2847 completed</div>
                        <div class="activity-time">15 minutes ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-database"></i></div>
                    <div class="activity-content">
                        <div class="activity-title">Database backup completed</div>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                <a href="#" class="action-btn">
                    <i class="fas fa-user-plus"></i> Add New User
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-plus-circle"></i> Create Content
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-file-export"></i> Export Data
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-cogs"></i> System Settings
                </a>
                
                <div class="system-info">
                    <h4><i class="fas fa-server"></i> System Status</h4>
                    <div class="info-item">
                        <span class="info-label">Server Status</span>
                        <span class="info-value status-online">Online</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Database</span>
                        <span class="info-value status-online">Connected</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Storage</span>
                        <span class="info-value status-warning">75% Used</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Memory</span>
                        <span class="info-value">3.2GB / 8GB</span>
                    </div>
                </div>
            </div>
        </div>
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
    </script>
</body>
</html>