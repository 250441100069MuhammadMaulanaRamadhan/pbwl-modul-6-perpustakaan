<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // menunggu -> disetujui / ditolak -> dikembalikan
            $table->string('status', 20)->default('menunggu')->after('tanggal_kembali');
        });

        // Data lama (Modul 1-4) disesuaikan dengan status barunya
        DB::table('peminjamans')->whereNotNull('tanggal_kembali')->update(['status' => 'dikembalikan']);
        DB::table('peminjamans')->whereNull('tanggal_kembali')->update(['status' => 'disetujui']);
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
