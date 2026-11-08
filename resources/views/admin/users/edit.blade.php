@extends('layouts.perpustakaan')

@section('title', 'Ubah Pengguna')

@section('content')

    <h2>Ubah Pengguna</h2>

    <section class="panel">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                @error('name') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
                @error('email') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label for="password">Password baru (kosongkan jika tidak diubah)</label>
                <input type="password" id="password" name="password">
                @error('password') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Password baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <p>Role saat ini: <span class="badge badge-role">{{ $user->getRoleNames()->implode(', ') }}</span>
               (diubah lewat tombol "Tetapkan" di daftar pengguna)</p>
            <br>

            <div class="aksi-form">
                <button type="submit" class="btn btn-utama">Perbarui</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-abu">Batal</a>
            </div>
        </form>
    </section>

@endsection
