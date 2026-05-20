<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'nip',
        'nama',
        'jabatan',
        'satker',
        'ttd',
        'foto_selfie',
        'latitude_user',
        'longitude_user',
        'jarak_meter',
        'status_validasi_radius',
        'waktu_absensi',
    ];

    protected $casts = [
        'waktu_absensi' => 'datetime',
        'latitude_user' => 'decimal:8',
        'longitude_user' => 'decimal:8',
        'jarak_meter' => 'decimal:2',
    ];

    // Relasi ke kegiatan
    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    // Accessor untuk format waktu
    public function getWaktuFormattedAttribute(): string
    {
        return $this->waktu_absensi->format('d/m/Y H:i:s');
    }

    // Accessor untuk foto selfie dengan URL lengkap
    public function getFotoSelfieUrlAttribute(): string
    {
        if (!$this->foto_selfie) {
            return '';
        }
        
        // Jika sudah URL lengkap, return langsung
        if (str_starts_with($this->foto_selfie, 'http://') || str_starts_with($this->foto_selfie, 'https://')) {
            return $this->foto_selfie;
        }
        
        // Jika path relatif, gunakan asset()
        return asset($this->foto_selfie);
    }
}
