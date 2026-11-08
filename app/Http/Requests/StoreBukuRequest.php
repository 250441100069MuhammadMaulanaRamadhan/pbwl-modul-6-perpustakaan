<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'min:3', 'max:150'],
            'penulis'      => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
        ];
    }

    public function messages(): array
    {
        $tahun = date('Y');

        return [
            'kategori_id.required' => 'Kategori wajib diisi.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid.',

            'judul.required' => 'Judul buku wajib diisi.',
            'judul.string'   => 'Judul buku harus berupa teks.',
            'judul.min'      => 'Judul buku minimal :min karakter',
            'judul.max'      => 'Judul buku maksimal :max karakter.',

            'penulis.required' => 'Penulis wajib diisi.',
            'penulis.string'   => 'Penulis harus berupa teks.',
            'penulis.min'      => 'Penulis minimal :min karakter.',
            'penulis.max'      => 'Penulis maksimal :max karakter.',

            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between'  => "Tahun terbit harus antara 1900 sampai {$tahun}",
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}