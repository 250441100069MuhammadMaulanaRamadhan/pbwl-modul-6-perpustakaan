<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

/** Kelola akun & role pengguna (khusus Admin) */
class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('roles')->orderBy('id')->get(),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', Rule::in(Role::pluck('name')->all())],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        // Penetapan role hanya jika pengguna punya izin "kelola role pengguna"
        $role = $request->user()->can('kelola role pengguna') ? $data['role'] : 'siswa';
        $user->assignRole($role);
        $user->anggotaProfil();

        return redirect()->route('admin.users.index')->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (filled($data['password'] ?? null)) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return back()->with('status', 'Pengguna berhasil dihapus.');
    }

    /** Penetapan / perubahan role (izin: kelola role pengguna) */
    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(Role::pluck('name')->all())],
        ]);

        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        $user->syncRoles($data['role']);

        return back()->with('status', "Role {$user->name} diubah menjadi {$data['role']}.");
    }
}
