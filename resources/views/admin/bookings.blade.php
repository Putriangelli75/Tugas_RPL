@extends('layouts.app')

@section('title', 'Kelola Booking - Admin SPLJ')
@section('app_sidebar', true)

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow-clean mb-3">Transaksi Sistem</p>
            <h1 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Kelola Seluruh Booking</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Verifikasi bukti pembayaran, pantau jadwal, dan kelola status booking.</p>
        </div>
        <a class="btn-outline-clean" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
    </div>

    <div class="mt-8" id="adminBookingsAlert"></div>

    <!-- Filter Status Tabs -->
    <div class="mt-8 flex flex-wrap gap-2 border-b border-slate-200 pb-3">
        <button class="px-4 py-2 text-sm font-semibold rounded-lg bg-emerald-950 text-white transition duration-200" id="btn-all" onclick="filterBookings('all')">Semua</button>
        <button class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-950 transition duration-200" id="btn-pending" onclick="filterBookings('pending')">Belum Bayar (Pending)</button>
        <button class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-950 transition duration-200" id="btn-menunggu_verifikasi" onclick="filterBookings('menunggu_verifikasi')">Menunggu Verifikasi</button>
        <button class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-950 transition duration-200" id="btn-disetujui" onclick="filterBookings('disetujui')">Disetujui (Lunas)</button>
        <button class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-950 transition duration-200" id="btn-ditolak" onclick="filterBookings('ditolak')">Ditolak</button>
    </div>

    <div class="surface mt-4 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-stone-100/60 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Pelanggan</th>
                        <th class="px-5 py-4">Lapangan</th>
                        <th class="px-5 py-4">Tanggal & Jam</th>
                        <th class="px-5 py-4">Total Harga</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Bukti Bayar</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200" id="bookingsTableBody">
                    <tr>
                        <td class="px-5 py-5 text-center text-slate-500" colspan="7">Memuat data booking...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Bukti Bayar Detail -->
    <div id="imageDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/85 backdrop-blur-xs p-4 animate-in fade-in duration-200">
        <div class="surface max-w-lg w-full p-6 shadow-2xl relative animate-in zoom-in-95 duration-200">
            <button class="absolute top-4 right-4 text-slate-400 hover:text-slate-650 text-2xl font-bold leading-none" onclick="closeImageDetailModal()">&times;</button>
            
            <h3 class="text-lg font-semibold text-slate-950">Verifikasi Bukti Pembayaran</h3>
            <p class="text-sm text-slate-500 mt-1" id="modalBookingUser">Bukti pembayaran dari pelanggan.</p>
            
            <div class="mt-4 border border-slate-200 rounded-lg p-2 bg-stone-50 flex items-center justify-center min-h-[200px]">
                <img id="detailImg" class="max-h-[350px] w-auto object-contain rounded" src="" alt="Bukti Pembayaran">
            </div>

            <div class="flex justify-between items-center gap-3 mt-6 border-t border-slate-100 pt-4" id="modalActions">
                <button class="btn-outline-clean" type="button" onclick="closeImageDetailModal()">Tutup</button>
                <div class="flex gap-2">
                    <button class="bg-red-50 text-red-700 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-semibold transition" id="rejectBtn">Tolak</button>
                    <button class="btn-brand" id="approveBtn">Setujui</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        requireAdmin();

        let allBookingsList = [];
        let activeFilter = 'all';

        loadAllBookings();

        async function loadAllBookings() {
            const response = await fetch('/api/admin/bookings', {
                headers: apiHeaders()
            });

            if (!response.ok) {
                showAlert('adminBookingsAlert', await getErrorMessage(response));
                return;
            }

            allBookingsList = await response.json();
            renderBookings();
        }

        function filterBookings(filter) {
            activeFilter = filter;
            
            // Toggle active styling on buttons
            const filterTypes = ['all', 'pending', 'menunggu_verifikasi', 'disetujui', 'ditolak'];
            filterTypes.forEach(type => {
                const btn = document.getElementById(`btn-${type}`);
                if (type === filter) {
                    btn.classList.add('bg-emerald-950', 'text-white');
                    btn.classList.remove('text-slate-600', 'hover:bg-slate-100');
                } else {
                    btn.classList.remove('bg-emerald-950', 'text-white');
                    btn.classList.add('text-slate-600', 'hover:bg-slate-100');
                }
            });

            renderBookings();
        }

        function renderBookings() {
            let filtered = allBookingsList;
            if (activeFilter !== 'all') {
                filtered = allBookingsList.filter(b => b.status === activeFilter);
            }

            if (!filtered.length) {
                document.getElementById('bookingsTableBody').innerHTML = `
                    <tr>
                        <td class="px-5 py-10 text-center text-slate-550" colspan="7">
                            Tidak ditemukan data booking dengan status "${activeFilter.replace('_', ' ')}".
                        </td>
                    </tr>
                `;
                return;
            }

            document.getElementById('bookingsTableBody').innerHTML = filtered.map(b => `
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-950">${escapeHtml(b.user?.name)}</div>
                        <div class="text-xs text-slate-550">${escapeHtml(b.user?.email)}</div>
                    </td>
                    <td class="px-5 py-4 font-semibold text-slate-900">${escapeHtml(b.lapangan?.nama_lapangan)}</td>
                    <td class="px-5 py-4">
                        <div>${escapeHtml(b.tanggal)}</div>
                        <div class="text-xs text-slate-500">${escapeHtml(b.jam_mulai.slice(0,5))} - ${escapeHtml(b.jam_selesai.slice(0,5))}</div>
                    </td>
                    <td class="px-5 py-4 font-bold text-slate-950">
                        ${formatRupiah(b.total_harga)}
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ${getStatusBadgeClass(b.status)}">
                            ${escapeHtml(b.status.replace('_', ' '))}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        ${b.pembayaran ? `
                            <button class="text-xs font-semibold text-emerald-905 hover:underline" onclick="openPaymentDetail(${b.id})">
                                Lihat Bukti
                            </button>
                        ` : '<span class="text-xs text-slate-400">Belum diupload</span>'}
                    </td>
                    <td class="px-5 py-4 text-right">
                        ${b.status === 'menunggu_verifikasi' ? `
                            <div class="flex justify-end gap-1.5">
                                <button class="text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition" onclick="verifyBookingAction(${b.id}, 'ditolak')">Tolak</button>
                                <button class="text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2 py-1 rounded transition" onclick="verifyBookingAction(${b.id}, 'disetujui')">Setujui</button>
                            </div>
                        ` : `
                            <span class="text-xs text-slate-400">-</span>
                        `}
                    </td>
                </tr>
            `).join('');
        }

        function getStatusBadgeClass(status) {
            switch(status) {
                case 'pending': return 'bg-amber-50 text-amber-800';
                case 'menunggu_verifikasi': return 'bg-blue-50 text-blue-900';
                case 'disetujui': return 'bg-emerald-50 text-emerald-900';
                case 'ditolak': return 'bg-red-50 text-red-800';
                default: return 'bg-slate-100 text-slate-700';
            }
        }

        function openPaymentDetail(bookingId) {
            const booking = allBookingsList.find(b => b.id === bookingId);
            if (!booking || !booking.pembayaran) return;

            document.getElementById('modalBookingUser').innerText = `Pembayaran dari ${escapeHtml(booking.user?.name)} untuk ${escapeHtml(booking.lapangan?.nama_lapangan)}`;
            document.getElementById('detailImg').src = `/bukti/${booking.pembayaran.bukti_bayar}`;

            const rejectBtn = document.getElementById('rejectBtn');
            const approveBtn = document.getElementById('approveBtn');

            if (booking.status === 'menunggu_verifikasi') {
                document.getElementById('modalActions').classList.remove('hidden');
                document.getElementById('modalActions').classList.add('flex');
                
                rejectBtn.onclick = () => {
                    closeImageDetailModal();
                    verifyBookingAction(booking.id, 'ditolak');
                };
                approveBtn.onclick = () => {
                    closeImageDetailModal();
                    verifyBookingAction(booking.id, 'disetujui');
                };
            } else {
                // If already verified, hide actions
                rejectBtn.onclick = null;
                approveBtn.onclick = null;
                // Just keep the close button visible
                document.getElementById('modalActions').classList.remove('flex');
                document.getElementById('modalActions').classList.add('hidden');
            }

            document.getElementById('imageDetailModal').classList.remove('hidden');
            document.getElementById('imageDetailModal').classList.add('flex');
        }

        function closeImageDetailModal() {
            document.getElementById('imageDetailModal').classList.add('hidden');
            document.getElementById('imageDetailModal').classList.remove('flex');
        }

        async function verifyBookingAction(id, status) {
            if (!confirm(`Apakah Anda yakin ingin mengubah status booking ini menjadi: ${status.toUpperCase()}?`)) {
                return;
            }

            const response = await fetch(`/api/booking/${id}/verifikasi`, {
                method: 'POST',
                headers: apiHeaders(),
                body: JSON.stringify({ status })
            });

            if (!response.ok) {
                showAlert('adminBookingsAlert', await getErrorMessage(response));
                return;
            }

            showAlert('adminBookingsAlert', `Booking berhasil diverifikasi sebagai: ${status.toUpperCase()}`, 'success');
            loadAllBookings();
        }
    </script>
@endpush
