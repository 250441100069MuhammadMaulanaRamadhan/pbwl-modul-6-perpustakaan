@extends('layouts.perpustakaan')

@section('title', 'Dashboard Petugas')

@section('content')

    <h2>Dashboard Petugas</h2>

    <div class="kartu-grid">
        <div class="kartu"><span class="angka">{{ $jumlahMenunggu }}</span><span>Menunggu Persetujuan</span></div>
        <div class="kartu"><span class="angka">{{ $dipinjam }}</span><span>Sedang Dipinjam</span></div>
    </div>

    <section class="panel">
        <h3>Pengajuan Terbaru</h3>

        <div class="tabel-wrap">
            <table class="tabel">
                <thead>
                    <tr><th>Anggota</th><th>Buku</th><th>Tanggal</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($menunggu as $p)
                        <tr>
                            <td>{{ $p->anggota->nama }}</td>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td><span class="badge badge-{{ $p->status }}">{{ $p->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="tengah">Tidak ada pengajuan yang menunggu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <h3>Menu Operasional</h3>
        <div class="link-aksi">
            @can('kelola-buku')
                <a href="{{ route('buku.index') }}" class="btn btn-utama">Kelola Buku</a>
            @endcan
            @can('kelola peminjaman')
                <a href="{{ route('peminjaman.index') }}" class="btn btn-utama">Proses Peminjaman</a>
            @endcan
            @can('kelola pengembalian')
                <a href="{{ route('pengembalian.index') }}" class="btn btn-utama">Proses Pengembalian</a>
            @endcan
            @can('lihat laporan perpustakaan')
                <a href="{{ route('laporan.index') }}" class="btn btn-utama">Laporan</a>
            @endcan
        </div>
    </section>

@endsection
