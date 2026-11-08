@extends('layouts.perpustakaan')

@section('title', 'Detail Buku')

@section('content')

    @if ($buku)

        <div class="buku-card">

            <h2>{{ $buku['judul'] }}</h2>

            <p><strong>ID:</strong> {{ $buku['id'] }}</p>
            <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku['tahun'] }}</p>
            <p><strong>Kategori:</strong> {{ $buku['kategori'] }}</p>

            <br>

            @can('mengajukan peminjaman buku')
                <form method="POST" action="{{ route('peminjaman.ajukan') }}" class="inline-form">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $buku['id'] }}">
                    <button type="submit" class="btn btn-utama">Ajukan Peminjaman</button>
                </form>
                @error('buku_id')
                    <small class="error">{{ $message }}</small>
                @enderror
                <br><br>
            @endcan

            <a href="{{ route('buku.index') }}">← Kembali ke Daftar Buku</a>

        </div>

    @else

        <h2>Buku Tidak Ditemukan</h2>

        <p>Maaf, buku yang kamu cari tidak tersedia.</p>

        <a href="{{ route('buku.index') }}">Kembali ke Daftar Buku</a>

    @endif

@endsection
