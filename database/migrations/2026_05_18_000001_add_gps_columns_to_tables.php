<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom GPS pada tabel kegiatans dan absensis
     * untuk fitur absensi berbasis radius lokasi.
     */
    public function up(): void
    {
        // Tambah kolom GPS pada tabel kegiatans
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('lokasi');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->unsignedInteger('radius_meter')->default(100)->after('longitude');
        });

        // Tambah kolom GPS user pada tabel absensis
        Schema::table('absensis', function (Blueprint $table) {
            $table->decimal('latitude_user', 10, 8)->nullable()->after('ttd');
            $table->decimal('longitude_user', 11, 8)->nullable()->after('latitude_user');
            $table->decimal('jarak_meter', 10, 2)->nullable()->after('longitude_user');
            $table->enum('status_validasi_radius', ['dalam_radius', 'luar_radius'])->nullable()->after('jarak_meter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meter']);
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['latitude_user', 'longitude_user', 'jarak_meter', 'status_validasi_radius']);
        });
    }
};
