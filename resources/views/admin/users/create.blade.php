@extends('layouts.perpustakaan')

@section('title', 'Tambah Pengguna')

@section('content')

    <h2>Tambah Pengguna</h2>

    <section class="panel">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="field">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
                @error('name') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                @error('password') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            @can('kelola role pengguna')
                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        @foreach ($roles as $r)
                            <option value="{{ $r }}" @selected(old('role', 'siswa') === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                    @error('role') <small class="error">{{ $message }}</small> @enderror
                </div>
            @endcan

            <div class="aksi-form">
                <button type="submit" class="btn btn-utama">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-abu">Batal</a>
            </div>
        </form>
    </section>

@endsection
