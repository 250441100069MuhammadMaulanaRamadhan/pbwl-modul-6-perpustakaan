@extends('layouts.perpustakaan')

@section('title', 'Detail Peminjaman')

@section('content')

    <h2>Detail Peminjaman #{{ $peminjaman->id }}</h2>

    <section class="panel">
        <p><strong>Peminjam:</strong> {{ $peminjaman->anggota->nama }}</p>
        <p><strong>Email:</strong> {{ $peminjaman->anggota->email }}</p>
        <p><strong>Buku:</strong> {{ $peminjaman->buku->judul }}</p>
        <p><strong>Kategori:</strong> {{ $peminjaman->buku->kategori->nama }}</p>
        <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam }}</p>
        <p><strong>Tanggal Kembali:</strong> {{ $peminjaman->tanggal_kembali ?? '-' }}</p>
        <p><strong>Status:</strong>
            <span class="badge badge-{{ $peminjaman->status }}">{{ $peminjaman->status }}</span>
        </p>
        <br>
        <a href="{{ route('dashboard') }}" class="btn btn-abu">Kembali</a>
    </section>

@endsection
