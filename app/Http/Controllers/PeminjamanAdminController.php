<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

/** Pengelolaan peminjaman oleh Admin / Petugas */
class PeminjamanAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->when(filled($status), fn ($q) => $q->where('status', $status))
            ->latest()
            ->limit(100)
            ->get();

        return view('peminjaman.index', compact('peminjamans', 'status'));
    }

    public function setujui(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::MENUNGGU) {
            return back()->with('error', 'Hanya peminjaman berstatus menunggu yang dapat diproses.');
        }

        $peminjaman->update([
            'status'         => Peminjaman::DISETUJUI,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('status', 'Peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::MENUNGGU) {
            return back()->with('error', 'Hanya peminjaman berstatus menunggu yang dapat diproses.');
        }

        $peminjaman->update(['status' => Peminjaman::DITOLAK]);

        return back()->with('status', 'Peminjaman ditolak.');
    }
}
