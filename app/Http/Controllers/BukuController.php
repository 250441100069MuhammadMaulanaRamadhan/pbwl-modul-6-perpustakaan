<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Modul 4: data buku pada tabel utama diambil lewat Fetch API ke GET /api/buku.
     * Modul 6: controller juga mengirim $bukus untuk daftar yang dirender di server (Blade),
     * yaitu tempat celah A1 (Soal 1) dan penghapusan DELETE (Soal 2).
     */
    public function index()
    {
        return view('buku.index', [
            'kategoris' => Kategori::orderBy('nama')->get(['id', 'nama']),
            'bukus'     => Buku::with('kategori')->latest()->orderByDesc('id')->limit(10)->get(),
        ]);
    }

    public function show($id)
    {
        $item = Buku::with('kategori')->find($id);

        return view('buku.show', [
            'buku' => $item ? $this->format($item) : null,
        ]);
    }

    /**
     * [MODUL 6 - SOAL 1] CELAH A2 - SQL Injection.
     * Nilai `judul` dari query string digabung LANGSUNG ke string SQL.
     */
    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

        return response()->json($hasil);
    }
}
