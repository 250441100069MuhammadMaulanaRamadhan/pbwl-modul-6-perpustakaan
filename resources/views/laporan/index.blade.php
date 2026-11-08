@extends('layouts.perpustakaan')

@section('title', 'Laporan Perpustakaan')

@section('content')

    <h2>Laporan Perpustakaan</h2>

    <div class="kartu-grid">
        @foreach ($ringkasan as $label => $jumlah)
            <div class="kartu"><span class="angka">{{ $jumlah }}</span><span>{{ $label }}</span></div>
        @endforeach
    </div>

    <section class="panel">
        <h3>Jumlah Buku per Kategori</h3>
        <div class="tabel-wrap">
            <table class="tabel">
                <thead><tr><th>Kategori</th><th>Jumlah Buku</th></tr></thead>
                <tbody>
                    @foreach ($bukuPerKategori as $k)
                        <tr><td>{{ $k->nama }}</td><td>{{ $k->bukus_count }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <h3>10 Buku Paling Sering Dipinjam</h3>
        <div class="tabel-wrap">
            <table class="tabel">
                <thead><tr><th>Judul</th><th>Total Peminjaman</th></tr></thead>
                <tbody>
                    @foreach ($peminjamanPerBuku as $b)
                        <tr><td>{{ $b->judul }}</td><td>{{ $b->peminjamans_count }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
