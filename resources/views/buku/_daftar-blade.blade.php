{{-- [MODUL 6] Daftar buku yang dirender di server (Blade) --}}
<section class="panel">
    <h3>Daftar Buku (render server / Blade)</h3>

    <div class="tabel-wrap">
        <table class="tabel">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bukus as $buku)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        {{-- [SOAL 1] CELAH A1 - XSS: {!! !!} TIDAK melakukan escaping --}}
                        <td>{!! $buku->judul !!}</td>

                        <td>{{ $buku->penulis }}</td>
                        <td class="aksi">
                            <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-abu">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="tengah">Belum ada data buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
