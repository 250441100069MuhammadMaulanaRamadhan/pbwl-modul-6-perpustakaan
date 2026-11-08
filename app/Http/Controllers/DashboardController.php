<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Arahkan pengguna ke halaman utama sesuai hak aksesnya */
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->can('akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->can('akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('buku.index');
    }

    public function admin(): View
    {
        return view('dashboard.admin', [
            'totalBuku'    => Buku::count(),
            'totalAdmin'   => User::role('admin')->count(),
            'totalPetugas' => User::role('petugas')->count(),
            'totalSiswa'   => User::role('siswa')->count(),
            'menunggu'     => Peminjaman::where('status', Peminjaman::MENUNGGU)->count(),
            'dipinjam'     => Peminjaman::where('status', Peminjaman::DISETUJUI)->count(),
        ]);
    }

    public function petugas(): View
    {
        return view('dashboard.petugas', [
            'jumlahMenunggu' => Peminjaman::where('status', Peminjaman::MENUNGGU)->count(),
            'dipinjam'       => Peminjaman::where('status', Peminjaman::DISETUJUI)->count(),
            'menunggu'       => Peminjaman::with(['anggota', 'buku'])
                                    ->where('status', Peminjaman::MENUNGGU)
                                    ->latest()->limit(5)->get(),
        ]);
    }
}
