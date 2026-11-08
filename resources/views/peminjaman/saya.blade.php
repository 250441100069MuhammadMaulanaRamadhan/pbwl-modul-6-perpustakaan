@extends('layouts.perpustakaan')

@section('title', $judul)

@section('content')

    <h2>{{ $judul }}</h2>
    <p>Pemilik data: <strong>{{ $pemilik->name }}</strong></p>
    <br>

    <section class="panel">
        <div class="tabel-wrap">
            <table class="tabel">
                <thead>
                    <tr>
                        <th>No</th><th>Judul Buku</th><th>Kategori</th>
                        <th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->buku->kategori->nama }}</td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                            <td><span class="badge badge-{{ $p->status }}">{{ $p->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="tengah">Belum ada data peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

@endsection
