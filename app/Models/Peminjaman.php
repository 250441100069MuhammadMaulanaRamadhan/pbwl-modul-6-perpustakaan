<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    public const MENUNGGU     = 'menunggu';
    public const DISETUJUI    = 'disetujui';
    public const DITOLAK      = 'ditolak';
    public const DIKEMBALIKAN = 'dikembalikan';

    protected $table = 'peminjamans';

    protected $fillable = ['anggota_id', 'buku_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
