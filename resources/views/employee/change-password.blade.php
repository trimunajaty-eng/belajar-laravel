<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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
        
        .container { max-width: 600px; margin: 2rem auto; padding: 1.5rem; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; padding: 2rem; border-radius: 12px; }
        .card h2 { color: #1e293b; font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569; font-size: 0.875rem; }
        .form-control { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; transition: border 0.2s; }
        .form-control:focus { outline: none; border-color: #16a34a; }
        
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #16a34a; color: white; }
        .btn-primary:hover { background: #15803d; }
        .btn-secondary { background: #64748b; color: white; margin-left: 0.5rem; text-decoration: none; display: inline-block; }
        .btn-secondary:hover { background: #475569; }
        
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .error { color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar { padding: 0.75rem; }
            .navbar h1 { font-size: 1rem; }
            .navbar h1 i { font-size: 1rem; margin-right: 0.25rem; }
            .user-name { display: none; }
            .user-avatar { width: 32px; height: 32px; font-size: 0.75rem; }
            .user-info { gap: 0.25rem; padding: 0.25rem; }
            .dropdown-menu { min-width: 150px; }
            
            .container { padding: 1rem; }
            .card { padding: 1rem; }
            .card h2 { font-size: 1rem; }
            .form-group { margin-bottom: 1rem; }
            .form-group label { font-size: 0.75rem; }
            .form-control { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
            .btn { padding: 0.625rem 1rem; font-size: 0.75rem; }
        }
        
        @media (max-width: 480px) {
            .navbar h1 { font-size: 0.875rem; }
            .navbar h1 i { display: none; }
        }
    </style>
</head>
<body>
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

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <h2><i class="fas fa-key"></i> Change Password</h2>
            
            <form method="POST" action="{{ route('employee.change-password.update') }}">
                @csrf
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                    @error('current_password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    @error('new_password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Password
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </form>
        </div>
    </div>

    <script>
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
