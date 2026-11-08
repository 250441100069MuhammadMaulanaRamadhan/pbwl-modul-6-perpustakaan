<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Perpustakaan')</title>

    @vite(['resources/css/perpustakaan.css'])
</head>

<body>

    <header>
        <h1>📚 Perpustakaan</h1>

        <nav>
            <a href="{{ route('home') }}">Home</a>

            @auth
                {{-- Menu tampil sesuai hak akses (@role / @can) --}}
                @can('lihat daftar buku')
                    <a href="{{ route('buku.index') }}">Daftar Buku</a>
                @endcan

                @role('admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                @endrole

                @role('petugas')
                    <a href="{{ route('petugas.dashboard') }}">Dashboard Petugas</a>
                @endrole

                @can('kelola data pengguna')
                    <a href="{{ route('admin.users.index') }}">Pengguna</a>
                @endcan

                @can('kelola peminjaman')
                    <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
                @endcan

                @can('kelola pengembalian')
                    <a href="{{ route('pengembalian.index') }}">Pengembalian</a>
                @endcan

                @can('lihat laporan perpustakaan')
                    <a href="{{ route('laporan.index') }}">Laporan</a>
                @endcan

                @can('melihat status peminjaman sendiri')
                    <a href="{{ route('peminjaman.saya') }}">Status Saya</a>
                @endcan

                @can('melihat riwayat peminjaman sendiri')
                    <a href="{{ route('riwayat') }}">Riwayat Saya</a>
                @endcan

                @can('lihat profil sendiri')
                    <a href="{{ route('profile.edit') }}">Profil</a>
                @endcan

                <span class="nav-kanan">
                    <span class="nav-user">
                        {{ auth()->user()->name }} ({{ auth()->user()->getRoleNames()->implode(', ') }})
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-logout">Logout</button>
                    </form>
                </span>
            @else
                <span class="nav-kanan">
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                </span>
            @endauth
        </nav>
    </header>

    <main>
        @if (session('status'))
            <div class="pesan pesan-sukses" role="status">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="pesan pesan-error" role="alert">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 Perpustakaan</p>
    </footer>

    @stack('scripts')
</body>
</html>
