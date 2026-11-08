<div class="buku-card">

    <h3>{{ $buku['judul'] }}</h3>

    <p>
        <strong>Penulis:</strong>
        {{ $buku['penulis'] }}
    </p>

    <p>
        <strong>Tahun:</strong>
        {{ $buku['tahun'] }}
    </p>

    <p>
        <strong>Kategori:</strong>
        {{ $buku['kategori'] }}
    </p>

    {{ $slot }}

</div>