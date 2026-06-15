<section class="section-shell grid min-h-[calc(100vh-73px)] items-center py-16 sm:py-20">
    <div class="grid gap-12 lg:grid-cols-[1.04fr_0.96fr] lg:items-center">
        <div>
            <p class="eyebrow-clean mb-5">Sistem Pemesanan Lapangan Jakabaring</p>
            <h1 class="max-w-3xl text-5xl font-semibold leading-[1.04] tracking-[-0.02em] text-slate-950 sm:text-6xl">
                Booking lapangan olahraga dengan alur yang lebih jelas.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                SPLJ membantu pengguna melihat pilihan lapangan, memahami harga per jam, dan masuk ke dashboard booking tanpa proses yang berantakan.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="btn-clean" href="{{ route('register') }}">Daftar sekarang</a>
                <a class="btn-outline-clean" href="{{ route('login') }}">Login</a>
            </div>
        </div>

        <div class="surface-clean p-5 sm:p-7">
            <div class="flex items-start justify-between gap-6 border-b border-slate-200 pb-6">
                <div>
                    <p class="text-sm font-medium text-slate-500">Preview booking</p>
                    <h2 class="mt-1 text-2xl font-semibold text-slate-950">Futsal A Jakabaring</h2>
                </div>
                <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-900">Tersedia</span>
            </div>

            <div class="grid gap-4 py-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <span class="text-sm text-slate-500">Olahraga</span>
                    <span class="text-sm font-semibold text-slate-900">Futsal</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <span class="text-sm text-slate-500">Harga</span>
                    <span class="text-sm font-semibold text-slate-900">Rp100.000 / jam</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Jadwal contoh</span>
                    <span class="text-sm font-semibold text-slate-900">19.00 - 21.00</span>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-stone-50 p-4">
                <p class="text-sm leading-6 text-slate-600">
                    Data lapangan disiapkan dari seeder, lalu ditampilkan ulang di dashboard setelah pengguna login.
                </p>
            </div>
        </div>
    </div>
</section>
