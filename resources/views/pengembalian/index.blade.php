@extends('layouts.perpustakaan')

@section('title', 'Data Pengembalian')

@section('content')

    <h2>Pengembalian Buku</h2>
    <p>Daftar buku yang sedang dipinjam dan belum dikembalikan.</p>
    <br>

    <section class="panel">
        <div class="tabel-wrap">
            <table class="tabel">
                <thead>
                    <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($peminjamans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->anggota->nama }}</td>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td class="aksi">
                                @can('mencatat pengembalian buku')
                                    <form method="POST" action="{{ route('pengembalian.store', $p) }}" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-utama">Catat Pengembalian</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="tengah">Tidak ada buku yang sedang dipinjam.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

@endsection
