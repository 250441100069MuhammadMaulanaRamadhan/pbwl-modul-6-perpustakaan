<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * [MODUL 6 - SOAL 4] Aturan "siapa boleh melihat peminjaman siapa".
 * Pemeriksaan role saja tidak cukup untuk mencegah IDOR, karena semua siswa punya role
 * yang sama. Yang menentukan adalah KEPEMILIKAN data (peminjaman -> anggota -> user).
 */
class PeminjamanPolicy
{
    private function adminAtauPetugas(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'petugas']);
    }

    /** Boleh melihat satu data peminjaman? */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Admin dan petugas boleh melihat semua peminjaman
        if ($this->adminAtauPetugas($user)) {
            return true;
        }

        // Siswa hanya boleh melihat peminjaman miliknya sendiri
        $anggota = $peminjaman->anggota;

        return $anggota !== null && (int) $anggota->user_id === (int) $user->id;
    }

    /** Boleh melihat riwayat peminjaman milik pengguna lain? (menggantikan abort_unless Modul 5) */
    public function lihatRiwayat(User $user, User $target): Response
    {
        if ($user->is($target) || $this->adminAtauPetugas($user)) {
            return Response::allow();
        }

        return Response::deny('Anda tidak berhak melihat riwayat peminjaman pengguna lain.');
    }
}
