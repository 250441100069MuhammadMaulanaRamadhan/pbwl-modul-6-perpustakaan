<?php

namespace App\Providers;

use App\Models\Peminjaman;
use App\Models\User;
use App\Policies\PeminjamanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // [MODUL 6 - SOAL 3] Aturan pengelolaan buku dipusatkan di sini.
        // Hanya role admin dan petugas yang boleh menambah, mengubah, dan menghapus buku.
        Gate::define('kelola-buku', function (User $user): bool {
            return $user->hasAnyRole(['admin', 'petugas']);
        });

        // [MODUL 6 - SOAL 4] Daftarkan Policy untuk model Peminjaman
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);
    }
}
