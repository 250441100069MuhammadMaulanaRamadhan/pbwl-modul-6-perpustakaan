<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Profil milik sendiri. Pengguna yang dipakai SELALU $request->user()
 * (bukan id dari URL), sehingga tidak mungkin membuka/mengubah profil orang lain.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Samakan data anggota perpustakaan dengan profil akun
        if ($anggota = $user->anggota) {
            $emailDipakai = Anggota::where('email', $user->email)->where('id', '!=', $anggota->id)->exists();

            $anggota->update([
                'nama'  => $user->name,
                'email' => $emailDipakai ? $anggota->email : $user->email,
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
