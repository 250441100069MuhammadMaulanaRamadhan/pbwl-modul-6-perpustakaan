@extends('layouts.perpustakaan')

@section('title', 'Home')

@section('content')

    <h2>Selamat Datang di Perpustakaan</h2>

    <p>
        Silakan melihat koleksi buku yang tersedia di perpustakaan.
    </p>

    <br>

    @guest
        <p>Untuk melihat daftar buku, silakan login terlebih dahulu.</p>
        <br>
        <div class="aksi-form">
            <a href="{{ route('login') }}" class="btn btn-utama">Login</a>
            <a href="{{ route('register') }}" class="btn btn-abu">Register</a>
        </div>
    @endguest

    @auth
        <a href="{{ route('dashboard') }}" class="btn btn-utama">Masuk ke Halaman Saya</a>
    @endauth

@endsection
