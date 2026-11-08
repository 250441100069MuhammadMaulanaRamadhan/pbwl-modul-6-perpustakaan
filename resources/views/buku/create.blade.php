@extends('layouts.perpustakaan')

@section('title', 'Tambah Buku')

@section('content')

    <h2>➕ Tambah Buku</h2>

    <div id="pesan" class="pesan" role="alert" hidden></div>

    <section class="panel">
        <form id="form-tambah" novalidate>
            <div class="field">
                <label for="kategori_id">Kategori</label>
                <select id="kategori_id" name="kategori_id">
                    <option value="">-- Pilih kategori --</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
                <small class="error" data-error-for="kategori_id"></small>
            </div>

            <div class="field">
                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" maxlength="150">
                <small class="error" data-error-for="judul"></small>
            </div>

            <div class="field">
                <label for="penulis">Penulis</label>
                <input type="text" id="penulis" name="penulis" maxlength="100">
                <small class="error" data-error-for="penulis"></small>
            </div>

            <div class="field">
                <label for="tahun_terbit">Tahun Terbit</label>
                <input type="number" id="tahun_terbit" name="tahun_terbit">
                <small class="error" data-error-for="tahun_terbit"></small>
            </div>

            <div class="aksi-form">
                <button type="submit" id="btn-simpan" class="btn btn-utama">Simpan</button>
                <a href="{{ route('buku.index') }}" class="btn btn-abu">Batal</a>
            </div>
        </form>
    </section>

@endsection

@push('scripts')
<script>
    // Form ini hanya dapat dibuka oleh admin / petugas (route dijaga can:kelola-buku).
    // Penyimpanan memakai API Modul 4: POST /api/buku (juga dijaga can:kelola-buku).
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const elForm = document.getElementById('form-tambah');
    const elPesan = document.getElementById('pesan');
    const elSimpan = document.getElementById('btn-simpan');

    elForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        document.querySelectorAll('[data-error-for]').forEach(el => el.textContent = '');
        elPesan.hidden = true;
        elSimpan.disabled = true;

        const data = {
            kategori_id:  elForm.kategori_id.value,
            judul:        elForm.judul.value.trim(),
            penulis:      elForm.penulis.value.trim(),
            tahun_terbit: elForm.tahun_terbit.value,
        };

        try {
            const response = await fetch('/api/buku', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify(data),
            });
            const body = await response.json().catch(() => null);

            if (!response.ok) {
                if (response.status === 422 && body && body.errors) {
                    Object.entries(body.errors).forEach(([field, pesan]) => {
                        const el = document.querySelector(`[data-error-for="${field}"]`);
                        if (el) el.textContent = pesan[0];
                    });
                }
                elPesan.textContent = (body && body.message) ? body.message : `Gagal menambahkan buku (kode ${response.status}).`;
                elPesan.className = 'pesan pesan-error';
                elPesan.hidden = false;
                return;
            }

            window.location.href = @json(route('buku.index'));
        } catch (error) {
            elPesan.textContent = 'Gagal menambahkan buku: API tidak dapat diakses.';
            elPesan.className = 'pesan pesan-error';
            elPesan.hidden = false;
        } finally {
            elSimpan.disabled = false;
        }
    });
</script>
@endpush
