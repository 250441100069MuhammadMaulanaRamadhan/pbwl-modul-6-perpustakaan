<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    /**
     * Pemetaan atribut respons API  <-  kolom/relasi pada database
     *
     *   id          <- bukus.id
     *   kategori_id <- bukus.kategori_id
     *   kategori    <- kategoris.nama   (relasi belongsTo, bukan kolom di tabel bukus)
     *   judul       <- bukus.judul
     *   penulis     <- bukus.penulis
     *   tahun       <- bukus.tahun_terbit   (nama atribut berbeda dari nama kolom)
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'kategori_id' => $this->kategori_id,
            'kategori'    => $this->whenLoaded('kategori', fn () => $this->kategori->nama),
            'judul'       => $this->judul,
            'penulis'     => $this->penulis,
            'tahun'       => $this->tahun_terbit,
        ];
    }
}
