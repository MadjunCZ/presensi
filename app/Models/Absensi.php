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
        'waktu_absensi',
    ];

    protected $casts = [
        'waktu_absensi' => 'datetime',
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
}
