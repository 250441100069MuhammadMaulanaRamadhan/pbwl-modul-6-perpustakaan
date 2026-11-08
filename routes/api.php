<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

/*
| Modul 5: seluruh endpoint API sekarang dilindungi.
|  - 'web'  : mengaktifkan session + verifikasi CSRF, sehingga login Breeze (session) dikenali
|  - 'auth' : pengguna harus login (respons JSON 401 jika belum)
|  - 'permission:...' : pengguna harus punya izin (respons JSON 403 jika tidak)
*/
Route::middleware(['web', 'auth'])->group(function () {

    Route::prefix('rekap')->middleware('permission:lihat laporan perpustakaan')->group(function () {
        Route::get('buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
        Route::get('peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
        Route::get('pemminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']); // alias ejaan soal
    });

    // Baca data buku: semua role yang boleh melihat daftar buku
    Route::apiResource('buku', BukuController::class)
        ->only(['index', 'show'])
        ->middleware('permission:lihat daftar buku')
        ->names('api.buku');

    // [MODUL 6 - SOAL 3] Tambah / ubah / hapus: aturannya dipusatkan pada Gate "kelola-buku"
    // (sebelumnya: permission:kelola data buku)
    Route::apiResource('buku', BukuController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware('can:kelola-buku')
        ->names('api.buku');
});
