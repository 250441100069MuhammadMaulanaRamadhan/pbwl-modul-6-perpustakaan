@extends('layouts.perpustakaan')

@section('title', 'Dashboard Admin')

@section('content')

    <h2>Dashboard Admin</h2>

    <div class="kartu-grid">
        <div class="kartu"><span class="angka">{{ $totalBuku }}</span><span>Total Buku</span></div>
        <div class="kartu"><span class="angka">{{ $totalAdmin }}</span><span>Akun Admin</span></div>
        <div class="kartu"><span class="angka">{{ $totalPetugas }}</span><span>Akun Petugas</span></div>
        <div class="kartu"><span class="angka">{{ $totalSiswa }}</span><span>Akun Siswa</span></div>
        <div class="kartu"><span class="angka">{{ $menunggu }}</span><span>Menunggu Persetujuan</span></div>
        <div class="kartu"><span class="angka">{{ $dipinjam }}</span><span>Sedang Dipinjam</span></div>
    </div>

    <section class="panel">
        <h3>Menu Administrasi</h3>
        <div class="link-aksi">
            @can('kelola-buku')
                <a href="{{ route('buku.index') }}" class="btn btn-utama">Kelola Buku</a>
            @endcan
            @can('kelola data pengguna')
                <a href="{{ route('admin.users.index') }}" class="btn btn-utama">Kelola Pengguna &amp; Role</a>
            @endcan
            @can('kelola peminjaman')
                <a href="{{ route('peminjaman.index') }}" class="btn btn-utama">Data Peminjaman</a>
            @endcan
            @can('kelola pengembalian')
                <a href="{{ route('pengembalian.index') }}" class="btn btn-utama">Data Pengembalian</a>
            @endcan
            @can('lihat laporan perpustakaan')
                <a href="{{ route('laporan.index') }}" class="btn btn-utama">Laporan</a>
            @endcan
        </div>
    </section>

@endsection
