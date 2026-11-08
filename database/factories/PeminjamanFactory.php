<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        $pinjam  = fake()->dateTimeBetween('-1 month', '-1 week');
        $kembali = fake()->optional(0.7)->dateTimeBetween($pinjam, 'now');

        return [
            'anggota_id'      => Anggota::inRandomOrder()->value('id'),
            'buku_id'         => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam'  => $pinjam,
            'tanggal_kembali' => $kembali,
            'status'          => $kembali ? Peminjaman::DIKEMBALIKAN : Peminjaman::DISETUJUI,
        ];
    }
}
