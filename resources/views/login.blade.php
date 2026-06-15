@extends('layouts.app')

@section('title', 'Login - SPLJ')

@section('content')
    <div class="grid min-h-[calc(100vh-153px)] items-center gap-10 lg:grid-cols-[0.95fr_1.05fr]">
        <section class="surface-clean p-6 sm:p-8">
            <p class="eyebrow-clean mb-4">Login</p>
            <h1 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Masuk ke SPLJ</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">Gunakan akun yang sudah terdaftar untuk membuka dashboard dan melihat data lapangan.</p>

            <div class="mt-7" id="loginAlert"></div>

            <form class="grid gap-5" id="loginForm">
                <div>
                    <label class="label-clean" for="email">Email</label>
                    <input class="input-clean" type="email" id="email" placeholder="nama@email.com" autocomplete="email" required>
                </div>

                <div>
                    <label class="label-clean" for="password">Password</label>
                    <input class="input-clean" type="password" id="password" placeholder="Masukkan password" autocomplete="current-password" required>
                </div>

                <button class="btn-clean w-full" type="submit" id="loginButton">Login</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Belum punya akun?
                <a class="font-semibold text-emerald-950 hover:underline" href="{{ route('register') }}">Daftar sekarang</a>
            </p>
        </section>

        <aside class="hidden lg:block">
            <div class="border-l border-slate-200 pl-10">
                <p class="eyebrow-clean mb-5">Untuk pengguna lapangan</p>
                <h2 class="max-w-lg text-4xl font-semibold leading-tight tracking-[-0.02em] text-slate-950">
                    Satu akun untuk cek lapangan dan melanjutkan booking.
                </h2>
                <div class="mt-8 grid gap-5">
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="font-semibold text-slate-950">Data tersinkron</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Halaman dashboard mengambil data dari API yang sama dengan halaman lapangan.</p>
                    </div>
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="font-semibold text-slate-950">Akses aman</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Token Sanctum disimpan di browser untuk menjaga sesi pengguna.</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const button = document.getElementById('loginButton');
            button.disabled = true;
            button.innerText = 'Memproses...';

            const response = await fetch('/api/login', {
                method: 'POST',
                headers: apiHeaders(),
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value
                })
            });

            if (!response.ok) {
                showAlert('loginAlert', await getErrorMessage(response));
                button.disabled = false;
                button.innerText = 'Login';
                return;
            }

            const data = await response.json();
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            window.location.href = data.user.role === 'admin' ? '/admin/dashboard' : '/dashboard';
        });
    </script>
@endpush
