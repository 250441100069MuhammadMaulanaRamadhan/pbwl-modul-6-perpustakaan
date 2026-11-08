<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Fitur peminjaman dari sisi pengguna: mengajukan, melihat status, melihat riwayat.
 * Data SELALU dibatasi berdasarkan identitas pengguna yang sedang login.
 */
class PeminjamanController extends Controller
{
    public function ajukan(Request $request)
    {
        $data = $request->validate(
            ['buku_id' => ['required', 'exists:bukus,id']],
            ['buku_id.required' => 'Buku wajib dipilih.', 'buku_id.exists' => 'Buku tidak ditemukan.']
        );

        $anggota = $request->user()->anggotaProfil();

        $sudahAda = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $data['buku_id'])
            ->whereIn('status', [Peminjaman::MENUNGGU, Peminjaman::DISETUJUI])
            ->exists();

        if ($sudahAda) {
            $pesan = 'Anda masih memiliki peminjaman aktif untuk buku ini.';

            return $request->expectsJson()
                ? response()->json(['message' => $pesan], 422)
                : back()->with('error', $pesan);
        }

        Peminjaman::create([
            'anggota_id'     => $anggota->id,
            'buku_id'        => $data['buku_id'],
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => Peminjaman::MENUNGGU,
        ]);

        $pesan = 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan petugas.';

        return $request->expectsJson()
            ? response()->json(['message' => $pesan], 201)
            : redirect()->route('peminjaman.saya')->with('status', $pesan);
    }

    /** Status peminjaman yang masih berjalan (menunggu / disetujui) milik sendiri */
    public function status(Request $request)
    {
        $user = $request->user();

        return view('peminjaman.saya', [
            'judul'       => 'Status Peminjaman Saya',
            'pemilik'     => $user,
            'peminjamans' => $this->milik($user)
                ->whereIn('status', [Peminjaman::MENUNGGU, Peminjaman::DISETUJUI])
                ->latest()->get(),
        ]);
    }

    /**
     * Riwayat peminjaman.
     *  - /riwayat          : riwayat milik sendiri
     *  - /riwayat/{user}   : hanya boleh jika {user} adalah diri sendiri,
     *                        atau pengguna punya izin 'kelola peminjaman' (Admin/Petugas)
     */
    public function riwayat(Request $request, ?User $user = null)
    {
        $login  = $request->user();
        $target = $user ?? $login;

        // Pemeriksaan berdasarkan IDENTITAS pengguna, bukan hanya permission
        abort_unless(
            $target->is($login) || $login->can('kelola peminjaman'),
            403,
            'Anda tidak berhak melihat riwayat peminjaman pengguna lain.'
        );

        return view('peminjaman.saya', [
            'judul'       => $target->is($login) ? 'Riwayat Peminjaman Saya' : 'Riwayat Peminjaman: ' . $target->name,
            'pemilik'     => $target,
            'peminjamans' => $this->milik($target)->latest()->get(),
        ]);
    }

    /**
     * [MODUL 6 - SOAL 1] CELAH A4 - IDOR (Insecure Direct Object Reference).
     * Data diambil hanya berdasarkan {id} dari URL, TANPA memeriksa siapa pemiliknya.
     * Pengguna mana pun yang sudah login bisa membuka peminjaman milik orang lain.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku.kategori'])->findOrFail($id);

        return view('peminjaman.show', ['peminjaman' => $peminjaman]);
    }

    private function milik(User $user)
    {
        return Peminjaman::with('buku.kategori')
            ->whereHas('anggota', fn ($q) => $q->where('user_id', $user->id));
    }
}
