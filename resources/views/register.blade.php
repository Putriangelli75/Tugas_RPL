@extends('layouts.app')

@section('title', 'Daftar - SPLJ')

@section('content')
    <div class="grid min-h-[calc(100vh-153px)] items-center gap-10 lg:grid-cols-[0.95fr_1.05fr]">
        <section class="surface-clean p-6 sm:p-8">
            <p class="eyebrow-clean mb-4">Akun baru</p>
            <h1 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Buat akun SPLJ</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">Daftar sekali untuk mengakses dashboard booking dan data lapangan Jakabaring.</p>

            <div class="mt-7" id="registerAlert"></div>

            <form class="grid gap-5" id="registerForm">
                <div>
                    <label class="label-clean" for="name">Nama</label>
                    <input class="input-clean" type="text" id="name" placeholder="Nama lengkap" autocomplete="name" required>
                </div>

                <div>
                    <label class="label-clean" for="email">Email</label>
                    <input class="input-clean" type="email" id="email" placeholder="nama@email.com" autocomplete="email" required>
                </div>

                <div>
                    <label class="label-clean" for="password">Password</label>
                    <input class="input-clean" type="password" id="password" placeholder="Minimal 6 karakter" autocomplete="new-password" minlength="6" required>
                </div>

                <div>
                    <label class="label-clean" for="role">Role</label>
                    <select class="input-clean" id="role" required>
                        <option value="pelanggan" selected>Pelanggan (Customer)</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button class="btn-clean w-full" type="submit" id="registerButton">Daftar</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Sudah punya akun?
                <a class="font-semibold text-emerald-950 hover:underline" href="{{ route('login') }}">Login</a>
            </p>
        </section>

        <aside class="hidden lg:block">
            <div class="border-l border-slate-200 pl-10">
                <p class="eyebrow-clean mb-5">Mulai dari dashboard</p>
                <h2 class="max-w-lg text-4xl font-semibold leading-tight tracking-[-0.02em] text-slate-950">
                    Setelah daftar, pengguna langsung bisa melihat daftar lapangan.
                </h2>
                <div class="mt-8 grid gap-5">
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="font-semibold text-slate-950">Lapangan dummy tersedia</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Seeder sudah menyiapkan contoh futsal, badminton, basket, tenis, dan mini soccer.</p>
                    </div>
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="font-semibold text-slate-950">Alur auth singkat</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Register membuat user, menghasilkan token, lalu redirect ke dashboard.</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('registerForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const button = document.getElementById('registerButton');
            button.disabled = true;
            button.innerText = 'Membuat akun...';

            const response = await fetch('/api/register', {
                method: 'POST',
                headers: apiHeaders(),
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    role: document.getElementById('role').value
                })
            });

            if (!response.ok) {
                showAlert('registerAlert', await getErrorMessage(response));
                button.disabled = false;
                button.innerText = 'Daftar';
                return;
            }

            const data = await response.json();
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            window.location.href = data.user.role === 'admin' ? '/admin/dashboard' : '/dashboard';
        });
    </script>
@endpush
