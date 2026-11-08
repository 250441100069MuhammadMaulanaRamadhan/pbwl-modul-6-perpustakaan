@extends('layouts.perpustakaan')

@section('title', 'Akses Ditolak')

@section('content')

    <section class="panel">
        <h2>403 - Akses Ditolak</h2>

        <p>Anda tidak memiliki hak akses untuk membuka halaman atau menjalankan aksi ini.</p>
        <br>

        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-utama">Kembali</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-utama">Login</a>
        @endauth
    </section>

@endsection
