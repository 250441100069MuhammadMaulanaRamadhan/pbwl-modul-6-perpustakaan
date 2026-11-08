<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Satu akun pengguna <-> satu data anggota perpustakaan */
    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * Ambil data anggota milik pengguna ini; jika belum ada, dibuat otomatis.
     * Dipakai agar riwayat peminjaman terhubung ke akun yang sedang login.
     */
    public function anggotaProfil(): Anggota
    {
        if ($this->anggota) {
            return $this->anggota;
        }

        $anggota = Anggota::firstOrCreate(
            ['email' => $this->email],
            ['nama' => $this->name, 'no_telepon' => '-']
        );

        if ($anggota->user_id === null) {
            $anggota->user_id = $this->id;
            $anggota->save();
        }

        $this->setRelation('anggota', $anggota);

        return $anggota;
    }
}
