<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-6 text-sm font-medium text-gray-700">
            <a href="{{ route('home') }}" class="font-bold text-blue-700">📚 Perpustakaan</a>
            <a href="{{ route('dashboard') }}">Dashboard</a>

            @can('lihat daftar buku')
                <a href="{{ route('buku.index') }}">Daftar Buku</a>
            @endcan

            @can('lihat profil sendiri')
                <a href="{{ route('profile.edit') }}">Profil</a>
            @endcan
        </div>

        <div class="flex items-center gap-4 text-sm text-gray-600">
            <span>{{ Auth::user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-600 font-medium">Logout</button>
            </form>
        </div>
    </div>
</nav>
