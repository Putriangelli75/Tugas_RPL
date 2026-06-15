@extends('layouts.app')

@section('title', 'Admin Dashboard - SPLJ')
@section('app_sidebar', true)

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow-clean mb-3">Panel Admin</p>
            <h1 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Statistik SPLJ</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Overview sistem pemesanan lapangan Jakabaring secara real-time.</p>
        </div>
        <div class="flex gap-3">
            <a class="btn-outline-clean" href="{{ route('admin.lapangan') }}">Kelola Lapangan</a>
            <a class="btn-clean" href="{{ route('admin.bookings') }}">Daftar Booking</a>
        </div>
    </div>

    <div class="mt-8" id="adminAlert"></div>

    <!-- Stats Cards -->
    <div class="mt-8 grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        <div class="surface metric-card p-5">
            <p class="text-sm font-semibold text-slate-500 font-medium">Total Lapangan</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-950" id="totalLapangan">0</h2>
        </div>
        <div class="surface metric-card p-5">
            <p class="text-sm font-semibold text-slate-500 font-medium">Total Booking</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-950" id="totalBookings">0</h2>
        </div>
        <div class="surface metric-card p-5">
            <p class="text-sm font-semibold text-slate-500 font-medium font-bold text-emerald-800">Total Pendapatan</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-950 text-emerald-950" id="totalRevenue">Rp0</h2>
        </div>
        <div class="surface metric-card p-5">
            <p class="text-sm font-semibold text-slate-500 font-medium text-amber-800">Menunggu Verifikasi</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-950 text-amber-900 animate-pulse" id="pendingVerifications">0</h2>
        </div>
        <div class="surface metric-card p-5">
            <p class="text-sm font-semibold text-slate-500 font-medium">Total Pelanggan</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-950" id="totalUsers">0</h2>
        </div>
    </div>

    <!-- Verifikasi Pembayaran Cepat -->
    <div class="surface mt-8 p-5">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Membutuhkan Verifikasi</h2>
                <p class="text-sm text-slate-500 mt-0.5">Booking yang telah diupload buktinya dan menunggu persetujuan Anda.</p>
            </div>
            <span class="text-xs bg-amber-50 text-amber-800 font-semibold px-2.5 py-1 rounded-full" id="pendingBadge">0 Transaksi</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-stone-100/60 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Pelanggan</th>
                        <th class="px-5 py-4">Lapangan</th>
                        <th class="px-5 py-4">Tanggal & Jam</th>
                        <th class="px-5 py-4">Total Harga</th>
                        <th class="px-5 py-4">Bukti</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200" id="pendingBookingsBody">
                    <tr>
                        <td class="px-5 py-5 text-center text-slate-500" colspan="6">Memuat data verifikasi...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Bukti Bayar -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 backdrop-blur-xs p-4">
        <div class="max-w-2xl w-full p-4 relative flex flex-col items-center">
            <button class="absolute top-0 right-4 text-white hover:text-stone-200 text-4xl font-semibold leading-none z-50" onclick="closeImageModal()">&times;</button>
            <img id="modalImg" class="max-h-[80vh] w-auto rounded-lg shadow-2xl object-contain bg-white" src="" alt="Bukti Pembayaran">
            <p class="mt-4 text-white text-sm bg-slate-950/60 px-4 py-2 rounded-full" id="modalImgLabel"></p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        requireAdmin();

        loadAdminDashboard();

        async function loadAdminDashboard() {
            const [statsResponse, bookingsResponse] = await Promise.all([
                fetch('/api/admin/stats', {
                    headers: apiHeaders()
                }),
                fetch('/api/admin/bookings', {
                    headers: apiHeaders()
                })
            ]);

            if (!statsResponse.ok) {
                showAlert('adminAlert', await getErrorMessage(statsResponse));
                return;
            }

            if (!bookingsResponse.ok) {
                showAlert('adminAlert', await getErrorMessage(bookingsResponse));
                return;
            }

            const stats = await statsResponse.json();
            const bookings = await bookingsResponse.json();

            // Render stats
            document.getElementById('totalLapangan').innerText = stats.total_lapangan;
            document.getElementById('totalBookings').innerText = stats.total_bookings;
            document.getElementById('totalRevenue').innerText = formatRupiah(stats.total_revenue);
            document.getElementById('pendingVerifications').innerText = stats.pending_verifications;
            document.getElementById('pendingBadge').innerText = `${stats.pending_verifications} Transaksi`;
            document.getElementById('totalUsers').innerText = stats.total_users;

            // Filter pending verifications
            const pendingBookings = bookings.filter(b => b.status === 'menunggu_verifikasi');

            if (!pendingBookings.length) {
                document.getElementById('pendingBookingsBody').innerHTML = `
                    <tr>
                        <td class="px-5 py-10 text-center text-slate-500" colspan="6">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="text-3xl">🎉</span>
                                <p class="text-sm font-semibold text-slate-900">Semua bersih!</p>
                                <p class="text-xs text-slate-500">Tidak ada pembayaran booking yang menunggu verifikasi saat ini.</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            document.getElementById('pendingBookingsBody').innerHTML = pendingBookings.map(b => `
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-950">${escapeHtml(b.user?.name)}</div>
                        <div class="text-xs text-slate-550">${escapeHtml(b.user?.email)}</div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-900">${escapeHtml(b.lapangan?.nama_lapangan)}</div>
                        <div class="text-xs text-slate-550">${escapeHtml(b.lapangan?.jenis_olahraga)}</div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-slate-900">${escapeHtml(b.tanggal)}</div>
                        <div class="text-xs text-slate-500">${escapeHtml(b.jam_mulai.slice(0,5))} - ${escapeHtml(b.jam_selesai.slice(0,5))}</div>
                    </td>
                    <td class="px-5 py-4 font-bold text-slate-950">
                        ${formatRupiah(b.total_harga)}
                    </td>
                    <td class="px-5 py-4">
                        ${b.pembayaran ? `
                            <button class="text-xs font-semibold text-emerald-900 hover:underline flex items-center gap-1" onclick="viewImage('/bukti/${b.pembayaran.bukti_bayar}', 'Bukti ${escapeHtml(b.user?.name)}')">
                                Lihat Bukti
                            </button>
                        ` : '<span class="text-xs text-slate-400">Tidak ada</span>'}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-1 rounded text-xs font-semibold transition" onclick="verifyBooking(${b.id}, 'ditolak')">Tolak</button>
                            <button class="bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-3 py-1 rounded text-xs font-semibold transition" onclick="verifyBooking(${b.id}, 'disetujui')">Setujui</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function viewImage(url, label) {
            document.getElementById('modalImg').src = url;
            document.getElementById('modalImgLabel').innerText = label;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        }

        async function verifyBooking(id, status) {
            if (!confirm(`Apakah Anda yakin ingin memverifikasi booking ini sebagai: ${status.toUpperCase()}?`)) {
                return;
            }

            const response = await fetch(`/api/booking/${id}/verifikasi`, {
                method: 'POST',
                headers: apiHeaders(),
                body: JSON.stringify({ status })
            });

            if (!response.ok) {
                showAlert('adminAlert', await getErrorMessage(response));
                return;
            }

            showAlert('adminAlert', `Booking berhasil ${status === 'disetujui' ? 'disetujui' : 'ditolak'}.`, 'success');
            loadAdminDashboard();
        }
    </script>
@endpush
