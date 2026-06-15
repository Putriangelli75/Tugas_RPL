@extends('layouts.app')

@section('title', 'Kelola Lapangan - Admin SPLJ')
@section('app_sidebar', true)

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow-clean mb-3">Manajemen Lapangan</p>
            <h1 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Kelola Data Lapangan</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Tambah, ubah, dan nonaktifkan lapangan olahraga Jakabaring.</p>
        </div>
        <button class="btn-clean" onclick="openLapanganModal()">Tambah Lapangan</button>
    </div>

    <div class="mt-8" id="adminLapanganAlert"></div>

    <div class="surface mt-8 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-stone-100/60 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Nama Lapangan</th>
                        <th class="px-5 py-4">Jenis Olahraga</th>
                        <th class="px-5 py-4">Harga / Jam</th>
                        <th class="px-5 py-4">Deskripsi</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200" id="lapanganTableBody">
                    <tr>
                        <td class="px-5 py-5 text-center text-slate-500" colspan="6">Memuat data lapangan...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Tambah / Edit Lapangan -->
    <div id="lapanganModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 backdrop-blur-xs p-4 animate-in fade-in duration-200">
        <div class="surface max-w-lg w-full p-6 shadow-xl relative animate-in zoom-in-95 duration-200">
            <button class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none" onclick="closeLapanganModal()">&times;</button>
            <h3 class="text-lg font-semibold text-slate-950" id="modalTitle">Tambah Lapangan</h3>
            <p class="mt-1 text-sm text-slate-500" id="modalSubtitle">Tambahkan data lapangan baru ke dalam sistem.</p>
            
            <div class="mt-4" id="modalAlert"></div>
            
            <form id="lapanganForm" class="mt-4 grid gap-4" onsubmit="submitLapangan(event)">
                <input type="hidden" id="lapangan_id">
                
                <div>
                    <label class="label-clean" for="nama_lapangan">Nama Lapangan</label>
                    <input class="input-clean" type="text" id="nama_lapangan" placeholder="Contoh: Futsal A" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label-clean" for="jenis_olahraga">Jenis Olahraga</label>
                        <select class="input-clean" id="jenis_olahraga" required>
                            <option value="" disabled selected>Pilih Olahraga</option>
                            <option value="Futsal">Futsal</option>
                            <option value="Badminton">Badminton</option>
                            <option value="Basket">Basket</option>
                            <option value="Tenis">Tenis</option>
                            <option value="Mini Soccer">Mini Soccer</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-clean" for="harga_per_jam">Harga per Jam (IDR)</label>
                        <input class="input-clean" type="number" id="harga_per_jam" placeholder="Harga dalam Rupiah" min="0" required>
                    </div>
                </div>

                <div>
                    <label class="label-clean" for="status">Status Operasional</label>
                    <select class="input-clean" id="status" required>
                        <option value="tersedia" selected>Tersedia</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>

                <div>
                    <label class="label-clean" for="deskripsi">Deskripsi Lapangan</label>
                    <textarea class="input-clean min-h-[80px]" id="deskripsi" placeholder="Tuliskan detail fasilitas lapangan jika ada..."></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t border-slate-100 pt-4">
                    <button class="btn-outline-clean" type="button" onclick="closeLapanganModal()">Batal</button>
                    <button class="btn-clean" type="submit" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        requireAdmin();

        let allLapangan = [];
        loadLapanganList();

        async function loadLapanganList() {
            const response = await fetch('/api/lapangan', {
                headers: apiHeaders()
            });

            if (!response.ok) {
                showAlert('adminLapanganAlert', await getErrorMessage(response));
                return;
            }

            allLapangan = await response.json();

            if (!allLapangan.length) {
                document.getElementById('lapanganTableBody').innerHTML = `
                    <tr>
                        <td class="px-5 py-8 text-center text-slate-500" colspan="6">
                            Belum ada data lapangan. Silakan tambah lapangan baru.
                        </td>
                    </tr>
                `;
                return;
            }

            document.getElementById('lapanganTableBody').innerHTML = allLapangan.map(item => `
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4 font-semibold text-slate-950">${escapeHtml(item.nama_lapangan)}</td>
                    <td class="px-5 py-4 text-slate-600">${escapeHtml(item.jenis_olahraga)}</td>
                    <td class="px-5 py-4 font-medium text-slate-900">${formatRupiah(item.harga_per_jam)}</td>
                    <td class="px-5 py-4 text-slate-500 max-w-[200px] truncate" title="${escapeHtml(item.deskripsi ?? '')}">
                        ${escapeHtml(item.deskripsi ?? '-')}
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ${item.status === 'tersedia' ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-800'}">
                            ${escapeHtml(item.status)}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="text-xs font-semibold text-blue-900 hover:text-blue-700 bg-blue-50 px-2 py-1 rounded transition" onclick="editLapangan(${item.id})">Edit</button>
                            <button class="text-xs font-semibold text-red-900 hover:text-red-700 bg-red-50 px-2 py-1 rounded transition" onclick="deleteLapangan(${item.id})">Hapus</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openLapanganModal(item = null) {
            const modal = document.getElementById('lapanganModal');
            const alertContainer = document.getElementById('modalAlert');
            const form = document.getElementById('lapanganForm');
            
            alertContainer.innerHTML = '';
            form.reset();

            if (item) {
                document.getElementById('lapangan_id').value = item.id;
                document.getElementById('nama_lapangan').value = item.nama_lapangan;
                document.getElementById('jenis_olahraga').value = item.jenis_olahraga;
                document.getElementById('harga_per_jam').value = item.harga_per_jam;
                document.getElementById('status').value = item.status;
                document.getElementById('deskripsi').value = item.deskripsi ?? '';
                
                document.getElementById('modalTitle').innerText = 'Ubah Lapangan';
                document.getElementById('modalSubtitle').innerText = 'Perbarui data lapangan yang sudah ada.';
                document.getElementById('submitBtn').innerText = 'Simpan Perubahan';
            } else {
                document.getElementById('lapangan_id').value = '';
                document.getElementById('modalTitle').innerText = 'Tambah Lapangan';
                document.getElementById('modalSubtitle').innerText = 'Tambahkan data lapangan baru ke dalam sistem.';
                document.getElementById('submitBtn').innerText = 'Simpan';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLapanganModal() {
            const modal = document.getElementById('lapanganModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function editLapangan(id) {
            const item = allLapangan.find(l => l.id === id);
            if (item) {
                openLapanganModal(item);
            }
        }

        async function submitLapangan(event) {
            event.preventDefault();

            const id = document.getElementById('lapangan_id').value;
            const submitBtn = document.getElementById('submitBtn');
            
            submitBtn.disabled = true;
            submitBtn.innerText = 'Menyimpan...';

            const payload = {
                nama_lapangan: document.getElementById('nama_lapangan').value,
                jenis_olahraga: document.getElementById('jenis_olahraga').value,
                harga_per_jam: document.getElementById('harga_per_jam').value,
                status: document.getElementById('status').value,
                deskripsi: document.getElementById('deskripsi').value
            };

            const url = id ? `/api/lapangan/${id}` : '/api/lapangan';
            const method = id ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: apiHeaders(),
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                showAlert('modalAlert', await getErrorMessage(response));
                submitBtn.disabled = false;
                submitBtn.innerText = id ? 'Simpan Perubahan' : 'Simpan';
                return;
            }

            showAlert('adminLapanganAlert', `Lapangan berhasil ${id ? 'diperbarui' : 'ditambahkan'}.`, 'success');
            closeLapanganModal();
            loadLapanganList();
        }

        async function deleteLapangan(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data lapangan ini? Semua riwayat booking terkait mungkin akan terpengaruh.')) {
                return;
            }

            const response = await fetch(`/api/lapangan/${id}`, {
                method: 'DELETE',
                headers: apiHeaders()
            });

            if (!response.ok) {
                showAlert('adminLapanganAlert', await getErrorMessage(response));
                return;
            }

            showAlert('adminLapanganAlert', 'Data lapangan berhasil dihapus.', 'success');
            loadLapanganList();
        }
    </script>
@endpush
