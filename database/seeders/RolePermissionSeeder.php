<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 16 permission sesuai tabel pada soal No. 4
        $semua = [
            'akses dashboard admin',
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ];

        foreach ($semua as $nama) {
            Permission::firstOrCreate(['name' => $nama, 'guard_name' => 'web']);
        }

        // Cache harus dibersihkan lagi setelah permission dibuat
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $admin   = Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        $petugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $siswa   = Role::firstOrCreate(['name' => 'siswa',   'guard_name' => 'web']);

        $ambil = fn (array $nama) => Permission::where('guard_name', 'web')
            ->whereIn('name', $nama)
            ->get();

        // Admin: semua kecuali dashboard petugas
        $admin->syncPermissions($ambil(array_values(array_diff($semua, ['akses dashboard petugas']))));

        // Petugas
        $petugas->syncPermissions($ambil([
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]));

        // Siswa
        $siswa->syncPermissions($ambil([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}