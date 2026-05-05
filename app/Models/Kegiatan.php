<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tanggal',
        'lokasi',
        'token',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Generate token unik saat membuat kegiatan
    public static function generateToken(): string
    {
        return Str::uuid()->toString();
    }

    // Relasi ke absensis
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    // Get URL absensi
    public function getAbsensiUrlAttribute(): string
    {
        return url('/absensi/' . $this->token);
    }

    // Get jumlah peserta
    public function getJumlahPesertaAttribute(): int
    {
        return $this->absensis()->count();
    }
}
