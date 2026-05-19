@extends('layouts.app')

@section('title', 'Daftar Absensi - ' . $kegiatan->nama_kegiatan)

@push('styles')
<style>
    .badge-radius {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-radius.dalam {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
    .badge-radius.luar {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ef9a9a;
    }
    .jarak-info {
        font-size: 12px;
        color: #666;
    }
</style>
@endpush

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary mb-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="btn btn-outline-secondary mb-2">
            <i class="bi bi-calendar-event"></i> Detail Kegiatan
        </a>
    </div>
</div>

<!-- Header Card -->
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">{{ $kegiatan->nama_kegiatan }}</h5>
                <small class="text-muted">
                    <i class="bi bi-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}
                    <span class="mx-2">•</span>
                    <i class="bi bi-people me-1"></i>
                    {{ $absensis->total() }} Peserta
                    @if($kegiatan->isGpsEnabled())
                        <span class="mx-2">•</span>
                        <i class="bi bi-geo-alt-fill text-success me-1"></i>
                        GPS Aktif ({{ $kegiatan->radius_meter }}m)
                    @endif
                </small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.kegiatan.export-excel', $kegiatan) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search Card -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.kegiatan.absensi', $kegiatan) }}">
            <div class="row g-2">
                <div class="col">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari NIP, Nama, Jabatan, Satker..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                @if(request('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.kegiatan.absensi', $kegiatan) }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Satuan Kerja</th>
                        <th>Waktu Absensi</th>
                        @if($kegiatan->isGpsEnabled())
                        <th class="text-center">Jarak & Status</th>
                        @endif
                        <th class="text-center">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $index => $absen)
                    <tr>
                        <td class="text-center">{{ $absensis->firstItem() + $index }}</td>
                        <td class="font-monospace">{{ $absen->nip }}</td>
                        <td><strong>{{ $absen->nama }}</strong></td>
                        <td>{{ $absen->jabatan }}</td>
                        <td>{{ $absen->satker }}</td>
                        <td>
                            <small>{{ $absen->waktu_formatted }}</small>
                        </td>
                        @if($kegiatan->isGpsEnabled())
                        <td class="text-center">
                            @if($absen->status_validasi_radius)
                                <span class="badge-radius {{ $absen->status_validasi_radius === 'dalam_radius' ? 'dalam' : 'luar' }}">
                                    @if($absen->status_validasi_radius === 'dalam_radius')
                                        <i class="bi bi-check-circle-fill"></i> Dalam Radius
                                    @else
                                        <i class="bi bi-x-circle-fill"></i> Di Luar Radius
                                    @endif
                                </span>
                                <div class="jarak-info mt-1">
                                    {{ number_format($absen->jarak_meter, 0) }}m / {{ $kegiatan->radius_meter }}m
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        @endif
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#ttdModal{{ $absen->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- TTD Modal -->
                    <div class="modal fade" id="ttdModal{{ $absen->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tanda Tangan {{ $absen->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    @if($absen->foto_selfie)
                                    <p class="text-muted small mb-2"><i class="bi bi-camera-fill me-1"></i>Foto Selfie</p>
                                    <img src="{{ asset('storage/' . $absen->foto_selfie) }}" alt="Foto Selfie" 
                                         style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 8px; object-fit: cover;" class="mb-3">
                                    <hr>
                                    @endif
                                    <p class="text-muted small mb-2"><i class="bi bi-pen me-1"></i>Tanda Tangan</p>
                                    <img src="{{ $absen->ttd }}" alt="Tanda Tangan" 
                                         style="max-width: 100%; border: 1px solid #ddd; border-radius: 8px;">
                                    <hr>
                                    <div class="text-start">
                                        <p class="mb-1"><strong>NIP:</strong> {{ $absen->nip }}</p>
                                        <p class="mb-1"><strong>Nama:</strong> {{ $absen->nama }}</p>
                                        <p class="mb-1"><strong>Jabatan:</strong> {{ $absen->jabatan }}</p>
                                        <p class="mb-1"><strong>Satuan Kerja:</strong> {{ $absen->satker }}</p>
                                        @if($absen->status_validasi_radius)
                                        <hr>
                                        <p class="mb-1"><strong>Lokasi GPS User:</strong></p>
                                        <p class="mb-1 font-monospace small">{{ $absen->latitude_user }}, {{ $absen->longitude_user }}</p>
                                        <p class="mb-1"><strong>Jarak:</strong> {{ number_format($absen->jarak_meter, 2) }} meter</p>
                                        <p class="mb-0"><strong>Status:</strong> 
                                            <span class="badge-radius {{ $absen->status_validasi_radius === 'dalam_radius' ? 'dalam' : 'luar' }}">
                                                {{ $absen->status_validasi_radius === 'dalam_radius' ? '✅ Dalam Radius' : '❌ Di Luar Radius' }}
                                            </span>
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="{{ $kegiatan->isGpsEnabled() ? 8 : 7 }}" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                @if(request('search'))
                                    Tidak ada hasil untuk "{{ request('search') }}"
                                @else
                                    Belum ada peserta yang absen
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($absensis->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-center">
            {{ $absensis->links() }}
        </div>
        <div class="text-center text-muted small">
            Menampilkan {{ $absensis->firstItem() }} - {{ $absensis->lastItem() }} 
            dari {{ $absensis->total() }} data
        </div>
    </div>
    @endif
</div>
@endsection
