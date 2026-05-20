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
    
    /* Zoom Viewer Styles */
    .zoom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .zoom-modal.show {
        display: flex;
    }
    .zoom-modal-content {
        position: relative;
        max-width: 90vw;
        max-height: 80vh;
        overflow: auto;
    }
    .zoom-modal-content img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        transition: transform 0.2s ease;
        cursor: grab;
    }
    .zoom-modal-content img.dragging {
        cursor: grabbing;
    }
    .zoom-controls {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        background: rgba(255, 255, 255, 0.9);
        padding: 10px 20px;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        z-index: 10001;
    }
    .zoom-controls button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #0d6efd;
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .zoom-controls button:hover {
        background: #0b5ed7;
        transform: scale(1.1);
    }
    .zoom-controls button:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }
    .zoom-controls .zoom-level {
        display: flex;
        align-items: center;
        font-weight: 600;
        min-width: 60px;
        justify-content: center;
    }
    .zoom-close {
        position: fixed;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        font-size: 24px;
        cursor: pointer;
        z-index: 10001;
        transition: all 0.2s;
    }
    .zoom-close:hover {
        background: white;
        transform: scale(1.1);
    }
    .zoom-hint {
        position: absolute;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-size: 12px;
        opacity: 0.7;
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
                                    @if($absen->foto_selfie_url)
                                    <p class="text-muted small mb-2"><i class="bi bi-camera-fill me-1"></i>Foto Selfie</p>
                                    <img src="{{ $absen->foto_selfie_url }}" alt="Foto Selfie" 
                                         style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 8px; object-fit: cover; cursor: zoom-in;" 
                                         class="mb-3 zoomable-image"
                                         data-image-src="{{ $absen->foto_selfie_url }}"
                                         onclick="openZoomViewer(this)">
                                    <hr>
                                    @endif
                                    <p class="text-muted small mb-2"><i class="bi bi-pen me-1"></i>Tanda Tangan</p>
                                    <img src="{{ $absen->ttd }}" alt="Tanda Tangan" 
                                         style="max-width: 100%; border: 1px solid #ddd; border-radius: 8px; cursor: zoom-in;"
                                         class="zoomable-image"
                                         data-image-src="{{ $absen->ttd }}"
                                         onclick="openZoomViewer(this)">
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

<!-- Zoom Viewer Modal -->
<div class="zoom-modal" id="zoomModal">
    <button class="zoom-close" onclick="closeZoomViewer()">&times;</button>
    <div class="zoom-modal-content" id="zoomContent">
        <img src="" alt="Zoomed Image" id="zoomImage">
    </div>
    <p class="zoom-hint">Klik atau scroll untuk zoom, drag untuk geser</p>
    <div class="zoom-controls">
        <button onclick="zoomOut()" title="Zoom Out" id="btnZoomOut">−</button>
        <span class="zoom-level" id="zoomLevel">100%</span>
        <button onclick="zoomIn()" title="Zoom In" id="btnZoomIn">+</button>
        <button onclick="resetZoom()" title="Reset" id="btnReset">
            <i class="bi bi-arrow-counterclockwise" style="font-size: 16px;"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentZoom = 1;
const minZoom = 0.5;
const maxZoom = 5;
const zoomStep = 0.25;
let isDragging = false;
let startX, startY, translateX = 0, translateY = 0;

function openZoomViewer(element) {
    const modal = document.getElementById('zoomModal');
    const img = document.getElementById('zoomImage');
    const imageSrc = element.dataset.imageSrc;
    
    img.src = imageSrc;
    currentZoom = 1;
    translateX = 0;
    translateY = 0;
    updateZoom();
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeZoomViewer();
        }
    };
}

function closeZoomViewer() {
    const modal = document.getElementById('zoomModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

function updateZoom() {
    const img = document.getElementById('zoomImage');
    const levelText = document.getElementById('zoomLevel');
    const btnZoomIn = document.getElementById('btnZoomIn');
    const btnZoomOut = document.getElementById('btnZoomOut');
    
    img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
    levelText.textContent = Math.round(currentZoom * 100) + '%';
    
    btnZoomIn.disabled = currentZoom >= maxZoom;
    btnZoomOut.disabled = currentZoom <= minZoom;
}

function zoomIn() {
    if (currentZoom < maxZoom) {
        currentZoom = Math.min(maxZoom, currentZoom + zoomStep);
        updateZoom();
    }
}

function zoomOut() {
    if (currentZoom > minZoom) {
        currentZoom = Math.max(minZoom, currentZoom - zoomStep);
        updateZoom();
    }
}

function resetZoom() {
    currentZoom = 1;
    translateX = 0;
    translateY = 0;
    updateZoom();
}

document.getElementById('zoomImage').addEventListener('wheel', function(e) {
    e.preventDefault();
    if (e.deltaY < 0) {
        zoomIn();
    } else {
        zoomOut();
    }
});

const zoomImage = document.getElementById('zoomImage');

zoomImage.addEventListener('mousedown', function(e) {
    if (currentZoom > 1) {
        isDragging = true;
        startX = e.clientX - translateX;
        startY = e.clientY - translateY;
        zoomImage.classList.add('dragging');
    }
});

document.addEventListener('mousemove', function(e) {
    if (isDragging) {
        e.preventDefault();
        translateX = e.clientX - startX;
        translateY = e.clientY - startY;
        updateZoom();
    }
});

document.addEventListener('mouseup', function() {
    isDragging = false;
    if (zoomImage) {
        zoomImage.classList.remove('dragging');
    }
});

document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('zoomModal');
    if (!modal.classList.contains('show')) return;
    
    if (e.key === 'Escape') {
        closeZoomViewer();
    } else if (e.key === '+' || e.key === '=') {
        zoomIn();
    } else if (e.key === '-') {
        zoomOut();
    } else if (e.key === '0') {
        resetZoom();
    }
});
</script>
@endpush
