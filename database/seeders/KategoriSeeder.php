<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {

        Kategori::factory()
            ->count(3)
            ->state(new Sequence(
                ['nama' => 'Novel'],
                ['nama' => 'Fantasi'],
                ['nama' => 'Pengembangan Diri'],
            ))
            ->create();
    }
}
