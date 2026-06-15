@extends('layouts.app')

@section('title', 'Data Lapangan - SPLJ')
@section('app_sidebar', true)

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow-clean mb-3">Data lapangan</p>
            <h1 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Lapangan Jakabaring</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Daftar lapangan olahraga beserta harga dan status operasional.</p>
        </div>
        <a class="btn-outline-clean" href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
    </div>

    <div class="mt-8" id="lapanganAlert"></div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[1fr_360px]">
        <div>
            <div class="grid gap-4 md:grid-cols-2" id="lapanganCards"></div>
        </div>

        <aside class="surface h-fit p-5 xl:sticky xl:top-24">
            <p class="eyebrow-clean mb-3">Form booking</p>
            <h2 class="text-xl font-semibold text-slate-950" id="selectedLapanganName">Pilih lapangan</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600" id="selectedLapanganMeta">Klik tombol pesan pada salah satu lapangan yang tersedia.</p>

            <div class="mt-5" id="bookingAlert"></div>

            <form class="mt-5 grid gap-4" id="bookingForm">
                <input type="hidden" id="lapangan_id">
                <input type="hidden" id="harga_per_jam">

                <div>
                    <label class="label-clean" for="tanggal">Tanggal</label>
                    <input class="input-clean" type="date" id="tanggal" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                    <div>
                        <label class="label-clean" for="jam_mulai">Jam mulai</label>
                        <input class="input-clean" type="time" id="jam_mulai" required>
                    </div>
                    <div>
                        <label class="label-clean" for="jam_selesai">Jam selesai</label>
                        <input class="input-clean" type="time" id="jam_selesai" required>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-stone-50 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-slate-500">Estimasi total</span>
                        <strong class="text-sm text-slate-950" id="bookingEstimate">-</strong>
                    </div>
                </div>

                <button class="btn-clean w-full" type="submit" id="bookingButton" disabled>Pilih lapangan dulu</button>
            </form>
        </aside>
    </div>

    <div class="surface mt-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-stone-100/60 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Olahraga</th>
                        <th class="px-5 py-4">Harga</th>
                        <th class="px-5 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200" id="lapanganBody">
                    <tr>
                        <td class="px-5 py-5 text-center text-slate-500" colspan="4">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        requireAuth();
        let lapangans = [];

        loadLapangan();
        setupBookingForm();

        async function loadLapangan() {
            const response = await fetch('/api/lapangan', {
                headers: apiHeaders()
            });

            if (!response.ok) {
                showAlert('lapanganAlert', await getErrorMessage(response));
                return;
            }

            lapangans = await response.json();

            document.getElementById('lapanganCards').innerHTML = lapangans.map((item) => `
                <div class="surface field-card p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="font-semibold text-slate-950">${escapeHtml(item.nama_lapangan)}</h2>
                            <p class="mt-1 text-sm text-slate-500">${escapeHtml(item.jenis_olahraga)}</p>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${item.status === 'tersedia' ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-800'}">${escapeHtml(item.status)}</span>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-600">${escapeHtml(item.deskripsi ?? 'Belum ada deskripsi.')}</p>
                    <div class="mt-5 flex items-center justify-between gap-4">
                        <p class="text-sm font-semibold text-slate-950">${formatRupiah(item.harga_per_jam)} / jam</p>
                        <button
                            class="${item.status === 'tersedia' ? 'btn-clean' : 'btn-outline-clean cursor-not-allowed opacity-60'}"
                            type="button"
                            ${item.status === 'tersedia' ? `onclick="selectLapangan(${item.id})"` : 'disabled'}>
                            ${item.status === 'tersedia' ? 'Pesan' : 'Tidak tersedia'}
                        </button>
                    </div>
                </div>
            `).join('');

            document.getElementById('lapanganBody').innerHTML = lapangans.map((item) => `
                <tr>
                    <td class="px-5 py-4 font-semibold text-slate-950">${escapeHtml(item.nama_lapangan)}</td>
                    <td class="px-5 py-4 text-slate-600">${escapeHtml(item.jenis_olahraga)}</td>
                    <td class="px-5 py-4 text-slate-600">${formatRupiah(item.harga_per_jam)}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${item.status === 'tersedia' ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-800'}">${escapeHtml(item.status)}</span>
                    </td>
                </tr>
            `).join('');
        }

        function setupBookingForm() {
            setDefaultBookingDate();

            ['tanggal', 'jam_mulai', 'jam_selesai'].forEach((id) => {
                document.getElementById(id).addEventListener('input', updateBookingEstimate);
            });

            document.getElementById('bookingForm').addEventListener('submit', submitBooking);
        }

        function setDefaultBookingDate() {
            const today = new Date().toISOString().slice(0, 10);
            document.getElementById('tanggal').min = today;
            document.getElementById('tanggal').value = today;
        }

        function selectLapangan(id) {
            const lapangan = lapangans.find((item) => item.id === id);

            if (!lapangan) {
                return;
            }

            document.getElementById('lapangan_id').value = lapangan.id;
            document.getElementById('harga_per_jam').value = lapangan.harga_per_jam;
            document.getElementById('selectedLapanganName').innerText = lapangan.nama_lapangan;
            document.getElementById('selectedLapanganMeta').innerText = `${lapangan.jenis_olahraga} - ${formatRupiah(lapangan.harga_per_jam)} / jam`;
            document.getElementById('bookingButton').disabled = false;
            document.getElementById('bookingButton').innerText = 'Buat booking';
            showAlert('bookingAlert', 'Lapangan dipilih. Lengkapi tanggal dan jam booking.', 'success');
            updateBookingEstimate();
        }

        function updateBookingEstimate() {
            const harga = Number(document.getElementById('harga_per_jam').value || 0);
            const mulai = document.getElementById('jam_mulai').value;
            const selesai = document.getElementById('jam_selesai').value;

            if (!harga || !mulai || !selesai || selesai <= mulai) {
                document.getElementById('bookingEstimate').innerText = '-';
                return;
            }

            const [mulaiJam, mulaiMenit] = mulai.split(':').map(Number);
            const [selesaiJam, selesaiMenit] = selesai.split(':').map(Number);
            const durasi = ((selesaiJam * 60 + selesaiMenit) - (mulaiJam * 60 + mulaiMenit)) / 60;

            document.getElementById('bookingEstimate').innerText = formatRupiah(durasi * harga);
        }

        async function submitBooking(event) {
            event.preventDefault();

            const button = document.getElementById('bookingButton');
            button.disabled = true;
            button.innerText = 'Menyimpan...';

            const response = await fetch('/api/booking', {
                method: 'POST',
                headers: apiHeaders(),
                body: JSON.stringify({
                    lapangan_id: document.getElementById('lapangan_id').value,
                    tanggal: document.getElementById('tanggal').value,
                    jam_mulai: document.getElementById('jam_mulai').value,
                    jam_selesai: document.getElementById('jam_selesai').value
                })
            });

            if (!response.ok) {
                showAlert('bookingAlert', await getErrorMessage(response));
                button.disabled = false;
                button.innerText = 'Buat booking';
                return;
            }

            const data = await response.json();
            showAlert('bookingAlert', data.message ?? 'Booking berhasil dibuat.', 'success');
            document.getElementById('bookingForm').reset();
            setDefaultBookingDate();
            document.getElementById('lapangan_id').value = '';
            document.getElementById('harga_per_jam').value = '';
            document.getElementById('selectedLapanganName').innerText = 'Pilih lapangan';
            document.getElementById('selectedLapanganMeta').innerText = 'Booking tersimpan. Pilih lapangan lagi untuk membuat booking baru.';
            document.getElementById('bookingEstimate').innerText = '-';
            button.disabled = true;
            button.innerText = 'Pilih lapangan dulu';
        }
    </script>
@endpush
