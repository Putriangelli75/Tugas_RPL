<nav class="sticky top-0 z-40 border-b border-slate-200/80 bg-stone-50/90 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
        <a class="text-sm font-bold tracking-[0.28em] text-emerald-950" href="{{ route('home') }}">SPLJ</a>

        <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-700 lg:hidden"
            type="button" aria-label="Buka navigasi" onclick="toggleNavigation()">
            <span class="h-0.5 w-5 bg-current before:relative before:-top-1.5 before:block before:h-0.5 before:bg-current after:relative after:top-1 after:block after:h-0.5 after:bg-current"></span>
        </button>

        <div class="hidden items-center gap-8 lg:flex" id="mainNav">
            <div class="flex items-center gap-6 text-sm font-medium">
                <a class="nav-link-clean {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="nav-link-clean auth-only pelanggan-only hidden {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link-clean auth-only pelanggan-only hidden {{ request()->routeIs('lapangan.index') ? 'is-active' : '' }}" href="{{ route('lapangan.index') }}">Lapangan</a>
                
                <a class="nav-link-clean auth-only admin-only hidden {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                <a class="nav-link-clean auth-only admin-only hidden {{ request()->routeIs('admin.lapangan') ? 'is-active' : '' }}" href="{{ route('admin.lapangan') }}">Kelola Lapangan</a>
                <a class="nav-link-clean auth-only admin-only hidden {{ request()->routeIs('admin.bookings') ? 'is-active' : '' }}" href="{{ route('admin.bookings') }}">Kelola Booking</a>
            </div>

            <div class="flex items-center gap-3">
                <a class="btn-outline-clean guest-only" href="{{ route('login') }}">Login</a>
                <a class="btn-clean guest-only" href="{{ route('register') }}">Daftar</a>
                <button class="btn-outline-clean auth-only hidden" type="button" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>

    <div class="hidden border-t border-slate-200 bg-stone-50 px-5 py-4 lg:hidden" id="mobileNav">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 text-sm font-medium">
            <a class="nav-link-clean {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="nav-link-clean auth-only pelanggan-only hidden {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="nav-link-clean auth-only pelanggan-only hidden {{ request()->routeIs('lapangan.index') ? 'is-active' : '' }}" href="{{ route('lapangan.index') }}">Lapangan</a>
            
            <a class="nav-link-clean auth-only admin-only hidden {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
            <a class="nav-link-clean auth-only admin-only hidden {{ request()->routeIs('admin.lapangan') ? 'is-active' : '' }}" href="{{ route('admin.lapangan') }}">Kelola Lapangan</a>
            <a class="nav-link-clean auth-only admin-only hidden {{ request()->routeIs('admin.bookings') ? 'is-active' : '' }}" href="{{ route('admin.bookings') }}">Kelola Booking</a>
            
            <div class="mt-2 flex gap-3">
                <a class="btn-outline-clean guest-only" href="{{ route('login') }}">Login</a>
                <a class="btn-clean guest-only" href="{{ route('register') }}">Daftar</a>
                <button class="btn-outline-clean auth-only hidden" type="button" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>
</nav>
