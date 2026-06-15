<aside class="h-fit rounded-lg border border-slate-200 bg-stone-100/50 p-3 lg:sticky lg:top-24">
    <!-- Pelanggan Sidebar Header -->
    <div class="mb-3 px-3 py-2 pelanggan-only hidden">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-800">Area Pengguna</p>
        <p class="mt-2 text-sm leading-6 text-slate-600">Kelola data booking dan lapangan dari dashboard.</p>
    </div>

    <!-- Admin Sidebar Header -->
    <div class="mb-3 px-3 py-2 admin-only hidden">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-800">Area Admin</p>
        <p class="mt-2 text-sm leading-6 text-slate-600">Kelola sistem, lapangan, dan verifikasi booking.</p>
    </div>

    <nav class="grid gap-1">
        <!-- Pelanggan Links -->
        <a class="sidebar-link pelanggan-only hidden {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
            <span>Dashboard</span>
            <span aria-hidden="true">/</span>
        </a>
        <a class="sidebar-link pelanggan-only hidden {{ request()->routeIs('lapangan.index') ? 'is-active' : '' }}" href="{{ route('lapangan.index') }}">
            <span>Pesan Lapangan</span>
            <span aria-hidden="true">/</span>
        </a>

        <!-- Admin Links -->
        <a class="sidebar-link admin-only hidden {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
            <span>Dashboard Admin</span>
            <span aria-hidden="true">/</span>
        </a>
        <a class="sidebar-link admin-only hidden {{ request()->routeIs('admin.lapangan') ? 'is-active' : '' }}" href="{{ route('admin.lapangan') }}">
            <span>Kelola Lapangan</span>
            <span aria-hidden="true">/</span>
        </a>
        <a class="sidebar-link admin-only hidden {{ request()->routeIs('admin.bookings') ? 'is-active' : '' }}" href="{{ route('admin.bookings') }}">
            <span>Kelola Booking</span>
            <span aria-hidden="true">/</span>
        </a>
    </nav>
</aside>
