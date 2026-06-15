@extends('layouts.app')

@section('title', 'Dashboard - SPLJ')
@section('app_sidebar', true)

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow-clean mb-3">Dashboard</p>
            <h1 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Selamat Datang, <span id="userName">User</span>!</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Pantau pemesanan Anda dan status ketersediaan lapangan terbaru.</p>
        </div>
        <a class="btn-clean animate-pulse" href="{{ route('lapangan.index') }}">Pesan Lapangan Sekarang</a>
    </div>

    <div class="mt-8" id="dashboardAlert"></div>

    <!-- Stats Cards -->
    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="surface metric-card p-5 hover:shadow-md transition duration-350">
            <p class="text-sm font-semibold text-slate-500">Total Lapangan</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-950" id="jumlahLapangan">0</h2>
        </div>
        <div class="surface metric-card p-5 hover:shadow-md transition duration-350">
            <p class="text-sm font-semibold text-slate-500">Tersedia</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-950" id="lapanganTersedia">0</h2>
        </div>
        <div class="surface metric-card p-5 hover:shadow-md transition duration-350">
            <p class="text-sm font-semibold text-slate-500">Maintenance</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-950" id="lapanganMaintenance">0</h2>
        </div>
        <div class="surface metric-card p-5 hover:shadow-md transition duration-350">
            <p class="text-sm font-semibold text-slate-500">Booking Saya</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-950" id="jumlahBooking">0</h2>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        <!-- Lapangan Preview -->
        <div class="surface p-5">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-slate-950">Lapangan Terbaru</h2>
                <span class="text-xs bg-stone-100 text-stone-700 px-2 py-1 rounded">Real-time</span>
            </div>
            <div class="mt-5 grid gap-4" id="lapanganPreview"></div>
        </div>

        <!-- Riwayat Booking -->
        <div class="surface p-5">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-slate-950">Riwayat Booking</h2>
                <a class="text-sm font-semibold text-emerald-900 hover:underline hover:text-emerald-850" href="{{ route('lapangan.index') }}">Buat booking baru &rarr;</a>
            </div>
            <div class="mt-5 grid gap-3" id="bookingHistory">
                <p class="rounded-lg border border-slate-200 p-4 text-sm text-slate-500">Memuat riwayat...</p>
            </div>
        </div>
    </div>

    <!-- Modal Upload Bukti Bayar -->
    <div id="uploadModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 backdrop-blur-xs p-4">
        <div class="surface max-w-md w-full p-6 shadow-xl relative animate-in fade-in zoom-in-95 duration-200">
            <button class="absolute top-4 right-4 text-slate-400 hover:text-slate-650 text-2xl font-semibold leading-none" onclick="closeUploadModal()">&times;</button>
            <h3 class="text-lg font-semibold text-slate-950">Upload Bukti Pembayaran</h3>
            <p class="mt-1 text-sm text-slate-500">Kirim bukti transfer Anda (.jpg, .jpeg, .png, .pdf, maks. 2MB)</p>
            
            <div class="mt-4" id="uploadAlert"></div>
            
            <form id="uploadForm" class="mt-4 grid gap-4" onsubmit="submitBukti(event)">
                <input type="hidden" id="upload_booking_id">
                <div>
                    <label class="label-clean" for="bukti_bayar">File Bukti</label>
                    <input class="input-clean" type="file" id="bukti_bayar" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button class="btn-outline-clean" type="button" onclick="closeUploadModal()">Batal</button>
                    <button class="btn-clean" type="submit" id="uploadSubmitBtn">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        requireAuth();
        
        // Show user's name
        const loggedInUser = JSON.parse(localStorage.getItem('user') || '{}');
        document.getElementById('userName').innerText = loggedInUser.name || 'User';

        loadDashboard();

        async function loadDashboard() {
            const [lapanganResponse, bookingResponse] = await Promise.all([
                fetch('/api/lapangan', {
                    headers: apiHeaders()
                }),
                fetch('/api/riwayat-booking', {
                    headers: apiHeaders()
                })
            ]);

            if (!lapanganResponse.ok) {
                showAlert('dashboardAlert', await getErrorMessage(lapanganResponse));
                return;
            }

            if (!bookingResponse.ok) {
                showAlert('dashboardAlert', await getErrorMessage(bookingResponse));
                return;
            }

            const data = await lapanganResponse.json();
            const bookings = await bookingResponse.json();
            const tersedia = data.filter((item) => item.status === 'tersedia').length;
            const maintenance = data.filter((item) => item.status === 'maintenance').length;

            document.getElementById('jumlahLapangan').innerText = data.length;
            document.getElementById('lapanganTersedia').innerText = tersedia;
            document.getElementById('lapanganMaintenance').innerText = maintenance;
            document.getElementById('jumlahBooking').innerText = bookings.length;

            document.getElementById('lapanganPreview').innerHTML = data.slice(0, 3).map((item) => `
                <div class="rounded-lg border border-slate-200 p-4 transition hover:border-emerald-900/30">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${item.status === 'tersedia' ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-800'}">${escapeHtml(item.status)}</span>
                    <h3 class="mt-3 font-semibold text-slate-950">${escapeHtml(item.nama_lapangan)}</h3>
                    <p class="mt-1 text-sm text-slate-550">${escapeHtml(item.jenis_olahraga)}</p>
                    <p class="mt-3 text-sm font-semibold text-slate-900">${formatRupiah(item.harga_per_jam)} / jam</p>
                </div>
            `).join('');

            document.getElementById('bookingHistory').innerHTML = bookings.length ? bookings.slice(0, 5).map((booking) => `
                <div class="rounded-lg border border-slate-200 p-4 hover:border-slate-350 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-slate-950">${escapeHtml(booking.lapangan?.nama_lapangan ?? 'Lapangan')}</h3>
                            <p class="mt-1 text-xs text-slate-500">Tanggal: ${escapeHtml(booking.tanggal)}</p>
                            <p class="mt-0.5 text-xs text-slate-500">Jam: ${escapeHtml(booking.jam_mulai.slice(0, 5))} - ${escapeHtml(booking.jam_selesai.slice(0, 5))}</p>
                        </div>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ${getStatusBadgeClass(booking.status)}">${escapeHtml(booking.status.replace('_', ' '))}</span>
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                        <p class="text-sm font-bold text-slate-900">${formatRupiah(booking.total_harga)}</p>
                        ${booking.status === 'pending' ? `
                            <button class="btn-clean min-h-8 px-3 py-1 text-xs" onclick="showUploadModal(${booking.id})">Upload Bukti</button>
                        ` : ''}
                    </div>
                </div>
            `).join('') : `
                <div class="rounded-lg border border-slate-200 p-4 text-center">
                    <p class="text-sm text-slate-500">Belum ada riwayat booking.</p>
                </div>
            `;
        }

        function getStatusBadgeClass(status) {
            switch(status) {
                case 'pending': return 'bg-amber-50 text-amber-800';
                case 'menunggu_verifikasi': return 'bg-blue-55 text-blue-900';
                case 'disetujui': return 'bg-emerald-50 text-emerald-900';
                case 'ditolak': return 'bg-red-50 text-red-800';
                default: return 'bg-stone-100 text-stone-700';
            }
        }

        function showUploadModal(bookingId) {
            document.getElementById('upload_booking_id').value = bookingId;
            document.getElementById('uploadModal').classList.remove('hidden');
            document.getElementById('uploadModal').classList.add('flex');
            document.getElementById('uploadAlert').innerHTML = '';
            document.getElementById('uploadForm').reset();
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.getElementById('uploadModal').classList.remove('flex');
        }

        async function submitBukti(event) {
            event.preventDefault();
            const bookingId = document.getElementById('upload_booking_id').value;
            const fileInput = document.getElementById('bukti_bayar');
            
            if (!fileInput.files.length) return;
            
            const btn = document.getElementById('uploadSubmitBtn');
            btn.disabled = true;
            btn.innerText = 'Mengupload...';
            
            const formData = new FormData();
            formData.append('bukti_bayar', fileInput.files[0]);
            
            const response = await fetch(`/api/booking/${bookingId}/upload-bukti`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: formData
            });
            
            if (!response.ok) {
                showAlert('uploadAlert', await getErrorMessage(response));
                btn.disabled = false;
                btn.innerText = 'Upload';
                return;
            }
            
            showAlert('dashboardAlert', 'Bukti pembayaran berhasil diupload!', 'success');
            closeUploadModal();
            loadDashboard();
        }
    </script>
@endpush
