@extends('layouts.perpustakaan')

@section('title', 'Data Peminjaman')

@section('content')

    <h2>Data Peminjaman</h2>

    <div class="filter-status">
        <a href="{{ route('peminjaman.index') }}" class="btn {{ $status ? 'btn-abu' : 'btn-utama' }}">Semua</a>
        @foreach (['menunggu', 'disetujui', 'ditolak', 'dikembalikan'] as $s)
            <a href="{{ route('peminjaman.index', ['status' => $s]) }}"
               class="btn {{ $status === $s ? 'btn-utama' : 'btn-abu' }}">{{ ucfirst($s) }}</a>
        @endforeach
    </div>

    <section class="panel">
        <div class="tabel-wrap">
            <table class="tabel">
                <thead>
                    <tr>
                        <th>No</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->anggota->nama }}</td>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                            <td><span class="badge badge-{{ $p->status }}">{{ $p->status }}</span></td>
                            <td class="aksi">
                                @if ($p->status === 'menunggu')
                                    @can('memproses persetujuan peminjaman')
                                        <form method="POST" action="{{ route('peminjaman.setujui', $p) }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn btn-utama">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('peminjaman.tolak', $p) }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn btn-merah">Tolak</button>
                                        </form>
                                    @endcan
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="tengah">Belum ada data peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

@endsection
