@extends('layouts.app')

@section('title', 'SPLJ - Booking Lapangan Jakabaring')
@section('main_class', '')

@section('content')
    <x-hero />

    <section class="section-shell section-divider py-16">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow-clean mb-4">Alur booking</p>
                <h2 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Tiga langkah, tanpa distraksi.</h2>
            </div>

            <div class="grid gap-6">
                <div class="flex gap-5">
                    <span class="step-number">1</span>
                    <div>
                        <h3 class="font-semibold text-slate-950">Pilih lapangan</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Bandingkan jenis olahraga, harga, dan status ketersediaan.</p>
                    </div>
                </div>
                <div class="flex gap-5">
                    <span class="step-number">2</span>
                    <div>
                        <h3 class="font-semibold text-slate-950">Tentukan jadwal</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Pilih tanggal serta jam mulai dan selesai sesuai kebutuhan.</p>
                    </div>
                </div>
                <div class="flex gap-5">
                    <span class="step-number">3</span>
                    <div>
                        <h3 class="font-semibold text-slate-950">Konfirmasi booking</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Sistem menghitung total harga dan menyimpan riwayat pesanan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell section-divider py-16">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="eyebrow-clean mb-4">Data lapangan</p>
                <h2 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Fasilitas awal yang sudah tersedia.</h2>
            </div>
            <a class="text-sm font-semibold text-emerald-950 hover:underline" href="{{ route('login') }}">Login untuk lihat semua</a>
        </div>

        <div class="divide-y divide-slate-200 border-y border-slate-200">
            @foreach ([
                ['name' => 'Futsal A Jakabaring', 'sport' => 'Futsal', 'price' => 'Rp100.000 / jam'],
                ['name' => 'Badminton 1', 'sport' => 'Badminton', 'price' => 'Rp50.000 / jam'],
                ['name' => 'Basket Indoor', 'sport' => 'Basket', 'price' => 'Rp150.000 / jam'],
            ] as $field)
                <div class="grid gap-2 py-5 sm:grid-cols-[1fr_0.5fr_0.5fr] sm:items-center">
                    <h3 class="font-semibold text-slate-950">{{ $field['name'] }}</h3>
                    <p class="text-sm text-slate-600">{{ $field['sport'] }}</p>
                    <p class="text-sm font-semibold text-slate-900 sm:text-right">{{ $field['price'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <x-qna />

    <section class="section-shell pb-16">
        <div class="surface-clean flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div>
                <p class="eyebrow-clean mb-3">Mulai sekarang</p>
                <h2 class="text-2xl font-semibold text-slate-950">Masuk ke SPLJ dan lanjutkan pemesanan dari dashboard.</h2>
            </div>
            <div class="flex gap-3">
                <a class="btn-clean" href="{{ route('register') }}">Daftar</a>
                <a class="btn-outline-clean" href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </section>
@endsection
