<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanAdminController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Halaman yang dilindungi (wajib login)  -> middleware "auth"
| Pembatasan per fitur                    -> middleware "permission:..." (Spatie)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Setelah login, pengguna diarahkan sesuai role-nya
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Buku (Modul 4: halaman /buku memakai Fetch API ke /api/buku)
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.index');

    // [MODUL 6 - SOAL 3] Form tambah buku, dijaga Gate. HARUS di atas /buku/{id},
    // jika tidak kata "create" akan dianggap sebagai {id}.
    Route::get('/buku/create', [BukuController::class, 'create'])
        ->middleware('can:kelola-buku')
        ->name('buku.create');

    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.show');

    // =====================================================================
    // [MODUL 6 - SOAL 1] CELAH KEAMANAN YANG SENGAJA DIBUAT UNTUK PRAKTIKUM
    // (A1 ada di view buku/_daftar-blade.blade.php; A2, A3, A4 ada di sini)
    // Semuanya ditambal pada Soal 2 dan Soal 4.
    // =====================================================================

    // A2 - ditambal pada BukuController@cari (query dengan binding) di Soal 2b
    Route::get('/buku-cari', [BukuController::class, 'cari'])
        ->name('buku.cari');

    // [MODUL 6 - SOAL 2c] A3 ditambal: route GET /buku/{buku}/hapus DIHAPUS,
    // penghapusan memakai method DELETE (dilindungi token CSRF oleh grup middleware "web")
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
        ->middleware('can:kelola-buku')
        ->name('buku.destroy');

    // ---- Profil milik sendiri (selalu memakai $request->user(), bukan id dari URL)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');

    // ---- Peminjaman milik sendiri (Siswa, Petugas, Admin)
    Route::post('/peminjaman', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    Route::get('/peminjaman-saya', [PeminjamanController::class, 'status'])
        ->middleware('permission:melihat status peminjaman sendiri')
        ->name('peminjaman.saya');

    // [MODUL 6 - SOAL 1] A4 - IDOR: detail peminjaman diambil dari id tanpa cek pemilik
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->whereNumber('id')
        ->name('peminjaman.show');

    Route::get('/riwayat/{user?}', [PeminjamanController::class, 'riwayat'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('riwayat');

    // ---- Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    // ---- Kelola pengguna & role (khusus Admin)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)
            ->except(['show'])
            ->middleware('permission:kelola data pengguna');

        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('permission:kelola role pengguna')
            ->name('users.role');
    });

    // ---- Operasional perpustakaan (Admin & Petugas)
    Route::get('/peminjaman', [PeminjamanAdminController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');

    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanAdminController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanAdminController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])
        ->middleware('permission:kelola pengembalian')
        ->name('pengembalian.index');

    Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('pengembalian.store');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');
});

// Route login, register, logout, dsb. dari Laravel Breeze (memakai middleware "guest")
require __DIR__.'/auth.php';
