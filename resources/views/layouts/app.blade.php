<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPLJ')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-stone-50 text-slate-950 antialiased">
    <x-navbar />

    @hasSection('app_sidebar')
        <div class="mx-auto grid max-w-6xl gap-6 px-5 py-8 lg:grid-cols-[240px_1fr] lg:py-10">
            <x-sidebar />

            <main class="min-w-0">
                @yield('content')
            </main>
        </div>
    @else
        <main class="@yield('main_class', 'page-shell')">
            @yield('content')
        </main>
    @endif

    <x-footer />

    <script>
        const apiHeaders = () => ({
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(localStorage.getItem('token') ? {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            } : {})
        });

        const formatRupiah = (value) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value);

        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        } [char]));

        const showAlert = (targetId, message, type = 'danger') => {
            const target = document.getElementById(targetId);

            if (!target) {
                return;
            }

            const alertClass = type === 'success' ? 'alert-clean-success' : 'alert-clean-danger';
            target.innerHTML = `<div class="${alertClass}" role="alert">${escapeHtml(message)}</div>`;
        };

        const getErrorMessage = async (response) => {
            const data = await response.json().catch(() => ({}));

            if (data.errors) {
                return Object.values(data.errors).flat().join(' ');
            }

            if (data.message) {
                return data.message;
            }

            return 'Terjadi kesalahan. Coba lagi sebentar.';
        };

        const requireAuth = () => {
            if (!localStorage.getItem('token')) {
                window.location.href = '{{ route('login') }}';
            }
        };

        const requireAdmin = () => {
            requireAuth();
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (user.role !== 'admin') {
                window.location.href = '{{ route('dashboard') }}';
            }
        };

        const toggleNavigation = () => {
            document.getElementById('mobileNav')?.classList.toggle('hidden');
        };

        const logout = async () => {
            if (localStorage.getItem('token')) {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: apiHeaders()
                }).catch(() => null);
            }

            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '{{ route('login') }}';
        };

        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user') || '{}');

        document.querySelectorAll(token ? '.auth-only' : '.guest-only')
            .forEach((element) => element.classList.remove('hidden'));

        if (token) {
            if (user.role === 'admin') {
                document.querySelectorAll('.admin-only').forEach((el) => el.classList.remove('hidden'));
                document.querySelectorAll('.pelanggan-only').forEach((el) => el.classList.add('hidden'));
            } else {
                document.querySelectorAll('.pelanggan-only').forEach((el) => el.classList.remove('hidden'));
                document.querySelectorAll('.admin-only').forEach((el) => el.classList.add('hidden'));
            }
        } else {
            document.querySelectorAll('.admin-only, .pelanggan-only').forEach((el) => el.classList.add('hidden'));
        }
    </script>

    @stack('scripts')
</body>

</html>
