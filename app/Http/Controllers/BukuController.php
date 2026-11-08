<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    private function format(Buku $buku): array
    {
        return [
            'id'       => $buku->id,
            'judul'    => $buku->judul,
            'penulis'  => $buku->penulis,
            'tahun'    => $buku->tahun_terbit,
            'kategori' => $buku->kategori->nama,
        ];
    }

    /**
     * Modul 4: data buku TIDAK lagi dikirim dari sini.
     * Halaman mengambilnya lewat Fetch API ke GET /api/buku.
     * Controller hanya menyediakan daftar kategori untuk pilihan <select>.
     */
    public function index()
    {
        return view('buku.index', [
            'kategoris' => Kategori::orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function show($id)
    {
        $item = Buku::with('kategori')->find($id);

        return view('buku.show', [
            'buku' => $item ? $this->format($item) : null,
        ]);
    }
}
