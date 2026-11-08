<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index', [
            'ringkasan' => [
                'Total buku'         => Buku::count(),
                'Total peminjaman'   => Peminjaman::count(),
                'Menunggu persetujuan' => Peminjaman::where('status', Peminjaman::MENUNGGU)->count(),
                'Sedang dipinjam'    => Peminjaman::where('status', Peminjaman::DISETUJUI)->count(),
                'Sudah dikembalikan' => Peminjaman::where('status', Peminjaman::DIKEMBALIKAN)->count(),
            ],
            'bukuPerKategori'   => Kategori::withCount('bukus')->orderByDesc('bukus_count')->get(),
            'peminjamanPerBuku' => Buku::withCount('peminjamans')->orderByDesc('peminjamans_count')->limit(10)->get(),
        ]);
    }
}
