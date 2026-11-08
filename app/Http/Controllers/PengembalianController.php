<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class PengembalianController extends Controller
{
    /** Daftar buku yang sedang dipinjam (belum dikembalikan) */
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::DISETUJUI)
            ->latest()
            ->get();

        return view('pengembalian.index', compact('peminjamans'));
    }

    public function store(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::DISETUJUI) {
            return back()->with('error', 'Hanya buku yang sedang dipinjam yang dapat dicatat pengembaliannya.');
        }

        $peminjaman->update([
            'status'          => Peminjaman::DIKEMBALIKAN,
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('status', 'Pengembalian buku berhasil dicatat.');
    }
}
