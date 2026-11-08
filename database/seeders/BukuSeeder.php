<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $novel     = Kategori::where('nama', 'Novel')->value('id');
        $fantasi   = Kategori::where('nama', 'Fantasi')->value('id');
        $pengDiri  = Kategori::where('nama', 'Pengembangan Diri')->value('id');

        Buku::factory()
            ->count(15)
            ->state(new Sequence(
                ['judul' => 'Laskar Pelangi',                          'penulis' => 'Andrea Hirata',            'tahun_terbit' => 2005, 'kategori_id' => $novel],
                ['judul' => 'Bumi',                                    'penulis' => 'Tere Liye',                'tahun_terbit' => 2014, 'kategori_id' => $fantasi],
                ['judul' => 'Negeri 5 Menara',                         'penulis' => 'Ahmad Fuadi',              'tahun_terbit' => 2009, 'kategori_id' => $novel],
                ['judul' => 'Filosofi Teras',                          'penulis' => 'Henry Manampiring',        'tahun_terbit' => 2018, 'kategori_id' => $pengDiri],
                ['judul' => 'Atomic Habits',                           'penulis' => 'James Clear',              'tahun_terbit' => 2018, 'kategori_id' => $pengDiri],
                ['judul' => 'Sang Pemimpi',                            'penulis' => 'Andrea Hirata',            'tahun_terbit' => 2006, 'kategori_id' => $novel],
                ['judul' => 'Ayat-Ayat Cinta',                         'penulis' => 'Habiburrahman El Shirazy', 'tahun_terbit' => 2004, 'kategori_id' => $novel],
                ['judul' => 'Perahu Kertas',                           'penulis' => 'Dee Lestari',              'tahun_terbit' => 2009, 'kategori_id' => $novel],
                ['judul' => 'Laut Bercerita',                          'penulis' => 'Leila S. Chudori',         'tahun_terbit' => 2017, 'kategori_id' => $novel],
                ['judul' => 'Bulan',                                   'penulis' => 'Tere Liye',                'tahun_terbit' => 2015, 'kategori_id' => $fantasi],
                ['judul' => 'Matahari',                                'penulis' => 'Tere Liye',                'tahun_terbit' => 2016, 'kategori_id' => $fantasi],
                ['judul' => 'The Hobbit',                              'penulis' => 'J.R.R. Tolkien',           'tahun_terbit' => 1937, 'kategori_id' => $fantasi],
                ['judul' => 'Harry Potter dan Batu Bertuah',           'penulis' => 'J.K. Rowling',             'tahun_terbit' => 1997, 'kategori_id' => $fantasi],
                ['judul' => 'Sebuah Seni untuk Bersikap Bodo Amat',    'penulis' => 'Mark Manson',              'tahun_terbit' => 2016, 'kategori_id' => $pengDiri],
                ['judul' => 'The 7 Habits of Highly Effective People', 'penulis' => 'Stephen R. Covey',         'tahun_terbit' => 1989, 'kategori_id' => $pengDiri],
            ))
            ->create();
    }
}