@extends('layouts.app')

@section('title', 'Detail Kegiatan')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <!-- Detail Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event text-primary me-2"></i>Detail Kegiatan
                </h5>
                <div>
                    <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h4 class="mb-3">{{ $kegiatan->nama_kegiatan }}</h4>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-calendar me-2"></i>
                            <div>
                                <small>Tanggal</small>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-clock me-2"></i>
                            <div>
                                <small>Jam</small>
                                <div class="fw-semibold">{{ \Carbon\Carbon::createFromFormat('H:i:s', $kegiatan->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $kegiatan->jam_selesai)->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3 mt-1">
                    <div class="col-6">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-geo-alt me-2"></i>
                            <div>
                                <small>Lokasi</small>
                                <div class="fw-semibold">{{ $kegiatan->lokasi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($kegiatan->deskripsi)
                    <hr>
                    <div class="mb-0">
                        <small class="text-muted">Deskripsi</small>
                        <p class="mb-0">{{ $kegiatan->deskripsi }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Link Absensi Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-link-45deg text-success me-2"></i>Link Absensi
                </h5>
            </div>
            <div class="card-body">
                <div class="url-box mb-3">
                    {{ $kegiatan->absensi_url }}
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary copy-btn" data-url="{{ $kegiatan->absensi_url }}">
                        <i class="bi bi-clipboard me-2"></i>Salin Link
                    </button>
                    <button class="btn btn-success" onclick="showQrCode('{{ $kegiatan->token }}')">
                        <i class="bi bi-qr-code me-2"></i>QR Code
                    </button>
                    <a href="{{ $kegiatan->absensi_url }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Buka Link
                    </a>
                </div>
                
                <hr>
                
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <span class="text-muted small">Token:</span>
                    <code class="small">{{ $kegiatan->token }}</code>
                    <form action="{{ route('admin.kegiatan.regenerate-token', $kegiatan) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning" 
                                onclick="return confirm('Generate token baru? Link lama tidak akan生效.')">
                            <i class="bi bi-arrow-repeat"></i> Regenerate
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <!-- Stats Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart text-info me-2"></i>Statistik
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="display-4 text-success mb-2">{{ $kegiatan->jumlah_peserta }}</div>
                <div class="text-muted">Total Peserta Hadir</div>
                <hr>
                <a href="{{ route('admin.kegiatan.absensi', $kegiatan) }}" class="btn btn-success w-100">
                    <i class="bi bi-list-check me-2"></i>Lihat Daftar Absensi
                </a>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear text-secondary me-2"></i>Aksi
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.kegiatan.absensi', $kegiatan) }}" class="btn btn-outline-success">
                        <i class="bi bi-people me-2"></i>Manajemen Absensi
                    </a>
                    <a href="{{ route('admin.kegiatan.export-excel', $kegiatan) }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                    </a>
                    <hr class="my-2">
                    <form action="{{ route('admin.kegiatan.destroy', $kegiatan) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirm('Apakah Anda yakin ingin menghapus kegiatan ini?') && document.getElementById('deleteForm').submit()">
                        <i class="bi bi-trash me-2"></i>Hapus Kegiatan
                    </button>
                </div>
            </div>
        </div>
    </div>
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
                <div class="d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1 copy-qr-url" id="copyQrUrl">
                        <i class="bi bi-clipboard me-2"></i>Salin Link
                    </button>
                    <button class="btn btn-success" id="downloadQrBtn">
                        <i class="bi bi-download me-2"></i>Download
                    </button>
                </div>
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
            colorDark: '#198754',
            correctLevel: QRCode.CorrectLevel.H
        });
        
        document.getElementById('copyQrUrl').onclick = function() {
            copyToClipboard(currentQrUrl, 'Link berhasil disalin!');
        };
        
        document.getElementById('downloadQrBtn').onclick = function() {
            const canvas = document.querySelector('#qrcodeContainer canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = 'qrcode-absensi.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
        
        new bootstrap.Modal(document.getElementById('qrcodeModal')).show();
    }
</script>
@endpush
