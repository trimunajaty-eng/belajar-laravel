<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Pengajuan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }

        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 64px; }
        .navbar h1 { font-size: 1.25rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; }
        .hamburger { display: none; background: none; border: none; font-size: 1.5rem; color: #1e293b; cursor: pointer; padding: 0.5rem; margin-right: 0.5rem; }
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
        .sidebar-overlay { position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 998; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .sidebar-overlay.active { opacity: 1; pointer-events: auto; }

        .badge { display: inline-flex; align-items: center; justify-content: center; background: #dc2626; color: white; font-size: 0.65rem; font-weight: 700; border-radius: 9999px; min-width: 18px; height: 18px; padding: 0 5px; margin-left: auto; }

        .main-content { margin-left: 256px; margin-top: 64px; padding: 2rem; transition: filter 0.3s ease; }
        .main-content.blurred { filter: blur(3px); pointer-events: none; }

        body { opacity: 0; animation: pageFadeIn 0.22s ease forwards; }
        @keyframes pageFadeIn { to { opacity: 1; } }
        .page-leaving { opacity: 0; transition: opacity 0.18s ease; }

        .page-header { margin-bottom: 1.5rem; }
        .page-header h2 { font-size: 1.25rem; font-weight: 600; color: #1e293b; }
        .page-header p { font-size: 0.875rem; color: #64748b; margin-top: 0.25rem; }

        .filter-bar { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .filter-btn { padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 500; cursor: pointer; border: 1px solid #e2e8f0; background: white; color: #64748b; text-decoration: none; transition: all 0.2s; }
        .filter-btn:hover { background: #f1f5f9; }
        .filter-btn.active { background: #1e293b; color: white; border-color: #1e293b; }
        .filter-btn.pending  { border-color: #f59e0b; color: #92400e; }
        .filter-btn.pending.active  { background: #f59e0b; color: white; border-color: #f59e0b; }
        .filter-btn.approved { border-color: #16a34a; color: #166534; }
        .filter-btn.approved.active { background: #16a34a; color: white; border-color: #16a34a; }
        .filter-btn.rejected { border-color: #ef4444; color: #991b1b; }
        .filter-btn.rejected.active { background: #ef4444; color: white; border-color: #ef4444; }

        .alert { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.875rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }

        .table-wrapper { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; min-width: 700px; }
        .table th { background: #f8fafc; padding: 0.75rem 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .table td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem; vertical-align: top; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover { background: #f8fafc; }

        .status-badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; white-space: nowrap; }
        .status-pending  { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fecaca; color: #991b1b; }

        .type-badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500; }
        .type-cuti  { background: #d1fae5; color: #065f46; }
        .type-sakit { background: #fee2e2; color: #991b1b; }
        .type-izin  { background: #dbeafe; color: #1e40af; }

        .btn { padding: 0.4rem 0.875rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.35rem; }
        .btn-approve { background: #16a34a; color: white; }
        .btn-approve:hover { background: #15803d; }
        .btn-reject  { background: #ef4444; color: white; }
        .btn-reject:hover  { background: #dc2626; }

        .empty-state { text-align: center; padding: 4rem 2rem; color: #94a3b8; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; display: block; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.show { display: flex; }
        .modal { background: white; border-radius: 12px; padding: 1.5rem; width: 100%; max-width: 440px; }
        .modal h3 { font-size: 1rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; }
        .modal p { font-size: 0.875rem; color: #64748b; margin-bottom: 1.25rem; }
        .modal textarea { width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.625rem 0.875rem; font-size: 0.875rem; resize: none; outline: none; transition: border 0.2s; }
        .modal textarea:focus { border-color: #3b82f6; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem; }
        .btn-cancel { background: #f1f5f9; color: #475569; }
        .btn-cancel:hover { background: #e2e8f0; }

        /* Pagination */
        .pagination { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; padding: 1.25rem; }
        .page-link { padding: 0.4rem 0.75rem; border-radius: 6px; border: 1px solid #e2e8f0; background: white; color: #475569; font-size: 0.8rem; text-decoration: none; transition: all 0.2s; }
        .page-link:hover { border-color: #dc2626; color: #dc2626; }
        .page-link.active { background: #dc2626; border-color: #dc2626; color: white; }
        .page-link.disabled { opacity: 0.4; pointer-events: none; }

        @media (max-width: 768px) {
            .hamburger { display: block; }
            .user-name { display: none; }
            .sidebar { transform: translateX(-100%); will-change: transform; }
            .sidebar.active { transform: translateX(0); box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; padding: 1rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <h1><i class="fas fa-chart-line" style="color:#dc2626;margin-right:0.5rem;"></i>Admin Dashboard</h1>
        <div class="user-info" onclick="toggleDropdown()">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down chevron" id="chevron"></i>
            <div class="dropdown-menu" id="dropdownMenu">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
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
            <li><a href="{{ route('reports.index') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li>
                <a href="{{ route('admin.leave-requests.index') }}" class="active">
                    <i class="fas fa-file-medical-alt"></i> Pengajuan
                    @if($pendingCount > 0)
                        <span class="badge">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-file-medical-alt" style="color:#dc2626;margin-right:0.5rem;"></i>Kelola Pengajuan</h2>
            <p>Tinjau dan konfirmasi pengajuan cuti, sakit, dan izin karyawan.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        {{-- Filter --}}
        <div class="filter-bar">
            <a href="{{ route('admin.leave-requests.index') }}"
               class="filter-btn {{ !request('status') ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('admin.leave-requests.index', ['status' => 'pending']) }}"
               class="filter-btn pending {{ request('status') === 'pending' ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Menunggu
                @if($pendingCount > 0)
                    <span style="background:#f59e0b;color:white;border-radius:9999px;padding:0 6px;font-size:0.7rem;font-weight:700;margin-left:4px;">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.leave-requests.index', ['status' => 'approved']) }}"
               class="filter-btn approved {{ request('status') === 'approved' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i> Disetujui
            </a>
            <a href="{{ route('admin.leave-requests.index', ['status' => 'rejected']) }}"
               class="filter-btn rejected {{ request('status') === 'rejected' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i> Ditolak
            </a>
        </div>

        <div class="card">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Durasi</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $lr)
                            @php
                                $duration = $lr->start_date->diffInDays($lr->end_date) + 1;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $lr->user->name }}</strong>
                                    <br><small style="color:#64748b;">{{ $lr->user->email }}</small>
                                    <br><small style="color:#94a3b8;">{{ $lr->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}</small>
                                </td>
                                <td>
                                    <span class="type-badge type-{{ $lr->type }}">
                                        @if($lr->type === 'cuti') <i class="fas fa-umbrella-beach"></i> Cuti
                                        @elseif($lr->type === 'sakit') <i class="fas fa-notes-medical"></i> Sakit
                                        @else <i class="fas fa-file-alt"></i> Izin
                                        @endif
                                    </span>
                                </td>
                                <td style="white-space:nowrap;">
                                    {{ $lr->start_date->locale('id')->isoFormat('D MMM Y') }}
                                    @if($lr->start_date != $lr->end_date)
                                        <br>{{ $lr->end_date->locale('id')->isoFormat('D MMM Y') }}
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">{{ $duration }} hari</td>
                                <td style="max-width:200px;">
                                    {{ Str::limit($lr->reason, 80) }}
                                    @if($lr->admin_note)
                                        <div style="margin-top:0.4rem;padding:0.4rem 0.6rem;background:#f1f5f9;border-radius:6px;font-size:0.75rem;color:#475569;">
                                            <i class="fas fa-reply" style="color:#64748b;"></i> {{ $lr->admin_note }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $lr->status }}">
                                        @if($lr->status === 'pending') <i class="fas fa-clock"></i> Menunggu
                                        @elseif($lr->status === 'approved') <i class="fas fa-check-circle"></i> Disetujui
                                        @else <i class="fas fa-times-circle"></i> Ditolak
                                        @endif
                                    </span>
                                    @if($lr->reviewed_at)
                                        <div style="font-size:0.7rem;color:#94a3b8;margin-top:0.25rem;">
                                            {{ $lr->reviewed_at->locale('id')->isoFormat('D MMM Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">
                                    @if($lr->status === 'pending')
                                        <button onclick="openModal('approve', {{ $lr->id }}, '{{ $lr->user->name }}')"
                                            class="btn btn-approve">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button onclick="openModal('reject', {{ $lr->id }}, '{{ $lr->user->name }}')"
                                            class="btn btn-reject" style="margin-top:0.35rem;">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    @else
                                        <span style="font-size:0.75rem;color:#94a3b8;">Sudah ditinjau</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Tidak ada pengajuan{{ request('status') ? ' dengan status ini' : '' }}.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($leaveRequests->hasPages())
                <div class="pagination">
                    @if($leaveRequests->onFirstPage())
                        <span class="page-link disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $leaveRequests->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @for($p = 1; $p <= $leaveRequests->lastPage(); $p++)
                        <a href="{{ $leaveRequests->url($p) }}"
                           class="page-link {{ $p == $leaveRequests->currentPage() ? 'active' : '' }}">{{ $p }}</a>
                    @endfor

                    @if($leaveRequests->hasMorePages())
                        <a href="{{ $leaveRequests->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-link disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Approve --}}
    <div class="modal-overlay" id="modalApprove">
        <div class="modal">
            <h3><i class="fas fa-check-circle" style="color:#16a34a;margin-right:0.5rem;"></i>Setujui Pengajuan</h3>
            <p id="approveDesc">Anda akan menyetujui pengajuan ini.</p>
            <form method="POST" id="formApprove">
                <input type="hidden" name="_token" id="csrfApprove" value="{{ csrf_token() }}">
                <label style="font-size:0.8rem;font-weight:500;color:#475569;display:block;margin-bottom:0.4rem;">Catatan (opsional)</label>
                <textarea name="admin_note" rows="3" placeholder="Tambahkan catatan untuk karyawan..."></textarea>
                <div class="modal-actions">
                    <button type="button" onclick="closeModal('approve')" class="btn btn-cancel">Batal</button>
                    <button type="submit" class="btn btn-approve"><i class="fas fa-check"></i> Setujui</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Reject --}}
    <div class="modal-overlay" id="modalReject">
        <div class="modal">
            <h3><i class="fas fa-times-circle" style="color:#ef4444;margin-right:0.5rem;"></i>Tolak Pengajuan</h3>
            <p id="rejectDesc">Anda akan menolak pengajuan ini.</p>
            <form method="POST" id="formReject">
                <input type="hidden" name="_token" id="csrfReject" value="{{ csrf_token() }}">
                <label style="font-size:0.8rem;font-weight:500;color:#475569;display:block;margin-bottom:0.4rem;">Alasan penolakan (opsional)</label>
                <textarea name="admin_note" rows="3" placeholder="Tuliskan alasan penolakan..."></textarea>
                <div class="modal-actions">
                    <button type="button" onclick="closeModal('reject')" class="btn btn-cancel">Batal</button>
                    <button type="submit" class="btn btn-reject"><i class="fas fa-times"></i> Tolak</button>
                </div>
            </form>
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

        async function refreshCsrf() {
            try {
                const res  = await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' });
                const html = await fetch(window.location.href, { credentials: 'same-origin' });
                const text = await html.text();
                const match = text.match(/name="_token"\s+value="([^"]+)"/);
                if (match) {
                    const token = match[1];
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', token);
                    document.getElementById('csrfApprove').value = token;
                    document.getElementById('csrfReject').value  = token;
                }
            } catch(e) {}
        }

        function openModal(type, id, name) {
            refreshCsrf();
            if (type === 'approve') {
                document.getElementById('approveDesc').textContent = 'Anda akan menyetujui pengajuan dari ' + name + '.';
                document.getElementById('formApprove').action = '/admin/leave-requests/' + id + '/approve';
                document.getElementById('modalApprove').classList.add('show');
            } else {
                document.getElementById('rejectDesc').textContent = 'Anda akan menolak pengajuan dari ' + name + '.';
                document.getElementById('formReject').action = '/admin/leave-requests/' + id + '/reject';
                document.getElementById('modalReject').classList.add('show');
            }
        }

        function closeModal(type) {
            document.getElementById(type === 'approve' ? 'modalApprove' : 'modalReject').classList.remove('show');
        }

        // Tutup modal klik di luar
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('show');
            });
        });
    </script>
</body>
</html>
