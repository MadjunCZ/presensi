<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->string('nip');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('satker');
            $table->longText('ttd');
            $table->timestamp('waktu_absensi')->useCurrent();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['kegiatan_id', 'nip']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
