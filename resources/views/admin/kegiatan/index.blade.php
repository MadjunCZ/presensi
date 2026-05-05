@extends('layouts.app')

@section('title', 'Daftar Kegiatan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h4 class="mb-0">
                <i class="bi bi-calendar-event text-primary me-2"></i>Daftar Kegiatan
            </h4>
            <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span class="d-none d-sm-inline">Buat Kegiatan</span>
            </a>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.kegiatan.index') }}">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Cari Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama kegiatan..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control" 
                           value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control" 
                           value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-filter"></i>
                        <span class="d-none d-sm-inline">Filter</span>
                    </button>
                    <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Desktop Table / Mobile Cards -->
<div class="card">
    <div class="card-body p-0">
        <!-- Desktop Table View -->
        <div class="d-none d-md-block table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th class="text-center">Peserta</th>
                        <th class="text-center">Link Absensi</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatans as $index => $kegiatan)
                    <tr>
                        <td class="text-center">{{ $kegiatans->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $kegiatan->nama_kegiatan }}</strong>
                            @if($kegiatan->deskripsi)
                                <br><small class="text-muted">{{ Str::limit($kegiatan->deskripsi, 50) }}</small>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $kegiatan->lokasi ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-success badge-count">
                                <i class="bi bi-people"></i> {{ $kegiatan->jumlah_peserta }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary copy-btn" 
                                    data-url="{{ $kegiatan->absensi_url }}"
                                    title="Salin Link">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                            <button class="btn btn-sm btn-outline-success" 
                                    onclick="showQrCode('{{ $kegiatan->token }}')"
                                    title="QR Code">
                                <i class="bi bi-qr-code"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <div class="action-buttons justify-content-center">
                                <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" 
                                   class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.kegiatan.absensi', $kegiatan) }}" 
                                   class="btn btn-sm btn-success" title="List Absensi">
                                    <i class="bi bi-list-check"></i>
                                </a>
                                <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.kegiatan.destroy', $kegiatan) }}" 
                                      method="POST" class="d-inline" 
                                      id="delete-form-{{ $kegiatan->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete('delete-form-{{ $kegiatan->id }}', '{{ $kegiatan->nama_kegiatan }}')"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada kegiatan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-md-none">
            @forelse($kegiatans as $kegiatan)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0 text-truncate" style="max-width: 60%;">
                        {{ $kegiatan->nama_kegiatan }}
                    </h6>
                    <span class="badge bg-success badge-count">
                        <i class="bi bi-people"></i> {{ $kegiatan->jumlah_peserta }}
                    </span>
                </div>
                <p class="text-muted small mb-2">
                    <i class="bi bi-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}
                </p>
                @if($kegiatan->lokasi)
                    <p class="text-muted small mb-2">
                        <i class="bi bi-geo-alt me-1"></i>{{ $kegiatan->lokasi }}
                    </p>
                @endif
                <div class="d-flex gap-2 mb-2">
                    <button class="btn btn-sm btn-outline-primary flex-grow-1 copy-btn" 
                            data-url="{{ $kegiatan->absensi_url }}">
                        <i class="bi bi-clipboard me-1"></i>Copy Link
                    </button>
                    <button class="btn btn-sm btn-outline-success" 
                            onclick="showQrCode('{{ $kegiatan->token }}')">
                        <i class="bi bi-qr-code"></i>
                    </button>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" 
                       class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    <a href="{{ route('admin.kegiatan.absensi', $kegiatan) }}" 
                       class="btn btn-sm btn-success">
                        <i class="bi bi-list-check"></i> Absensi
                    </a>
                    <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}" 
                       class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.kegiatan.destroy', $kegiatan) }}" 
                          method="POST" class="d-inline" 
                          id="delete-form-mobile-{{ $kegiatan->id }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button class="btn btn-sm btn-danger" 
                            onclick="confirmDelete('delete-form-mobile-{{ $kegiatan->id }}', '{{ $kegiatan->nama_kegiatan }}')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    Belum ada kegiatan
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Pagination -->
    @if($kegiatans->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-center">
            {{ $kegiatans->links() }}
        </div>
    </div>
    @endif
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrcodeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrcodeContainer" class="qrcode-container mb-3"></div>
                <p class="small text-muted mb-3">Scan QR Code untuk absensi</p>
                <button class="btn btn-primary w-100 copy-qr-url" id="copyQrUrl">
                    <i class="bi bi-clipboard me-2"></i>Salin Link
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    // Copy button handlers
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            copyToClipboard(this.dataset.url, 'Link absensi berhasil disalin!');
        });
    });

    // QR Code modal
    let currentQrUrl = '';
    
    function showQrCode(token) {
        currentQrUrl = '{{ url("/absensi") }}/' + token;
        document.getElementById('qrcodeContainer').innerHTML = '';
        new QRCode(document.getElementById('qrcodeContainer'), {
            text: currentQrUrl,
            width: 200,
            height: 200,
            colorDark: '#0d6efd',
            correctLevel: QRCode.CorrectLevel.H
        });
        
        document.getElementById('copyQrUrl').onclick = function() {
            copyToClipboard(currentQrUrl, 'Link berhasil disalin!');
        };
        
        new bootstrap.Modal(document.getElementById('qrcodeModal')).show();
    }
</script>
@endpush
