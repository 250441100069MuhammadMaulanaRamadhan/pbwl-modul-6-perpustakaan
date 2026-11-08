<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $cari     = $request->query('cari');
        $kategori = $request->query('kategori');
        $tahun    = $request->query('tahun');

        $bukus = Buku::with('kategori')
            ->when(filled($cari), function ($q) use ($cari) {
                $q->where(function ($w) use ($cari) {
                    $w->where('judul', 'like', "%{$cari}%")
                      ->orWhere('penulis', 'like', "%{$cari}%");
                });
            })
            ->when(filled($kategori), fn ($q) => $q->where('kategori_id', $kategori))
            ->when(filled($tahun), fn ($q) => $q->where('tahun_terbit', $tahun))
            ->latest()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Struktur respons (data, links, meta) diatur oleh BukuResource
        return BukuResource::collection($bukus);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        $buku = Buku::create($validated);

        return (new BukuResource($buku->load('kategori')))
            ->additional(['message' => 'Buku berhasil ditambahkan'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjamans.anggota'])->find($id);

        if (! $buku) {
            return $this->notFound();
        }

        return response()->json(['data' => $buku], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return $this->notFound();
        }

        $validated = $request->validate(
            $this->rules(sometimes: true),
            $this->messages(),
            $this->attributes()
        );

        $buku->update($validated);

        return (new BukuResource($buku->fresh('kategori')))
            ->additional(['message' => 'Buku berhasil diperbarui'])
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return $this->notFound();
        }

        try {
            $buku->delete();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena masih memiliki data terkait',
            ], 409);
        }

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }

    private function notFound(): JsonResponse
    {
        return response()->json(['message' => 'Buku tidak ditemukan'], 404);
    }

    /* ---------- Aturan validasi ($request->validate) ---------- */

    private function rules(bool $sometimes = false): array
    {
        $wajib = $sometimes ? 'sometimes' : 'required';

        return [
            'kategori_id'  => [$wajib, 'exists:kategoris,id'],
            'judul'        => [$wajib, 'string', 'min:3', 'max:150'],
            'penulis'      => [$wajib, 'string', 'min:3', 'max:100'],
            'tahun_terbit' => [$wajib, 'integer', 'between:1900,' . date('Y')],
        ];
    }

    private function messages(): array
    {
        $tahun = date('Y');

        return [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid.',

            'judul.required' => 'Judul buku wajib diisi.',
            'judul.string'   => 'Judul buku harus berupa teks.',
            'judul.min'      => 'Judul buku minimal :min karakter.',
            'judul.max'      => 'Judul buku maksimal :max karakter.',

            'penulis.required' => 'Penulis wajib diisi.',
            'penulis.string'   => 'Penulis harus berupa teks.',
            'penulis.min'      => 'Penulis minimal :min karakter.',
            'penulis.max'      => 'Penulis maksimal :max karakter.',

            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between'  => "Tahun terbit harus antara 1900 sampai {$tahun}.",
        ];
    }

    private function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}