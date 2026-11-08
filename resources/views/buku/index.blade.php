@extends('layouts.perpustakaan')

@section('title', 'Daftar Buku')

@section('content')

    <h2>📚 Daftar Buku</h2>

    {{-- Area pesan (sukses / gagal) --}}
    <div id="pesan" class="pesan" role="alert" hidden></div>

    {{-- ===== Soal 2 & 3: Formulir tambah / ubah (hanya Admin & Petugas) ===== --}}
    @can('kelola data buku')
    <section class="panel">
        <h3 id="judul-form">Tambah Buku</h3>

        <form id="form-buku" novalidate>
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
                <button type="button" id="btn-batal" class="btn btn-abu" hidden>Batal</button>
            </div>
        </form>
    </section>
    @endcan

    {{-- ===== Soal 1: Tabel data dari API ===== --}}
    <section class="panel">
        <div class="tabel-wrap">
            <table class="tabel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-buku">
                    <tr><td colspan="6" class="tengah">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="paginasi">
            <button type="button" id="btn-prev" class="btn btn-abu">&laquo; Sebelumnya</button>
            <span id="info-halaman"></span>
            <button type="button" id="btn-next" class="btn btn-abu">Berikutnya &raquo;</button>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    const API_BUKU = '/api/buku';

    // Modul 5: izin pengguna (dari Spatie) + token CSRF untuk session login
    const IZIN = {
        kelola: @json(auth()->user()->can('kelola data buku')),
        pinjam: @json(auth()->user()->can('mengajukan peminjaman buku')),
    };
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const HEADER_JSON = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': CSRF,
    };

    const elTbody   = document.getElementById('tbody-buku');
    const elForm    = document.getElementById('form-buku');
    const elPesan   = document.getElementById('pesan');
    const elJudulForm = document.getElementById('judul-form');
    const elSimpan  = document.getElementById('btn-simpan');
    const elBatal   = document.getElementById('btn-batal');
    const elPrev    = document.getElementById('btn-prev');
    const elNext    = document.getElementById('btn-next');
    const elInfo    = document.getElementById('info-halaman');

    let daftarBuku   = [];   // data buku pada halaman yang sedang tampil
    let halamanAktif = 1;
    let lastPage     = 1;
    let idDiubah     = null; // null = mode tambah, angka = mode ubah

    /* ---------------- Helper tampilan ---------------- */

    function tampilkanPesan(teks, jenis = 'sukses') {
        elPesan.textContent = teks;
        elPesan.className = 'pesan pesan-' + jenis;
        elPesan.hidden = false;
    }

    function sembunyikanPesan() {
        elPesan.hidden = true;
    }

    function bersihkanErrorField() {
        document.querySelectorAll('[data-error-for]').forEach(el => el.textContent = '');
    }

    function tampilkanErrorField(errors) {
        Object.entries(errors).forEach(([field, daftarPesan]) => {
            const el = document.querySelector(`[data-error-for="${field}"]`);
            if (el) el.textContent = daftarPesan[0];
        });
    }

    // Ambil body JSON dengan aman (respons error bisa saja bukan JSON)
    async function bacaJson(response) {
        try {
            return await response.json();
        } catch (e) {
            return null;
        }
    }

    // Satu tempat untuk menerjemahkan kegagalan menjadi pesan yang mudah dipahami
    function pesanGagal(response, body, aksi) {
        if (response.status === 401) {
            return 'Sesi login berakhir. Silakan login kembali.';
        }
        if (response.status === 403) {
            return 'Anda tidak memiliki hak akses untuk aksi ini.';
        }
        if (response.status === 422) {
            return 'Data tidak valid. Periksa kembali isian formulir.';
        }
        if (body && body.message) {
            return body.message;
        }
        return `Gagal ${aksi} (kode ${response.status}).`;
    }

    function pesanTidakTerhubung(aksi) {
        return `Gagal ${aksi}: API tidak dapat diakses. Pastikan server berjalan lalu coba lagi.`;
    }

    /* ---------------- Soal 1: GET (Read) ---------------- */

    async function muatBuku(halaman = 1) {
        try {
            const response = await fetch(`${API_BUKU}?page=${halaman}`, {
                headers: HEADER_JSON,
            });
            const body = await bacaJson(response);

            if (!response.ok) {
                elTbody.innerHTML = '<tr><td colspan="6" class="tengah">Data tidak dapat ditampilkan.</td></tr>';
                tampilkanPesan(pesanGagal(response, body, 'mengambil data buku'), 'error');
                return;
            }

            // Jika halaman terakhir kosong (mis. setelah hapus), mundur satu halaman
            if (body.data.length === 0 && body.meta.current_page > 1) {
                return muatBuku(body.meta.last_page);
            }

            daftarBuku   = body.data;
            halamanAktif = body.meta.current_page;
            lastPage     = body.meta.last_page;

            renderTabel(body.meta);
        } catch (error) {
            elTbody.innerHTML = '<tr><td colspan="6" class="tengah">Data tidak dapat ditampilkan.</td></tr>';
            tampilkanPesan(pesanTidakTerhubung('mengambil data buku'), 'error');
        }
    }

    function renderTabel(meta) {
        elTbody.innerHTML = '';

        if (daftarBuku.length === 0) {
            elTbody.innerHTML = '<tr><td colspan="6" class="tengah">Belum ada data buku.</td></tr>';
        }

        daftarBuku.forEach((buku, i) => {
            const tr = document.createElement('tr');

            // Atribut respons API Resource: kategori, judul, penulis, tahun
            const kolom = [
                (meta.from ?? 1) + i,
                buku.kategori,
                buku.judul,
                buku.penulis,
                buku.tahun,
            ];
            kolom.forEach(nilai => {
                const td = document.createElement('td');
                td.textContent = nilai;      // textContent => aman dari XSS
                tr.appendChild(td);
            });

            const tdAksi = document.createElement('td');
            tdAksi.className = 'aksi';

            const btnUbah = document.createElement('button');
            btnUbah.type = 'button';
            btnUbah.className = 'btn btn-kuning';
            btnUbah.textContent = 'Ubah';
            btnUbah.addEventListener('click', () => mulaiUbah(buku.id));

            const btnHapus = document.createElement('button');
            btnHapus.type = 'button';
            btnHapus.className = 'btn btn-merah';
            btnHapus.textContent = 'Hapus';
            btnHapus.addEventListener('click', () => hapusBuku(buku.id, buku.judul));

            const linkDetail = document.createElement('a');
            linkDetail.href = `/buku/${buku.id}`;
            linkDetail.className = 'btn btn-abu';
            linkDetail.textContent = 'Detail';

            const tombol = [];
            if (IZIN.kelola) tombol.push(btnUbah, btnHapus);

            if (IZIN.pinjam) {
                const btnPinjam = document.createElement('button');
                btnPinjam.type = 'button';
                btnPinjam.className = 'btn btn-utama';
                btnPinjam.textContent = 'Ajukan Pinjam';
                btnPinjam.addEventListener('click', () => ajukanPinjam(buku.id, buku.judul));
                tombol.push(btnPinjam);
            }

            tombol.push(linkDetail);
            tdAksi.append(...tombol);
            tr.appendChild(tdAksi);
            elTbody.appendChild(tr);
        });

        elInfo.textContent = `Halaman ${meta.current_page} dari ${meta.last_page} (total ${meta.total} buku)`;
        elPrev.disabled = meta.current_page <= 1;
        elNext.disabled = meta.current_page >= meta.last_page;
    }

    /* ---------------- Soal 2 & 3: POST / PUT (Create & Update) ---------------- */

    function ambilDataForm() {
        return {
            kategori_id:  elForm.kategori_id.value,
            judul:        elForm.judul.value.trim(),
            penulis:      elForm.penulis.value.trim(),
            tahun_terbit: elForm.tahun_terbit.value,
        };
    }

    async function tambahBuku(data) {
        try {
            const response = await fetch(API_BUKU, {
                method: 'POST',
                headers: { ...HEADER_JSON, 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });
            const body = await bacaJson(response);

            if (!response.ok) {
                if (response.status === 422 && body && body.errors) {
                    tampilkanErrorField(body.errors);
                }
                tampilkanPesan(pesanGagal(response, body, 'menambahkan buku'), 'error');
                return;
            }

            resetForm();                       // formulir kembali kosong
            await muatBuku(1);                 // tabel diperbarui tanpa reload (buku terbaru di halaman 1)
            tampilkanPesan(body.message ?? 'Buku berhasil ditambahkan', 'sukses');
        } catch (error) {
            tampilkanPesan(pesanTidakTerhubung('menambahkan buku'), 'error');
        }
    }

    function mulaiUbah(id) {
        const buku = daftarBuku.find(b => b.id === id);
        if (!buku) {
            tampilkanPesan('Data buku tidak ditemukan pada tabel. Muat ulang halaman.', 'error');
            return;
        }

        idDiubah = id;
        elForm.kategori_id.value  = buku.kategori_id;
        elForm.judul.value        = buku.judul;
        elForm.penulis.value      = buku.penulis;
        elForm.tahun_terbit.value = buku.tahun;

        elJudulForm.textContent = `Ubah Buku (ID ${id})`;
        elSimpan.textContent    = 'Perbarui';
        elBatal.hidden          = false;
        bersihkanErrorField();
        sembunyikanPesan();
        elForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    async function ubahBuku(id, data) {
        try {
            const response = await fetch(`${API_BUKU}/${id}`, {
                method: 'PUT',
                headers: { ...HEADER_JSON, 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });
            const body = await bacaJson(response);

            if (!response.ok) {
                if (response.status === 422 && body && body.errors) {
                    tampilkanErrorField(body.errors);
                }
                tampilkanPesan(pesanGagal(response, body, 'memperbarui buku'), 'error');
                return;
            }

            resetForm();
            await muatBuku(halamanAktif);      // tetap di halaman yang sama
            tampilkanPesan(body.message ?? 'Buku berhasil diperbarui', 'sukses');
        } catch (error) {
            tampilkanPesan(pesanTidakTerhubung('memperbarui buku'), 'error');
        }
    }

    function resetForm() {
        elForm.reset();
        idDiubah = null;
        elJudulForm.textContent = 'Tambah Buku';
        elSimpan.textContent    = 'Simpan';
        elBatal.hidden          = true;
        bersihkanErrorField();
    }

    if (elForm) elForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        bersihkanErrorField();
        sembunyikanPesan();

        elSimpan.disabled = true;               // cegah klik ganda
        const data = ambilDataForm();
        if (idDiubah === null) {
            await tambahBuku(data);
        } else {
            await ubahBuku(idDiubah, data);
        }
        elSimpan.disabled = false;
    });

    if (elBatal) elBatal.addEventListener('click', () => {
        resetForm();
        sembunyikanPesan();
    });

    /* ---------------- Soal 4: DELETE ---------------- */

    async function hapusBuku(id, judul) {
        if (!confirm(`Yakin ingin menghapus buku "${judul}"?`)) {
            return;                              // dibatalkan => tidak ada request
        }

        try {
            const response = await fetch(`${API_BUKU}/${id}`, {
                method: 'DELETE',
                headers: HEADER_JSON,
            });
            const body = await bacaJson(response);

            if (!response.ok) {
                tampilkanPesan(pesanGagal(response, body, 'menghapus buku'), 'error');
                return;
            }

            if (idDiubah === id) resetForm();    // buku yang sedang diedit ikut terhapus
            await muatBuku(halamanAktif);
            tampilkanPesan(body.message ?? 'Buku berhasil dihapus', 'sukses');
        } catch (error) {
            tampilkanPesan(pesanTidakTerhubung('menghapus buku'), 'error');
        }
    }

    /* ---------------- Modul 5: ajukan peminjaman (POST /peminjaman) ---------------- */

    async function ajukanPinjam(id, judul) {
        if (!confirm(`Ajukan peminjaman buku "${judul}"?`)) {
            return;
        }

        try {
            const response = await fetch('/peminjaman', {
                method: 'POST',
                headers: { ...HEADER_JSON, 'Content-Type': 'application/json' },
                body: JSON.stringify({ buku_id: id }),
            });
            const body = await bacaJson(response);

            if (!response.ok) {
                tampilkanPesan(pesanGagal(response, body, 'mengajukan peminjaman'), 'error');
                return;
            }

            tampilkanPesan(body.message ?? 'Pengajuan peminjaman berhasil', 'sukses');
        } catch (error) {
            tampilkanPesan(pesanTidakTerhubung('mengajukan peminjaman'), 'error');
        }
    }

    /* ---------------- Paginasi & inisialisasi ---------------- */

    elPrev.addEventListener('click', () => muatBuku(halamanAktif - 1));
    elNext.addEventListener('click', () => muatBuku(halamanAktif + 1));

    muatBuku(1);
</script>
@endpush
