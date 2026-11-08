@extends('layouts.perpustakaan')

@section('title', 'Kelola Pengguna')

@section('content')

    <h2>Kelola Pengguna &amp; Role</h2>

    <p><a href="{{ route('admin.users.create') }}" class="btn btn-utama">+ Tambah Pengguna</a></p>
    <br>

    <section class="panel">
        <div class="tabel-wrap">
            <table class="tabel">
                <thead>
                    <tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if ($u->is(auth()->user()))
                                    <span class="badge badge-role">{{ $u->getRoleNames()->implode(', ') }}</span>
                                    (akun Anda)
                                @else
                                    @can('kelola role pengguna')
                                        <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role">
                                                @foreach ($roles as $r)
                                                    <option value="{{ $r }}" @selected($u->hasRole($r))>{{ $r }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-kuning">Tetapkan</button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                            <td class="aksi">
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-abu">Ubah</a>

                                @unless ($u->is(auth()->user()))
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline-form"
                                          onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-merah">Hapus</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
