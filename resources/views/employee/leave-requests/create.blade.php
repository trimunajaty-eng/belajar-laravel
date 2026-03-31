<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengajuan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-700">

    <nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <h1 class="flex items-center text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">
                <span class="mr-3 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                    <i class="fas fa-user-clock"></i>
                </span>
                Employee Portal
            </h1>

            <div class="relative flex items-center">
                <div onclick="toggleDropdown()" class="flex cursor-pointer items-center gap-2 rounded-xl border border-transparent px-2 py-2 transition hover:border-slate-200 hover:bg-slate-50 sm:gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-sm font-medium text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="hidden text-sm font-medium text-slate-700 sm:inline">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-300" id="chevron"></i>
                </div>

                <div id="dropdownMenu" class="invisible absolute right-0 top-full z-50 mt-3 w-52 translate-y-2 rounded-2xl border border-slate-200 bg-white p-2 opacity-0 shadow-xl transition-all duration-300">
                    <a href="{{ route('dashboard') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-home text-emerald-500"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('employee.change-password') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-cog text-blue-500"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('employee.leave-requests.index') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-file-medical-alt text-amber-500"></i>
                        <span>Pengajuan</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-sm text-slate-700 transition hover:bg-slate-50">
                            <i class="fas fa-sign-out-alt text-red-500"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('employee.leave-requests.index') }}" class="hover:text-emerald-600">Pengajuan Saya</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-slate-700">Buat Pengajuan</span>
        </div>

        <div class="mb-6 overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-700 to-indigo-600 p-6 text-white shadow-xl">
            <h2 class="text-xl font-bold tracking-tight sm:text-2xl">Buat Pengajuan Baru</h2>
            <p class="mt-1 text-sm text-slate-200">Isi form di bawah untuk mengajukan cuti, sakit, atau izin.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('employee.leave-requests.store') }}" class="space-y-6">
                @csrf

                {{-- Jenis Pengajuan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Jenis Pengajuan</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['cuti' => ['icon' => 'fa-umbrella-beach', 'color' => 'emerald', 'label' => 'Cuti'], 'sakit' => ['icon' => 'fa-notes-medical', 'color' => 'red', 'label' => 'Sakit'], 'izin' => ['icon' => 'fa-file-alt', 'color' => 'blue', 'label' => 'Izin']] as $value => $item)
                        <label class="type-card cursor-pointer">
                            <input type="radio" name="type" value="{{ $value }}" class="sr-only" {{ old('type') === $value ? 'checked' : '' }}>
                            <div class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-slate-200 p-4 text-center transition-all duration-200 hover:border-{{ $item['color'] }}-400 hover:bg-{{ $item['color'] }}-50 peer-checked:border-{{ $item['color'] }}-500 peer-checked:bg-{{ $item['color'] }}-50">
                                <i class="fas {{ $item['icon'] }} text-2xl text-{{ $item['color'] }}-500"></i>
                                <span class="text-sm font-medium text-slate-700">{{ $item['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date"
                            value="{{ old('start_date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </div>
                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-medium text-slate-700">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date"
                            value="{{ old('end_date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </div>
                </div>

                {{-- Durasi preview --}}
                <div id="duration-preview" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Durasi: <span id="duration-text" class="font-semibold"></span>
                </div>

                {{-- Alasan --}}
                <div>
                    <label for="reason" class="mb-2 block text-sm font-medium text-slate-700">Alasan / Keterangan</label>
                    <textarea id="reason" name="reason" rows="4"
                        placeholder="Tuliskan alasan pengajuan Anda secara jelas..."
                        class="w-full resize-none rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('reason') }}</textarea>
                    <p class="mt-1 text-right text-xs text-slate-400"><span id="char-count">0</span>/1000</p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('employee.leave-requests.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700">
                        <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');
            dropdown.classList.toggle('invisible');
            dropdown.classList.toggle('opacity-0');
            dropdown.classList.toggle('translate-y-2');
            chevron.classList.toggle('rotate-180');
        }

        document.addEventListener('click', function(e) {
            const nav = document.querySelector('.relative.flex.items-center');
            const dropdown = document.getElementById('dropdownMenu');
            if (!nav.contains(e.target)) {
                dropdown.classList.add('invisible', 'opacity-0', 'translate-y-2');
                document.getElementById('chevron').classList.remove('rotate-180');
            }
        });

        // Type card selection highlight
        document.querySelectorAll('.type-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.type-card div').forEach(div => {
                    div.classList.remove('border-emerald-500', 'bg-emerald-50', 'border-red-500', 'bg-red-50', 'border-blue-500', 'bg-blue-50');
                    div.classList.add('border-slate-200');
                });
                const colors = { cuti: ['border-emerald-500', 'bg-emerald-50'], sakit: ['border-red-500', 'bg-red-50'], izin: ['border-blue-500', 'bg-blue-50'] };
                const card = this.closest('.type-card').querySelector('div');
                card.classList.remove('border-slate-200');
                card.classList.add(...colors[this.value]);
            });
        });

        // Duration preview
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        function updateDuration() {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            const preview = document.getElementById('duration-preview');
            const text = document.getElementById('duration-text');

            if (startInput.value && endInput.value && end >= start) {
                const days = Math.round((end - start) / (1000 * 60 * 60 * 24)) + 1;
                text.textContent = days + ' hari';
                preview.classList.remove('hidden');
                endInput.min = startInput.value;
            } else {
                preview.classList.add('hidden');
            }
        }

        startInput.addEventListener('change', function() {
            endInput.min = this.value;
            updateDuration();
        });
        endInput.addEventListener('change', updateDuration);

        // Char counter
        const reasonInput = document.getElementById('reason');
        const charCount = document.getElementById('char-count');
        reasonInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            charCount.classList.toggle('text-red-500', this.value.length > 900);
        });

        // Restore selected type on validation error
        const oldType = '{{ old('type') }}';
        if (oldType) {
            const radio = document.querySelector(`input[value="${oldType}"]`);
            if (radio) radio.dispatchEvent(new Event('change'));
        }
    </script>

    <script>
    const POLL_INTERVAL = 10000;
    let shownIds = new Set();

    function getIcon(status) {
        return status === 'approved'
            ? '<i class="fas fa-check-circle" style="color:#16a34a;font-size:1.25rem;"></i>'
            : '<i class="fas fa-times-circle" style="color:#ef4444;font-size:1.25rem;"></i>';
    }

    function showNotifToast(notif) {
        const data    = notif.data;
        const wrapper = document.createElement('div');
        wrapper.style.cssText = [
            'position:fixed','top:1.25rem','right:1.25rem','z-index:9999',
            'display:flex','align-items:flex-start','gap:0.75rem',
            'background:white','border:1px solid #e2e8f0',
            'border-radius:1rem','padding:1rem 1.25rem',
            'box-shadow:0 10px 25px rgba(0,0,0,0.12)',
            'max-width:340px','width:90%',
            'animation:slideIn 0.3s ease',
        ].join(';');
        wrapper.innerHTML = `
            ${getIcon(data.status)}
            <div style="flex:1;">
                <div style="font-size:0.875rem;font-weight:600;color:#1e293b;margin-bottom:0.2rem;">
                    Pengajuan ${data.type.charAt(0).toUpperCase()+data.type.slice(1)}
                    ${data.status === 'approved' ? '<span style="color:#16a34a;">Disetujui</span>' : '<span style="color:#ef4444;">Ditolak</span>'}
                </div>
                <div style="font-size:0.8rem;color:#64748b;">${data.message}</div>
                ${data.admin_note ? `<div style="font-size:0.75rem;color:#94a3b8;margin-top:0.3rem;"><i class="fas fa-reply"></i> ${data.admin_note}</div>` : ''}
                <div style="font-size:0.7rem;color:#cbd5e1;margin-top:0.3rem;">${notif.created}</div>
            </div>
            <button onclick="dismissToast(this,'${notif.id}')" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1rem;padding:0;line-height:1;">&times;</button>
        `;
        document.body.appendChild(wrapper);
        setTimeout(() => dismissToast(wrapper.querySelector('button'), notif.id), 8000);
    }

    function dismissToast(btn, id) {
        const el = btn.closest ? btn.closest('div[style]') : btn.parentElement.parentElement;
        if (!el) return;
        el.style.opacity = '0';
        el.style.transform = 'translateX(100%)';
        el.style.transition = 'all 0.3s ease';
        setTimeout(() => el.remove(), 300);
        fetch(`/api/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        });
    }

    async function pollNotifications() {
        try {
            const res  = await fetch('/api/notifications/unread');
            const list = await res.json();
            list.forEach(notif => {
                if (!shownIds.has(notif.id)) {
                    shownIds.add(notif.id);
                    showNotifToast(notif);
                }
            });
        } catch(e) {}
    }

    pollNotifications();
    setInterval(pollNotifications, POLL_INTERVAL);
    </script>

    <style>
    @keyframes slideIn {
        from { opacity:0; transform:translateX(100%); }
        to   { opacity:1; transform:translateX(0); }
    }
    </style>
</body>
</html>
