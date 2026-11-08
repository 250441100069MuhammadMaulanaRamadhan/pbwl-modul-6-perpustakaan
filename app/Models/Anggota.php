<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = ['user_id', 'nama', 'email', 'no_telepon'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }
}
