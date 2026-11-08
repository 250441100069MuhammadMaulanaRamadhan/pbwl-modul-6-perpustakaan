<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function bukuPerKategori(): JsonResponse
    {
        $data = DB::table('kategoris')
            ->leftJoin('bukus', 'bukus.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.*', DB::raw('COUNT(bukus.id) as jumlah_buku'))
            ->groupBy('kategoris.id')
            ->orderByDesc('jumlah_buku')
            ->get();

        return response()->json(['data' => $data], 200);
    }

    public function peminjamanPerBuku(): JsonResponse
    {
        $data = DB::table('bukus')
            ->leftJoin('peminjamans', 'peminjamans.buku_id', '=', 'bukus.id')
            ->select('bukus.id', 'bukus.judul', DB::raw('COUNT(peminjamans.id) as total_peminjaman'))
            ->groupBy('bukus.id', 'bukus.judul')
            ->orderByDesc('total_peminjaman')
            ->get();

        return response()->json(['data' => $data], 200);
    }
}