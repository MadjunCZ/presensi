<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi - {{ $kegiatan->nama_kegiatan }}</title>

    <!-- Open Graph Meta Tags for Social Media Preview -->
    <meta property="og:title" content="{{ $kegiatan->nama_kegiatan ?? 'Form Absensi' }}">
    <meta property="og:description" content="Form Absensi - {{ $kegiatan->nama_kegiatan ?? 'Kegiatan' }}">
    <meta property="og:image" content="https://portal.kemenagnganjuk.id/logo-kemenag.webp">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $kegiatan->nama_kegiatan ?? 'Form Absensi' }}">
    <meta name="twitter:description" content="Form Absensi - {{ $kegiatan->nama_kegiatan ?? 'Kegiatan' }}">
    <meta name="twitter:image" content="https://portal.kemenagnganjuk.id/logo-kemenag.webp">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    @if($kegiatan->isGpsEnabled())
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endif
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        * {
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-bottom: 100px;
        }
        
        /* Header */
        .header-section {
            background: var(--bg-gradient);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
        }
        
        .header-section h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .header-section .subtitle {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        /* Card styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        /* Form styles */
        .form-control, .form-select {
            border-radius: 12px;
            min-height: 52px;
            font-size: 16px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 14px;
            color: #495057;
        }
        
        /* Signature Pad */
        .signature-section {
            margin-top: 1.5rem;
        }
        
        .signature-label {
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 14px;
            color: #495057;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .signature-pad-wrapper {
            border: 3px dashed #ced4da;
            border-radius: 16px;
            background-color: #fff;
            padding: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .signature-pad-wrapper canvas {
            display: block;
            width: 100%;
            height: 200px;
            touch-action: none;
            cursor: crosshair;
        }
        
        .signature-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #adb5bd;
            font-size: 14px;
            pointer-events: none;
            text-align: center;
        }
        
        .signature-pad-wrapper.has-signature .signature-placeholder {
            display: none;
        }
        
        /* Buttons */
        .btn {
            border-radius: 12px;
            font-weight: 600;
            min-height: 52px;
            font-size: 16px;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:active {
            transform: scale(0.98);
        }
        
        .btn-secondary {
            background-color: #e9ecef;
            border-color: #e9ecef;
            color: #495057;
        }
        
        .btn-clear {
            background-color: #fff;
            border: 2px solid #dc3545;
            color: #dc3545;
        }
        
        .btn-clear:hover {
            background-color: #dc3545;
            color: white;
        }
        
        /* Validation */
        .is-invalid {
            border-color: var(--danger-color) !important;
        }
        
        .invalid-feedback {
            font-size: 12px;
            margin-top: 0.5rem;
        }
        
        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            left: 20px;
            z-index: 9999;
        }
        
        /* Info box */
        .info-box {
            background: linear-gradient(to right, #e7f1ff, #ffffff);
            border-left: 4px solid var(--primary-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-box h6 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .info-box p {
            margin-bottom: 0;
            font-size: 14px;
            color: #495057;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            body {
                padding-bottom: 80px;
            }
            
            .header-section {
                padding: 1rem 0;
            }
            
            .header-section h4 {
                font-size: 1.25rem;
            }
            
            .form-control, .form-select {
                min-height: 56px;
                font-size: 16px;
            }
            
            .btn {
                min-height: 56px;
                font-size: 16px;
            }
            
            .signature-pad-wrapper canvas {
                height: 180px;
            }
        }
        /* GPS Map Styles */
        #mapAbsensi {
            height: 300px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            z-index: 1;
        }
        .gps-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .gps-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .gps-status-badge.dalam {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
        }
        .gps-status-badge.luar {
            background: linear-gradient(135deg, #ffebee, #ffcdd2);
            color: #c62828;
        }
        .gps-status-badge.loading {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #e65100;
        }
        .gps-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            font-size: 14px;
        }
        .gps-info-row + .gps-info-row {
            border-top: 1px solid #f0f0f0;
        }
        .gps-loader {
            text-align: center;
            padding: 2rem 1rem;
        }
        .gps-loader .spinner-border {
            width: 2.5rem;
            height: 2.5rem;
            color: #4caf50;
        }
        /* GPS Permission Styles */
        .gps-prompt-icon {
            width: 80px; height: 80px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
        }
        .gps-prompt-icon.ask { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
        .gps-prompt-icon.denied { background: linear-gradient(135deg, #ffebee, #ffcdd2); color: #c62828; }
        .gps-prompt-icon.warn { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }
        .gps-guide-step {
            display: flex; align-items: flex-start; gap: 0.75rem;
            padding: 0.6rem 0; font-size: 13px;
        }
        .gps-guide-step .step-num {
            min-width: 24px; height: 24px; border-radius: 50%;
            background: #4caf50; color: #fff; font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .gps-browser-tab { cursor: pointer; }
        .gps-browser-tab.active { background: #e8f5e9 !important; border-color: #4caf50 !important; }
        .gps-https-warn {
            background: linear-gradient(135deg, #fff8e1, #fff3e0);
            border: 1px solid #ffe082; border-radius: 12px;
            padding: 1rem; text-align: center; font-size: 13px; color: #e65100;
        }
        /* Camera Selfie Styles */
        .camera-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; }
        .camera-viewport {
            position: relative; width: 100%; aspect-ratio: 3/4; background: #111;
            border-radius: 12px; overflow: hidden;
        }
        .camera-viewport video, .camera-viewport img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .camera-viewport video.mirror { transform: scaleX(-1); }
        .camera-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 1rem; display: flex; justify-content: center; align-items: center; gap: 1rem;
            background: linear-gradient(transparent, rgba(0,0,0,0.6));
        }
        .camera-btn {
            width: 60px; height: 60px; border-radius: 50%; border: 3px solid #fff;
            background: rgba(255,255,255,0.2); color: #fff; font-size: 1.4rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; backdrop-filter: blur(4px);
        }
        .camera-btn:hover { background: rgba(255,255,255,0.35); }
        .camera-btn.capture { width: 68px; height: 68px; background: #4caf50; border-color: #fff; }
        .camera-btn.capture:hover { background: #388e3c; }
        .camera-btn-sm { width: 44px; height: 44px; font-size: 1.1rem; }
        .camera-countdown {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            font-size: 5rem; font-weight: 800; color: #fff;
            background: rgba(0,0,0,0.5); z-index: 5;
            text-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }
        .camera-watermark {
            position: absolute; bottom: 60px; left: 12px; right: 12px;
            color: #fff; font-size: 10px; text-shadow: 0 1px 4px rgba(0,0,0,0.8);
            pointer-events: none; line-height: 1.5;
        }
        .camera-prompt-icon {
            width: 70px; height: 70px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.75rem; font-size: 1.8rem;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #1565c0;
        }
        .camera-prompt-icon.denied { background: linear-gradient(135deg, #ffebee, #ffcdd2); color: #c62828; }
        .photo-actions { display: flex; gap: 0.5rem; justify-content: center; margin-top: 0.75rem; }
        /* Camera Location Info */
        .cam-loc-panel {
            background: linear-gradient(135deg, #f8fffe, #f0faf5);
            border: 1px solid #e0f2e9; border-radius: 12px;
            padding: 0.75rem; margin-top: 0.75rem; font-size: 13px;
        }
        .cam-loc-address { font-weight: 600; color: #2e7d32; margin-bottom: 0.25rem; }
        .cam-loc-coords { color: #666; font-size: 11px; font-family: monospace; }
        .cam-loc-time { color: #888; font-size: 11px; }
        #camMiniMap { height: 120px; border-radius: 8px; border: 1px solid #e0e0e0; margin-top: 0.5rem; z-index: 1; }
        .cam-loc-row { display: flex; align-items: center; gap: 0.5rem; padding: 2px 0; }
        .cam-loc-row i { color: #4caf50; font-size: 12px; min-width: 16px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="https://portal.kemenagnganjuk.id/logo-kemenag.webp" alt="Logo Kemenag" height="50" class="me-3" style="object-fit: contain;">
                <div>
                    <h4 class="mb-0">Form Absensi</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Live Preview Section -->
        <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-center text-center">
                    <div>
                        <img src="https://portal.kemenagnganjuk.id/logo-kemenag.webp" alt="Logo Kemenag" height="80" class="mb-3" style="object-fit: contain; ">
                        <h3 class="mb-2" style="font-weight: 700;">{{ $kegiatan->nama_kegiatan }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h6><i class="bi bi-info-circle me-2"></i>Detail Kegiatan</h6>
            <p>
                <i class="bi bi-calendar me-2"></i>
                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}
            </p>
            <p>
                <i class="bi bi-clock me-2"></i>
                {{ \Carbon\Carbon::createFromFormat('H:i:s', $kegiatan->jam_mulai)->format('H:i') }} - 
                {{ \Carbon\Carbon::createFromFormat('H:i:s', $kegiatan->jam_selesai)->format('H:i') }} WIB
            </p>
            <p>
                @if($kegiatan->lokasi)
                    <i class="bi bi-geo-alt me-2"></i>{{ $kegiatan->lokasi }}
                @endif
            </p>
        </div>

        <!-- GPS Location Card -->
        @if($kegiatan->isGpsEnabled())
        <div class="card gps-card mb-4" id="gpsCard">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-geo-alt-fill text-success me-2"></i>Verifikasi Lokasi GPS
                </h6>

                <!-- HTTPS Warning (hidden by default) -->
                <div id="gpsHttpsWarn" style="display:none;" class="gps-https-warn mb-3">
                    <i class="bi bi-shield-exclamation fs-4 d-block mb-1"></i>
                    <strong>Koneksi Tidak Aman</strong><br>
                    Fitur GPS hanya berjalan di <b>HTTPS</b> atau <b>localhost</b>.
                </div>

                <!-- No Support Warning (hidden by default) -->
                <div id="gpsNoSupport" style="display:none;" class="text-center py-4">
                    <div class="gps-prompt-icon warn"><i class="bi bi-phone-vibrate"></i></div>
                    <p class="fw-semibold mb-1">Browser Tidak Didukung</p>
                    <small class="text-muted">Browser Anda tidak mendukung fitur GPS.<br>Gunakan Chrome, Safari, atau Firefox terbaru.</small>
                </div>

                <!-- Step 1: Permission Pre-Prompt -->
                <div id="gpsPrePrompt" style="display:none;" class="text-center py-3">
                    <div class="gps-prompt-icon ask"><i class="bi bi-geo-alt"></i></div>
                    <h6 class="fw-bold mb-2">Izin Lokasi Dibutuhkan</h6>
                    <p class="text-muted mb-3" style="font-size:14px;">
                        Aplikasi absensi membutuhkan akses lokasi GPS untuk memvalidasi kehadiran Anda di area kegiatan.
                    </p>
                    <div class="d-grid">
                        <button type="button" class="btn btn-success" id="btnAllowGps" style="border-radius:12px;min-height:48px;">
                            <i class="bi bi-geo-alt-fill me-2"></i>Izinkan Lokasi
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2"><i class="bi bi-shield-check me-1"></i>Lokasi hanya digunakan untuk validasi absensi</small>
                </div>

                <!-- Step 2: Loading -->
                <div class="gps-loader" id="gpsLoader" style="display:none;">
                    <div class="spinner-border mb-3" role="status"></div>
                    <p class="text-muted mb-1">Mengambil lokasi Anda...</p>
                    <small class="text-muted">Pastikan GPS aktif dan izinkan akses lokasi</small>
                </div>

                <!-- Step 3: Map & Status -->
                <div id="gpsContent" style="display:none;">
                    <div id="mapAbsensi" class="mb-3"></div>
                    <div class="text-center mb-3">
                        <span class="gps-status-badge loading" id="gpsStatusBadge">
                            <i class="bi bi-hourglass-split"></i> Mendeteksi lokasi...
                        </span>
                    </div>
                    <div class="gps-info-row">
                        <span class="text-muted"><i class="bi bi-arrows-move me-1"></i>Jarak ke lokasi</span>
                        <strong id="gpsJarak">-</strong>
                    </div>
                    <div class="gps-info-row">
                        <span class="text-muted"><i class="bi bi-bullseye me-1"></i>Radius absensi</span>
                        <strong>{{ $kegiatan->radius_meter }} meter</strong>
                    </div>
                    <div class="gps-info-row">
                        <span class="text-muted"><i class="bi bi-reception-4 me-1"></i>Akurasi GPS</span>
                        <span id="gpsAccuracy">-</span>
                    </div>
                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnRefreshGps">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh Lokasi
                        </button>
                    </div>
                </div>

                <!-- Step 4: Error (timeout/unavailable) -->
                <div id="gpsError" style="display:none;" class="text-center py-3">
                    <div class="gps-prompt-icon warn"><i class="bi bi-exclamation-triangle"></i></div>
                    <p class="fw-semibold mb-1" id="gpsErrorMsg">GPS tidak tersedia</p>
                    <small class="text-muted" id="gpsErrorDetail">Pastikan GPS aktif dan coba lagi</small>
                    <div class="d-grid gap-2 mt-3">
                        <button type="button" class="btn btn-outline-warning" id="btnRetryGps" style="border-radius:12px;">
                            <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                        </button>
                    </div>
                </div>

                <!-- Step 5: Permission Denied -->
                <div id="gpsDenied" style="display:none;">
                    <div class="text-center mb-3">
                        <div class="gps-prompt-icon denied"><i class="bi bi-geo-alt-fill"></i></div>
                        <h6 class="fw-bold text-danger mb-1">Lokasi Ditolak</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">Anda menolak akses lokasi. Aktifkan izin lokasi agar dapat melakukan absensi.</p>
                    </div>

                    <!-- Browser guide tabs -->
                    <div class="mb-2"><small class="fw-semibold text-muted">Panduan Mengaktifkan Lokasi:</small></div>
                    <div class="d-flex gap-2 mb-3 flex-wrap">
                        <button class="btn btn-sm btn-outline-secondary gps-browser-tab active" data-target="guide-chrome-android" style="border-radius:20px;font-size:12px;">
                            <i class="bi bi-phone me-1"></i>Chrome Android
                        </button>
                        <button class="btn btn-sm btn-outline-secondary gps-browser-tab" data-target="guide-chrome-desktop" style="border-radius:20px;font-size:12px;">
                            <i class="bi bi-laptop me-1"></i>Chrome Desktop
                        </button>
                        <button class="btn btn-sm btn-outline-secondary gps-browser-tab" data-target="guide-safari" style="border-radius:20px;font-size:12px;">
                            <i class="bi bi-apple me-1"></i>Safari iPhone
                        </button>
                    </div>

                    <div class="guide-panel" id="guide-chrome-android" style="background:#f8f9fa;border-radius:12px;padding:0.75rem 1rem;">
                        <div class="gps-guide-step"><span class="step-num">1</span><span>Ketuk ikon <b><i class="bi bi-sliders"></i> pengaturan situs</b> di sebelah kiri address bar</span></div>
                        <div class="gps-guide-step"><span class="step-num">2</span><span>Ketuk <b>Permissions</b> atau <b>Izin</b></span></div>
                        <div class="gps-guide-step"><span class="step-num">3</span><span>Ubah <b>Location / Lokasi</b> menjadi <b>Allow / Izinkan</b></span></div>
                        <div class="gps-guide-step"><span class="step-num">4</span><span>Refresh halaman ini</span></div>
                    </div>
                    <div class="guide-panel" id="guide-chrome-desktop" style="display:none;background:#f8f9fa;border-radius:12px;padding:0.75rem 1rem;">
                        <div class="gps-guide-step"><span class="step-num">1</span><span>Klik ikon <b><i class="bi bi-sliders"></i> pengaturan situs</b> di kiri URL bar</span></div>
                        <div class="gps-guide-step"><span class="step-num">2</span><span>Klik <b>Site settings</b></span></div>
                        <div class="gps-guide-step"><span class="step-num">3</span><span>Ubah <b>Location</b> ke <b>Allow</b></span></div>
                        <div class="gps-guide-step"><span class="step-num">4</span><span>Refresh halaman ini</span></div>
                    </div>
                    <div class="guide-panel" id="guide-safari" style="display:none;background:#f8f9fa;border-radius:12px;padding:0.75rem 1rem;">
                        <div class="gps-guide-step"><span class="step-num">1</span><span>Buka <b>Settings / Pengaturan</b> iPhone</span></div>
                        <div class="gps-guide-step"><span class="step-num">2</span><span>Scroll ke <b>Safari</b></span></div>
                        <div class="gps-guide-step"><span class="step-num">3</span><span>Ketuk <b>Location / Lokasi</b> → pilih <b>Allow / Izinkan</b></span></div>
                        <div class="gps-guide-step"><span class="step-num">4</span><span>Kembali ke Safari dan refresh halaman</span></div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="button" class="btn btn-success" id="btnRetryDenied" style="border-radius:12px;min-height:48px;">
                            <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="location.reload()" style="border-radius:12px;">
                            <i class="bi bi-arrow-repeat me-1"></i>Refresh Halaman
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('absensi.store', $kegiatan->token) }}" method="POST" id="absensiForm">
                    @csrf
                    
                    <!-- Hidden fields -->
                    <input type="hidden" name="ttd" id="ttdSignature" value="">
                    @if($kegiatan->isSelfieRequired())
                    <input type="hidden" name="foto_selfie" id="fotoSelfieInput" value="">
                    @endif
                    @if($kegiatan->isGpsEnabled())
                    <input type="hidden" name="latitude_user" id="latitudeUser" value="">
                    <input type="hidden" name="longitude_user" id="longitudeUser" value="">
                    @endif
                    
                    <!-- NIP -->
                    <div class="mb-3">
                        <label for="nip" class="form-label">
                            NIP <span class="text-danger">*</span> <small class="text-muted">(18 digit)</small>
                        </label>
                        <input type="text" 
                               class="form-control @error('nip') is-invalid @enderror" 
                               id="nip" 
                               name="nip" 
                               value="{{ old('nip') }}"
                               placeholder="Masukkan 18 digit NIP"
                               inputmode="numeric"
                               maxlength="18"
                               pattern="[0-9]{18}"
                               required>
                        <small class="text-danger d-none" id="nipError">NIP harus 18 digit</small>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}"
                               placeholder="Masukkan nama lengkap Anda"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">
                            Jabatan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('jabatan') is-invalid @enderror" 
                                id="jabatan" 
                                name="jabatan" 
                                required
                                data-placeholder="-- Pilih Jabatan --">
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="JFT Arsiparis">JFT Arsiparis</option>
                            <option value="JFT Perencana">JFT Perencana</option>
                            <option value="JFT Humas">JFT Humas</option>
                            <option value="JFT Pranata Keuangan APBN">JFT Pranata Keuangan APBN</option>
                            <option value="JFT Analis APBN">JFT Analis APBN</option>
                            <option value="JFT Pranata Komputer">JFT Pranata Komputer</option>
                            <option value="JFT Statistisi">JFT Statistisi</option>
                            <option value="Guru">Guru</option>
                            <option value="Pengawas">Pengawas</option>
                            <option value="Jabatan Struktural">Jabatan Struktural</option>
                            <option value="Jabatan Pelaksana">Jabatan Pelaksana</option>
                            <option value="JFT Penghulu">JFT Penghulu</option>
                            <option value="JFT Penyuluh">JFT Penyuluh</option>
                            <option value="JFT Analis SDM">JFT Analis SDM</option>
                            <option value="lainnya">Lainnya...</option>
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Custom Jabatan (Hidden by default) -->
                    <div class="mb-3" id="customJabatanDiv" style="display: none;">
                        <label for="jabatanCustom" class="form-label">
                            Masukkan Jabatan Lainnya <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="jabatanCustom" 
                               name="jabatanCustom" 
                               placeholder="Contoh: Staff Keuangan"
                               value="{{ old('jabatanCustom') }}">
                    </div>

                    <!-- Satuan Kerja -->
                    <div class="mb-4">
                        <label for="satker" class="form-label">
                            Satuan Kerja <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('satker') is-invalid @enderror" 
                                id="satker" 
                                name="satker" 
                                required
                                data-placeholder="-- Pilih Satuan Kerja --">
                            <option value="">-- Pilih Satuan Kerja --</option>
                            <option value="Kantor Kementerian Agama Nganjuk">Kantor Kementerian Agama Nganjuk</option>
                            <option value="Kantor Urusan Agama Bagor">Kantor Urusan Agama Bagor</option>
                            <option value="Kantor Urusan Agama Baron">Kantor Urusan Agama Baron</option>
                            <option value="Kantor Urusan Agama Berbek">Kantor Urusan Agama Berbek</option>
                            <option value="Kantor Urusan Agama Gondang">Kantor Urusan Agama Gondang</option>
                            <option value="Kantor Urusan Agama Jatikalen">Kantor Urusan Agama Jatikalen</option>
                            <option value="Kantor Urusan Agama Kertosono">Kantor Urusan Agama Kertosono</option>
                            <option value="Kantor Urusan Agama Lengkong">Kantor Urusan Agama Lengkong</option>
                            <option value="Kantor Urusan Agama Loceret">Kantor Urusan Agama Loceret</option>
                            <option value="Kantor Urusan Agama Nganjuk">Kantor Urusan Agama Nganjuk</option>
                            <option value="Kantor Urusan Agama Ngetos">Kantor Urusan Agama Ngetos</option>
                            <option value="Kantor Urusan Agama Ngluyu">Kantor Urusan Agama Ngluyu</option>
                            <option value="Kantor Urusan Agama Ngronggot">Kantor Urusan Agama Ngronggot</option>
                            <option value="Kantor Urusan Agama Pace">Kantor Urusan Agama Pace</option>
                            <option value="Kantor Urusan Agama Patianrowo">Kantor Urusan Agama Patianrowo</option>
                            <option value="Kantor Urusan Agama Prambon">Kantor Urusan Agama Prambon</option>
                            <option value="Kantor Urusan Agama Rejoso">Kantor Urusan Agama Rejoso</option>
                            <option value="Kantor Urusan Agama Sawahan">Kantor Urusan Agama Sawahan</option>
                            <option value="Kantor Urusan Agama Sukomoro">Kantor Urusan Agama Sukomoro</option>
                            <option value="Kantor Urusan Agama Tanjunganom">Kantor Urusan Agama Tanjunganom</option>
                            <option value="Kantor Urusan Agama Wilangan">Kantor Urusan Agama Wilangan</option>
                            <option value="MAN 1 Nganjuk">MAN 1 Nganjuk</option>
                            <option value="MAN 2 Nganjuk">MAN 2 Nganjuk</option>
                            <option value="MAN 3 Nganjuk">MAN 3 Nganjuk</option>
                            <option value="MIN 1 Nganjuk">MIN 1 Nganjuk</option>
                            <option value="MIN 10 Nganjuk">MIN 10 Nganjuk</option>
                            <option value="MIN 11 Nganjuk">MIN 11 Nganjuk</option>
                            <option value="MIN 2 Nganjuk">MIN 2 Nganjuk</option>
                            <option value="MIN 3 Nganjuk">MIN 3 Nganjuk</option>
                            <option value="MIN 4 Nganjuk">MIN 4 Nganjuk</option>
                            <option value="MIN 5 Nganjuk">MIN 5 Nganjuk</option>
                            <option value="MIN 6 Nganjuk">MIN 6 Nganjuk</option>
                            <option value="MIN 7 Nganjuk">MIN 7 Nganjuk</option>
                            <option value="MIN 8 Nganjuk">MIN 8 Nganjuk</option>
                            <option value="MIN 9 Nganjuk">MIN 9 Nganjuk</option>
                            <option value="MTsN 1 Nganjuk">MTsN 1 Nganjuk</option>
                            <option value="MTsN 2 Nganjuk">MTsN 2 Nganjuk</option>
                            <option value="MTsN 3 Nganjuk">MTsN 3 Nganjuk</option>
                            <option value="MTsN 4 Nganjuk">MTsN 4 Nganjuk</option>
                            <option value="MTsN 5 Nganjuk">MTsN 5 Nganjuk</option>
                            <option value="MTsN 6 Nganjuk">MTsN 6 Nganjuk</option>
                            <option value="MTsN 7 Nganjuk">MTsN 7 Nganjuk</option>
                            <option value="MTsN 8 Nganjuk">MTsN 8 Nganjuk</option>
                            <option value="MTsN 9 Nganjuk">MTsN 9 Nganjuk</option>
                            <option value="MTsN 10 Nganjuk">MTsN 10 Nganjuk</option>
                            <option value="lainnya">Lainnya...</option>
                        </select>
                        @error('satker')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Custom Satuan Kerja (Hidden by default) -->
                    <div class="mb-4" id="customSatkerDiv" style="display: none;">
                        <label for="satkerCustom" class="form-label">
                            Masukkan Satuan Kerja Lainnya <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="satkerCustom" 
                               name="satkerCustom" 
                               placeholder="Satuan Kerja Lainnya"
                               value="{{ old('satkerCustom') }}">
                    </div>

                    @if($kegiatan->isSelfieRequired())
                    <!-- Camera Selfie Section -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-camera-fill text-primary me-1"></i>
                            Foto Selfie <span class="text-danger">*</span>
                        </label>

                        <div class="card camera-card">
                            <div class="card-body p-3">
                                <!-- Camera Pre-Prompt -->
                                <div id="cameraPrePrompt" class="text-center py-3">
                                    <div class="camera-prompt-icon"><i class="bi bi-camera"></i></div>
                                    <h6 class="fw-bold mb-1">Ambil Foto Selfie</h6>
                                    <p class="text-muted mb-3" style="font-size:13px;">Foto wajib diambil langsung dari kamera sebagai bukti kehadiran</p>
                                    <button type="button" class="btn btn-primary" id="btnStartCamera" style="border-radius:12px;min-height:44px;">
                                        <i class="bi bi-camera-video me-2"></i>Buka Kamera
                                    </button>
                                    <small class="text-muted d-block mt-2"><i class="bi bi-shield-check me-1"></i>Foto tidak dapat diupload dari galeri</small>
                                </div>

                                <!-- Camera Loading -->
                                <div id="cameraLoading" style="display:none;" class="text-center py-4">
                                    <div class="spinner-border text-primary mb-2" style="width:2rem;height:2rem;"></div>
                                    <p class="text-muted mb-0" style="font-size:13px;">Membuka kamera...</p>
                                </div>

                                <!-- Camera Live View -->
                                <div id="cameraLive" style="display:none;">
                                    <div class="camera-viewport" id="cameraViewport">
                                        <video id="cameraVideo" autoplay playsinline muted class="mirror"></video>
                                        <div class="camera-watermark" id="cameraWatermark"></div>
                                        <div id="cameraCountdownOverlay" class="camera-countdown" style="display:none;"></div>
                                        <div class="camera-overlay">
                                            <button type="button" class="camera-btn camera-btn-sm" id="btnSwitchCamera" title="Ganti Kamera">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                            <button type="button" class="camera-btn capture" id="btnCapture" title="Ambil Foto">
                                                <i class="bi bi-camera-fill"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Location Info Panel -->
                                    <div class="cam-loc-panel" id="camLocPanel">
                                        <div class="cam-loc-row">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span class="cam-loc-address" id="camLocAddress">Mendeteksi lokasi...</span>
                                        </div>
                                        <div class="cam-loc-row">
                                            <i class="bi bi-crosshair"></i>
                                            <span class="cam-loc-coords" id="camLocCoords">-</span>
                                        </div>
                                        <div class="cam-loc-row">
                                            <i class="bi bi-reception-4"></i>
                                            <span class="cam-loc-coords" id="camLocAccuracy">-</span>
                                        </div>
                                        <div class="cam-loc-row">
                                            <i class="bi bi-clock"></i>
                                            <span class="cam-loc-time" id="camLocTime">-</span>
                                        </div>
                                        <div id="camMiniMap"></div>
                                    </div>
                                </div>

                                <!-- Captured Photo Preview -->
                                <div id="cameraPreview" style="display:none;">
                                    <div class="camera-viewport">
                                        <img id="capturedPhoto" alt="Foto Selfie">
                                    </div>
                                    <div class="photo-actions">
                                        <button type="button" class="btn btn-outline-warning flex-grow-1" id="btnRetake" style="border-radius:10px;">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Ambil Ulang
                                        </button>
                                        <button type="button" class="btn btn-success flex-grow-1" id="btnUsePhoto" style="border-radius:10px;">
                                            <i class="bi bi-check-lg me-1"></i>Gunakan Foto
                                        </button>
                                    </div>
                                </div>

                                <!-- Photo Confirmed -->
                                <div id="cameraConfirmed" style="display:none;">
                                    <div class="camera-viewport">
                                        <img id="confirmedPhoto" alt="Foto Selfie">
                                    </div>
                                    <div class="text-center mt-2">
                                        <span class="gps-status-badge dalam"><i class="bi bi-check-circle-fill"></i> Foto berhasil diambil</span>
                                    </div>
                                    <div class="d-grid mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnRetakeConfirmed" style="border-radius:10px;">
                                            <i class="bi bi-camera me-1"></i>Ambil Ulang
                                        </button>
                                    </div>
                                </div>

                                <!-- Camera Error -->
                                <div id="cameraError" style="display:none;" class="text-center py-3">
                                    <div class="camera-prompt-icon denied"><i class="bi bi-camera-video-off"></i></div>
                                    <p class="fw-semibold text-danger mb-1" id="cameraErrorMsg">Kamera tidak tersedia</p>
                                    <small class="text-muted" id="cameraErrorDetail">Pastikan izin kamera diaktifkan</small>
                                    <div class="d-grid gap-2 mt-3">
                                        <button type="button" class="btn btn-outline-primary" id="btnRetryCamera" style="border-radius:12px;">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Signature Pad -->
                    <div class="signature-section">
                        <div class="signature-label">
                            <span>
                                Tanda Tangan <span class="text-danger">*</span>
                            </span>
                            <button type="button" class="btn btn-sm btn-clear" id="clearBtn">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Clear
                            </button>
                        </div>
                        <div class="signature-pad-wrapper @error('ttd') border-danger @enderror" id="signatureWrapper">
                            <canvas id="signaturePad"></canvas>
                            <div class="signature-placeholder">
                                <i class="bi bi-pen fs-4 d-block mb-2"></i>
                                Tanda tangan di area ini
                            </div>
                        </div>
                        @error('ttd')
                            <div class="text-danger mt-2" style="font-size: 12px;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted mt-2 d-block">
                            * Tanda tangan dengan jari atau stylus
                        </small>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i>Simpan Absensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Signature Pad JS -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    @if($kegiatan->isGpsEnabled())
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endif
    
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            const container = document.getElementById('toastContainer');
            container.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }

        // Select2 Initialization
        document.addEventListener('DOMContentLoaded', function() {
            const satkerSelect = $('#satker');
            const customSatkerDiv = document.getElementById('customSatkerDiv');
            const customSatkerInput = document.getElementById('satkerCustom');

            // Initialize Select2
            satkerSelect.select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih Satuan Kerja --'
            });

            // Handle Select2 change event
            satkerSelect.on('change', function() {
                const selectedValue = $(this).val();
                
                if (selectedValue === 'lainnya') {
                    customSatkerDiv.style.display = 'block';
                    customSatkerInput.required = true;
                } else {
                    customSatkerDiv.style.display = 'none';
                    customSatkerInput.required = false;
                    customSatkerInput.value = '';
                }
            });

            // Trigger change event on load if "lainnya" is already selected
            if (satkerSelect.val() === 'lainnya') {
                satkerSelect.trigger('change');
            }

            // Initialize Jabatan Select2
            const jabatanSelect = $('#jabatan');
            const customJabatanDiv = document.getElementById('customJabatanDiv');
            const customJabatanInput = document.getElementById('jabatanCustom');

            jabatanSelect.select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih Jabatan --'
            });

            // Handle Jabatan Select2 change event
            jabatanSelect.on('change', function() {
                const selectedValue = $(this).val();
                
                if (selectedValue === 'lainnya') {
                    customJabatanDiv.style.display = 'block';
                    customJabatanInput.required = true;
                } else {
                    customJabatanDiv.style.display = 'none';
                    customJabatanInput.required = false;
                    customJabatanInput.value = '';
                }
            });

            // Trigger change event on load if "lainnya" is already selected
            if (jabatanSelect.val() === 'lainnya') {
                jabatanSelect.trigger('change');
            }

            // NIP Validation
            const nipInput = document.getElementById('nip');
            const nipError = document.getElementById('nipError');

            nipInput.addEventListener('blur', function() {
                const nipValue = this.value.trim();
                if (nipValue && nipValue.length !== 18) {
                    nipError.classList.remove('d-none');
                    this.classList.add('is-invalid');
                } else {
                    nipError.classList.add('d-none');
                    if (nipValue) {
                        this.classList.remove('is-invalid');
                    }
                }
            });
        });

        // Signature Pad Setup
        const canvas = document.getElementById('signaturePad');
        const wrapper = document.getElementById('signatureWrapper');
        const signatureInput = document.getElementById('ttdSignature');
        const clearBtn = document.getElementById('clearBtn');
        const form = document.getElementById('absensiForm');
        const submitBtn = document.getElementById('submitBtn');

        // Set canvas size
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
        }

        // Initialize Signature Pad
        resizeCanvas();
        
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 1,
            maxWidth: 3,
            velocityFilterWeight: 0.7
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const data = signaturePad.toData();
            resizeCanvas();
            signaturePad.clear();
            signaturePad.fromData(data);
        });

        // Update signature input and wrapper class
        function updateSignature() {
            if (signaturePad.isEmpty()) {
                wrapper.classList.remove('has-signature');
                signatureInput.value = '';
            } else {
                wrapper.classList.add('has-signature');
                signatureInput.value = signaturePad.toDataURL('image/png');
            }
        }

        signaturePad.addEventListener('endStroke', updateSignature);

        // Clear button
        clearBtn.addEventListener('click', function() {
            signaturePad.clear();
            wrapper.classList.remove('has-signature');
            signatureInput.value = '';
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            // Validate NIP
            const nipValue = document.getElementById('nip').value.trim();
            if (!nipValue || nipValue.length !== 18) {
                e.preventDefault();
                showToast('NIP harus 18 digit!', 'danger');
                document.getElementById('nip').focus();
                return false;
            }

            // Handle custom jabatan value
            const jabatanValue = document.getElementById('jabatan').value;
            if (jabatanValue === 'lainnya') {
                const customValue = document.getElementById('jabatanCustom').value;
                if (!customValue || customValue.trim() === '') {
                    e.preventDefault();
                    showToast('Masukkan jabatan lainnya!', 'danger');
                    return false;
                }
                // Set the select value to custom input
                document.getElementById('jabatan').value = customValue;
            }

            // Handle custom satker value
            const satkerValue = document.getElementById('satker').value;
            if (satkerValue === 'lainnya') {
                const customValue = document.getElementById('satkerCustom').value;
                if (!customValue || customValue.trim() === '') {
                    e.preventDefault();
                    showToast('Masukkan satuan kerja lainnya!', 'danger');
                    return false;
                }
                // Set the select value to custom input
                document.getElementById('satker').value = customValue;
            }

            if (signaturePad.isEmpty()) {
                e.preventDefault();
                showToast('Tanda tangan wajib diisi!', 'danger');
                canvas.focus();
                return false;
            }

            @if($kegiatan->isSelfieRequired())
            // Validate foto selfie
            if (!document.getElementById('fotoSelfieInput').value) {
                e.preventDefault();
                showToast('Foto selfie wajib diambil!', 'danger');
                return false;
            }
            @endif

            @if($kegiatan->isGpsEnabled())
            // Validate GPS
            const latVal = document.getElementById('latitudeUser').value;
            const lngVal = document.getElementById('longitudeUser').value;
            if (!latVal || !lngVal) {
                e.preventDefault();
                showToast('Lokasi GPS belum terdeteksi!', 'danger');
                return false;
            }
            if (typeof window.gpsUserDalamRadius !== 'undefined' && !window.gpsUserDalamRadius) {
                e.preventDefault();
                showToast('Anda berada di luar area absensi!', 'danger');
                return false;
            }
            @endif
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        });

        // Show error from session
        @if(session('error'))
            showToast('{{ session('error') }}', 'danger');
        @endif

        @if($kegiatan->isGpsEnabled())
        // ========================
        // GPS LOCATION LOGIC (Enhanced Permission Handling)
        // ========================

        // Camera Selfie Logic with Location Info
        @if($kegiatan->isSelfieRequired())
        (function() {
            let stream = null;
            let facingMode = 'user';
            let capturedDataUrl = null;
            let camMiniMap = null, camMiniMarker = null, camMiniCircle = null;
            let camAddress = '';
            let camLat = null, camLng = null, camAcc = null;
            let geocodeTimeout = null;
            const video = document.getElementById('cameraVideo');
            const fotoInput = document.getElementById('fotoSelfieInput');

            const camEls = {
                prePrompt: document.getElementById('cameraPrePrompt'),
                loading: document.getElementById('cameraLoading'),
                live: document.getElementById('cameraLive'),
                preview: document.getElementById('cameraPreview'),
                confirmed: document.getElementById('cameraConfirmed'),
                error: document.getElementById('cameraError'),
                errorMsg: document.getElementById('cameraErrorMsg'),
                errorDetail: document.getElementById('cameraErrorDetail'),
                watermark: document.getElementById('cameraWatermark'),
                countdown: document.getElementById('cameraCountdownOverlay'),
                locAddress: document.getElementById('camLocAddress'),
                locCoords: document.getElementById('camLocCoords'),
                locAccuracy: document.getElementById('camLocAccuracy'),
                locTime: document.getElementById('camLocTime')
            };

            function hideCamAll() {
                ['prePrompt','loading','live','preview','confirmed','error'].forEach(k => {
                    if (camEls[k]) camEls[k].style.display = 'none';
                });
            }
            function showCamPanel(name) {
                hideCamAll();
                if (camEls[name]) camEls[name].style.display = 'block';
            }
            function stopStream() {
                if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
            }

            // Reverse geocoding via Nominatim
            function reverseGeocode(lat, lng) {
                if (geocodeTimeout) clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(() => {
                    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat='+lat+'&lon='+lng+'&zoom=18&addressdetails=1', {
                        headers: { 'Accept-Language': 'id' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.address) {
                            const a = data.address;
                            const parts = [];
                            if (a.road) parts.push(a.road);
                            if (a.house_number) parts[0] = (parts[0]||'') + ' No. ' + a.house_number;
                            const area = a.village || a.suburb || a.neighbourhood || '';
                            const kec = a.municipality || a.city_district || a.county || '';
                            const kota = a.city || a.town || a.regency || '';
                            const prov = a.state || '';
                            if (area) parts.push(area);
                            if (kec && kec !== area) parts.push(kec);
                            if (kota) parts.push(kota);
                            if (prov && prov !== kota) parts.push(prov);
                            camAddress = parts.join(', ') || data.display_name || '';
                        } else {
                            camAddress = 'Alamat tidak ditemukan';
                        }
                        if (camEls.locAddress) camEls.locAddress.textContent = camAddress;
                    })
                    .catch(() => {
                        camAddress = 'Gagal memuat alamat';
                        if (camEls.locAddress) camEls.locAddress.textContent = camAddress;
                    });
                }, 1500); // debounce
            }

            // Init mini map
            function initCamMiniMap(lat, lng) {
                if (camMiniMap) {
                    camMiniMap.setView([lat, lng], 17);
                    return;
                }
                const el = document.getElementById('camMiniMap');
                if (!el) return;
                camMiniMap = L.map(el, { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false }).setView([lat, lng], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(camMiniMap);
                @if($kegiatan->isGpsEnabled())
                L.circle([{{ $kegiatan->latitude }}, {{ $kegiatan->longitude }}], {
                    radius: {{ $kegiatan->radius_meter }}, color: '#4caf50', fillColor: '#4caf50', fillOpacity: 0.1, weight: 1, dashArray: '4,6'
                }).addTo(camMiniMap);
                L.marker([{{ $kegiatan->latitude }}, {{ $kegiatan->longitude }}]).addTo(camMiniMap);
                @endif
            }

            function updateCamMiniMap(lat, lng, acc) {
                initCamMiniMap(lat, lng);
                const userIcon = L.divIcon({
                    html: '<div style="width:12px;height:12px;background:#1976d2;border:2px solid #fff;border-radius:50%;box-shadow:0 0 6px rgba(25,118,210,0.5);"></div>',
                    iconSize: [12,12], iconAnchor: [6,6], className: ''
                });
                if (camMiniMarker) camMiniMarker.setLatLng([lat, lng]);
                else camMiniMarker = L.marker([lat, lng], { icon: userIcon }).addTo(camMiniMap);
                if (camMiniCircle) camMiniCircle.setLatLng([lat, lng]).setRadius(acc);
                else camMiniCircle = L.circle([lat, lng], { radius: acc, color: '#1976d2', fillOpacity: 0.08, weight: 1 }).addTo(camMiniMap);
                camMiniMap.setView([lat, lng], 17);
                setTimeout(() => { if(camMiniMap) camMiniMap.invalidateSize(); }, 300);
            }

            // Update location info panel
            function updateCamLocationInfo() {
                @if($kegiatan->isGpsEnabled())
                camLat = parseFloat(document.getElementById('latitudeUser').value) || null;
                camLng = parseFloat(document.getElementById('longitudeUser').value) || null;
                const accEl = document.getElementById('gpsAccuracy');
                camAcc = accEl ? parseFloat(accEl.textContent.replace(/[^\d.]/g,'')) : null;
                @endif
                if (camLat && camLng) {
                    camEls.locCoords.textContent = 'Lat: ' + camLat.toFixed(6) + '  Lng: ' + camLng.toFixed(6);
                    if (camAcc) camEls.locAccuracy.textContent = 'Akurasi: ±' + Math.round(camAcc) + ' meter';
                    reverseGeocode(camLat, camLng);
                    updateCamMiniMap(camLat, camLng, camAcc || 50);
                }
                const now = new Date();
                camEls.locTime.textContent = now.toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}) + ' ' + now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'}) + ' WIB';
            }

            function updateWatermark() {
                const now = new Date();
                const dateStr = now.toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'});
                const timeStr = now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
                let text = '{{ $kegiatan->nama_kegiatan }}<br>' + dateStr + ' ' + timeStr;
                if (camAddress) text += '<br>' + camAddress;
                if (camLat && camLng) text += '<br>Lat: ' + camLat.toFixed(6) + ' Lng: ' + camLng.toFixed(6);
                camEls.watermark.innerHTML = text;
            }

            async function openCamera() {
                showCamPanel('loading');
                stopStream();
                try {
                    const constraints = {
                        video: { facingMode: facingMode, width: {ideal: 720}, height: {ideal: 960} },
                        audio: false
                    };
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                    video.srcObject = stream;
                    video.classList.toggle('mirror', facingMode === 'user');
                    showCamPanel('live');
                    updateCamLocationInfo();
                    updateWatermark();
                    window._camWmInterval = setInterval(() => { updateWatermark(); updateCamLocationInfo(); }, 5000);
                    setTimeout(() => { if(camMiniMap) camMiniMap.invalidateSize(); }, 600);
                } catch(err) {
                    let msg = 'Kamera tidak tersedia', detail = 'Pastikan izin kamera diaktifkan di browser.';
                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        msg = 'Izin Kamera Ditolak'; detail = 'Aktifkan izin kamera di pengaturan browser.';
                    } else if (err.name === 'NotFoundError') {
                        msg = 'Kamera Tidak Ditemukan'; detail = 'Perangkat ini tidak memiliki kamera.';
                    } else if (err.name === 'NotReadableError') {
                        msg = 'Kamera Sedang Digunakan'; detail = 'Tutup aplikasi lain yang menggunakan kamera.';
                    }
                    camEls.errorMsg.textContent = msg;
                    camEls.errorDetail.textContent = detail;
                    showCamPanel('error');
                }
            }

            function doCountdownAndCapture() {
                let count = 3;
                camEls.countdown.style.display = 'flex';
                camEls.countdown.textContent = count;
                document.getElementById('btnCapture').disabled = true;
                const interval = setInterval(() => {
                    count--;
                    if (count > 0) { camEls.countdown.textContent = count; }
                    else {
                        clearInterval(interval);
                        camEls.countdown.style.display = 'none';
                        capturePhoto();
                        document.getElementById('btnCapture').disabled = false;
                    }
                }, 800);
            }

            function capturePhoto() {
                updateWatermark();
                const vw = video.videoWidth, vh = video.videoHeight;
                const c = document.createElement('canvas');
                let sw = vw, sh = Math.round(vw * 4 / 3);
                if (sh > vh) { sh = vh; sw = Math.round(vh * 3 / 4); }
                const sx = Math.round((vw - sw) / 2), sy = Math.round((vh - sh) / 2);
                c.width = Math.min(sw, 720);
                c.height = Math.round(c.width * 4 / 3);
                const ctx = c.getContext('2d');
                if (facingMode === 'user') { ctx.translate(c.width, 0); ctx.scale(-1, 1); }
                ctx.drawImage(video, sx, sy, sw, sh, 0, 0, c.width, c.height);
                ctx.setTransform(1, 0, 0, 1, 0, 0);

                // Build watermark lines
                const now = new Date();
                const dateStr = now.toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'});
                const timeStr = now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
                let wmLines = ['{{ $kegiatan->nama_kegiatan }}'];
                if (camAddress) wmLines.push(camAddress);
                wmLines.push(dateStr + ' ' + timeStr + ' WIB');
                if (camLat && camLng) wmLines.push('Lat: ' + camLat.toFixed(6) + '  Lng: ' + camLng.toFixed(6));

                const fontSize = Math.max(Math.round(c.width * 0.026), 12);
                const lineH = fontSize + 5;
                const blockH = wmLines.length * lineH + 16;
                ctx.fillStyle = 'rgba(0,0,0,0.5)';
                ctx.fillRect(0, c.height - blockH, c.width, blockH);
                ctx.font = '600 ' + fontSize + 'px Inter, sans-serif';
                ctx.fillStyle = '#fff';
                ctx.shadowColor = 'rgba(0,0,0,0.8)'; ctx.shadowBlur = 3;
                wmLines.forEach((line, i) => {
                    ctx.fillText(line, 10, c.height - blockH + lineH * (i + 1));
                });
                ctx.shadowBlur = 0;

                capturedDataUrl = c.toDataURL('image/jpeg', 0.75);
                document.getElementById('capturedPhoto').src = capturedDataUrl;
                showCamPanel('preview');
                stopStream();
                if (window._camWmInterval) clearInterval(window._camWmInterval);
            }

            function confirmPhoto() {
                fotoInput.value = capturedDataUrl;
                document.getElementById('confirmedPhoto').src = capturedDataUrl;
                showCamPanel('confirmed');
            }
            function retakePhoto() {
                capturedDataUrl = null;
                fotoInput.value = '';
                openCamera();
            }

            document.getElementById('btnStartCamera').addEventListener('click', openCamera);
            document.getElementById('btnCapture').addEventListener('click', doCountdownAndCapture);
            document.getElementById('btnSwitchCamera').addEventListener('click', function() {
                facingMode = facingMode === 'user' ? 'environment' : 'user';
                openCamera();
            });
            document.getElementById('btnRetake').addEventListener('click', retakePhoto);
            document.getElementById('btnUsePhoto').addEventListener('click', confirmPhoto);
            document.getElementById('btnRetakeConfirmed').addEventListener('click', retakePhoto);
            document.getElementById('btnRetryCamera').addEventListener('click', openCamera);
        })();
        @endif
        (function() {
            const kegiatanLat = {{ $kegiatan->latitude }};
            const kegiatanLng = {{ $kegiatan->longitude }};
            const radiusMeter = {{ $kegiatan->radius_meter }};

            let map, userMarker, kegiatanMarker, radiusCircle, watchId;
            window.gpsUserDalamRadius = false;

            const els = {
                prePrompt: document.getElementById('gpsPrePrompt'),
                loader: document.getElementById('gpsLoader'),
                content: document.getElementById('gpsContent'),
                error: document.getElementById('gpsError'),
                denied: document.getElementById('gpsDenied'),
                noSupport: document.getElementById('gpsNoSupport'),
                httpsWarn: document.getElementById('gpsHttpsWarn'),
                errorMsg: document.getElementById('gpsErrorMsg'),
                errorDetail: document.getElementById('gpsErrorDetail'),
                badge: document.getElementById('gpsStatusBadge'),
                jarak: document.getElementById('gpsJarak'),
                accuracy: document.getElementById('gpsAccuracy'),
                submitBtn: document.getElementById('submitBtn')
            };

            function hideAll() {
                ['prePrompt','loader','content','error','denied','noSupport'].forEach(k => {
                    if (els[k]) els[k].style.display = 'none';
                });
            }

            function showPanel(name) {
                hideAll();
                if (els[name]) els[name].style.display = name === 'content' ? 'block' : (name === 'denied' ? 'block' : '');
                // For flex/block panels
                if (els[name]) els[name].style.display = ['prePrompt','loader','error','noSupport'].includes(name) ? 'block' : 'block';
            }

            // Check HTTPS
            function isSecureContext() {
                return location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
            }

            // Init map
            function initGpsMap() {
                if (map) return;
                map = L.map('mapAbsensi').setView([kegiatanLat, kegiatanLng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap', maxZoom: 19
                }).addTo(map);
                kegiatanMarker = L.marker([kegiatanLat, kegiatanLng]).addTo(map)
                    .bindPopup('<b>Lokasi Kegiatan</b><br>{{ $kegiatan->lokasi ?? $kegiatan->nama_kegiatan }}');
                radiusCircle = L.circle([kegiatanLat, kegiatanLng], {
                    radius: radiusMeter, color: '#4caf50', fillColor: '#4caf50',
                    fillOpacity: 0.12, weight: 2, dashArray: '5, 10'
                }).addTo(map);
            }

            function haversine(lat1, lng1, lat2, lng2) {
                const R = 6371000;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLng = (lng2 - lng1) * Math.PI / 180;
                const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)**2;
                return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            }

            function updateUserPosition(lat, lng, accuracy) {
                showPanel('content');
                document.getElementById('latitudeUser').value = lat.toFixed(8);
                document.getElementById('longitudeUser').value = lng.toFixed(8);

                const userIcon = L.divIcon({
                    html: '<div style="width:16px;height:16px;background:#1976d2;border:3px solid #fff;border-radius:50%;box-shadow:0 0 8px rgba(25,118,210,0.5);"></div>',
                    iconSize: [16, 16], iconAnchor: [8, 8], className: ''
                });
                if (userMarker) { userMarker.setLatLng([lat, lng]); }
                else { userMarker = L.marker([lat, lng], {icon: userIcon}).addTo(map).bindPopup('Posisi Anda'); }

                const jarak = haversine(kegiatanLat, kegiatanLng, lat, lng);
                const dalam = jarak <= radiusMeter;
                window.gpsUserDalamRadius = dalam;

                els.jarak.textContent = Math.round(jarak) + ' meter';
                els.accuracy.textContent = '±' + Math.round(accuracy) + ' meter';

                if (dalam) {
                    els.badge.className = 'gps-status-badge dalam';
                    els.badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Dalam Radius Absensi';
                    els.submitBtn.disabled = false;
                } else {
                    els.badge.className = 'gps-status-badge luar';
                    els.badge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Di Luar Radius (' + Math.round(jarak) + 'm)';
                    els.submitBtn.disabled = true;
                }

                const bounds = L.latLngBounds([[kegiatanLat, kegiatanLng], [lat, lng]]);
                map.fitBounds(bounds, {padding: [40, 40], maxZoom: 17});
            }

            function showDenied() {
                showPanel('denied');
                els.submitBtn.disabled = true;
            }

            function showError(msg, detail) {
                showPanel('error');
                els.errorMsg.textContent = msg;
                if (detail) els.errorDetail.textContent = detail;
                els.submitBtn.disabled = true;
            }

            function requestLocation() {
                showPanel('loader');
                initGpsMap();
                if (watchId) navigator.geolocation.clearWatch(watchId);
                watchId = navigator.geolocation.watchPosition(
                    function(pos) {
                        updateUserPosition(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
                    },
                    function(err) {
                        if (err.code === 1) {
                            showDenied();
                        } else if (err.code === 2) {
                            showError('GPS Tidak Tersedia', 'Perangkat tidak dapat menentukan lokasi. Pastikan GPS aktif.');
                        } else if (err.code === 3) {
                            showError('Timeout Lokasi', 'Gagal mengambil lokasi dalam waktu yang ditentukan. Coba lagi.');
                        } else {
                            showError('Gagal Mengambil Lokasi', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    { enableHighAccuracy: true, timeout: 15000, maximumAge: 5000 }
                );
                setTimeout(function() { if(map) map.invalidateSize(); }, 500);
            }

            // Main initialization
            async function initGps() {
                els.submitBtn.disabled = true;

                // Check HTTPS
                if (!isSecureContext()) {
                    els.httpsWarn.style.display = 'block';
                }

                // Check geolocation support
                if (!navigator.geolocation) {
                    showPanel('noSupport');
                    return;
                }

                // Use Permissions API if available
                if (navigator.permissions && navigator.permissions.query) {
                    try {
                        const result = await navigator.permissions.query({ name: 'geolocation' });

                        if (result.state === 'granted') {
                            // Already granted - go straight to location
                            requestLocation();
                        } else if (result.state === 'denied') {
                            // Denied - show guide
                            showDenied();
                        } else {
                            // prompt - show pre-prompt card
                            showPanel('prePrompt');
                        }

                        // Listen for permission changes (user changes in settings)
                        result.addEventListener('change', function() {
                            if (result.state === 'granted') {
                                requestLocation();
                            } else if (result.state === 'denied') {
                                showDenied();
                            }
                        });
                    } catch(e) {
                        // Permissions API failed (e.g. Safari), show pre-prompt
                        showPanel('prePrompt');
                    }
                } else {
                    // No Permissions API (older browsers), show pre-prompt
                    showPanel('prePrompt');
                }
            }

            // Event listeners
            document.addEventListener('DOMContentLoaded', function() {
                initGps();

                // "Izinkan Lokasi" button
                document.getElementById('btnAllowGps').addEventListener('click', requestLocation);

                // Refresh button
                document.getElementById('btnRefreshGps').addEventListener('click', requestLocation);

                // Retry from error
                document.getElementById('btnRetryGps').addEventListener('click', requestLocation);

                // Retry from denied
                document.getElementById('btnRetryDenied').addEventListener('click', function() {
                    // Try requesting again - if still denied, browser won't show popup
                    requestLocation();
                });

                // Browser guide tabs
                document.querySelectorAll('.gps-browser-tab').forEach(function(tab) {
                    tab.addEventListener('click', function() {
                        document.querySelectorAll('.gps-browser-tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.guide-panel').forEach(p => p.style.display = 'none');
                        this.classList.add('active');
                        const target = document.getElementById(this.getAttribute('data-target'));
                        if (target) target.style.display = 'block';
                    });
                });
            });
        })();
        @endif
        // ========================
        // CAMERA SELFIE LOGIC (non-GPS kegiatan)
        // ========================
        @if(!$kegiatan->isGpsEnabled())
        (function() {
            let stream = null;
            let facingMode = 'user';
            let capturedDataUrl = null;
            let camMiniMap = null, camMiniMarker = null;
            let camAddress = '';
            let camLat = null, camLng = null, camAcc = null;
            let geocodeTimeout = null, camWatchId = null;
            const video = document.getElementById('cameraVideo');
            const fotoInput = document.getElementById('fotoSelfieInput');

            const camEls = {
                prePrompt: document.getElementById('cameraPrePrompt'),
                loading: document.getElementById('cameraLoading'),
                live: document.getElementById('cameraLive'),
                preview: document.getElementById('cameraPreview'),
                confirmed: document.getElementById('cameraConfirmed'),
                error: document.getElementById('cameraError'),
                errorMsg: document.getElementById('cameraErrorMsg'),
                errorDetail: document.getElementById('cameraErrorDetail'),
                watermark: document.getElementById('cameraWatermark'),
                countdown: document.getElementById('cameraCountdownOverlay'),
                locAddress: document.getElementById('camLocAddress'),
                locCoords: document.getElementById('camLocCoords'),
                locAccuracy: document.getElementById('camLocAccuracy'),
                locTime: document.getElementById('camLocTime')
            };

            function hideCamAll() {
                ['prePrompt','loading','live','preview','confirmed','error'].forEach(k => {
                    if (camEls[k]) camEls[k].style.display = 'none';
                });
            }
            function showCamPanel(name) { hideCamAll(); if (camEls[name]) camEls[name].style.display = 'block'; }
            function stopStream() { if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; } }

            function reverseGeocode(lat, lng) {
                if (geocodeTimeout) clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(() => {
                    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat='+lat+'&lon='+lng+'&zoom=18&addressdetails=1', {
                        headers: { 'Accept-Language': 'id' }
                    }).then(r => r.json()).then(data => {
                        if (data && data.address) {
                            const a = data.address, parts = [];
                            if (a.road) parts.push(a.road + (a.house_number ? ' No. '+a.house_number : ''));
                            const area = a.village||a.suburb||a.neighbourhood||'';
                            const kota = a.city||a.town||a.regency||'';
                            if (area) parts.push(area);
                            if (kota) parts.push(kota);
                            if (a.state && a.state !== kota) parts.push(a.state);
                            camAddress = parts.join(', ') || data.display_name || '';
                        } else { camAddress = ''; }
                        if (camEls.locAddress) camEls.locAddress.textContent = camAddress || 'Alamat tidak ditemukan';
                    }).catch(() => { camAddress = 'Gagal memuat alamat'; if (camEls.locAddress) camEls.locAddress.textContent = camAddress; });
                }, 1500);
            }

            function initCamMiniMap(lat, lng) {
                if (camMiniMap) { camMiniMap.setView([lat, lng], 17); return; }
                const el = document.getElementById('camMiniMap'); if (!el) return;
                camMiniMap = L.map(el, { zoomControl:false, attributionControl:false, dragging:false, scrollWheelZoom:false, doubleClickZoom:false }).setView([lat,lng],17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(camMiniMap);
            }

            function startCamGeo() {
                if (!navigator.geolocation) return;
                camWatchId = navigator.geolocation.watchPosition(pos => {
                    camLat = pos.coords.latitude; camLng = pos.coords.longitude; camAcc = pos.coords.accuracy;
                    camEls.locCoords.textContent = 'Lat: '+camLat.toFixed(6)+'  Lng: '+camLng.toFixed(6);
                    camEls.locAccuracy.textContent = 'Akurasi: ±'+Math.round(camAcc)+' meter';
                    reverseGeocode(camLat, camLng);
                    initCamMiniMap(camLat, camLng);
                    const userIcon = L.divIcon({ html:'<div style="width:12px;height:12px;background:#1976d2;border:2px solid #fff;border-radius:50%;box-shadow:0 0 6px rgba(25,118,210,0.5);"></div>', iconSize:[12,12], iconAnchor:[6,6], className:'' });
                    if (camMiniMarker) camMiniMarker.setLatLng([camLat,camLng]);
                    else camMiniMarker = L.marker([camLat,camLng],{icon:userIcon}).addTo(camMiniMap);
                    camMiniMap.setView([camLat,camLng],17);
                    setTimeout(() => { if(camMiniMap) camMiniMap.invalidateSize(); }, 300);
                }, () => {}, { enableHighAccuracy:true, timeout:10000, maximumAge:5000 });
            }

            function updateCamTime() {
                const now = new Date();
                camEls.locTime.textContent = now.toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'})+' '+now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'})+' WIB';
            }

            function updateWatermark() {
                const now = new Date();
                let text = '{{ $kegiatan->nama_kegiatan }}<br>'+now.toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'})+' '+now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
                if (camAddress) text += '<br>'+camAddress;
                if (camLat&&camLng) text += '<br>Lat: '+camLat.toFixed(6)+' Lng: '+camLng.toFixed(6);
                camEls.watermark.innerHTML = text;
            }

            async function openCamera() {
                showCamPanel('loading'); stopStream();
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video:{facingMode:facingMode,width:{ideal:720},height:{ideal:960}}, audio:false });
                    video.srcObject = stream;
                    video.classList.toggle('mirror', facingMode==='user');
                    showCamPanel('live');
                    startCamGeo(); updateCamTime(); updateWatermark();
                    window._camWmInterval = setInterval(() => { updateWatermark(); updateCamTime(); }, 5000);
                    setTimeout(() => { if(camMiniMap) camMiniMap.invalidateSize(); }, 600);
                } catch(err) {
                    let msg='Kamera tidak tersedia', detail='Pastikan izin kamera diaktifkan.';
                    if (err.name==='NotAllowedError') { msg='Izin Kamera Ditolak'; detail='Aktifkan izin kamera di pengaturan browser.'; }
                    else if (err.name==='NotFoundError') { msg='Kamera Tidak Ditemukan'; detail='Perangkat ini tidak memiliki kamera.'; }
                    camEls.errorMsg.textContent=msg; camEls.errorDetail.textContent=detail; showCamPanel('error');
                }
            }

            function doCountdownAndCapture() {
                let count=3; camEls.countdown.style.display='flex'; camEls.countdown.textContent=count;
                document.getElementById('btnCapture').disabled=true;
                const interval=setInterval(() => {
                    count--;
                    if (count>0) camEls.countdown.textContent=count;
                    else { clearInterval(interval); camEls.countdown.style.display='none'; capturePhoto(); document.getElementById('btnCapture').disabled=false; }
                }, 800);
            }

            function capturePhoto() {
                const vw=video.videoWidth, vh=video.videoHeight, c=document.createElement('canvas');
                let sw=vw, sh=Math.round(vw*4/3);
                if (sh>vh) { sh=vh; sw=Math.round(vh*3/4); }
                const sx=Math.round((vw-sw)/2), sy=Math.round((vh-sh)/2);
                c.width=Math.min(sw,720); c.height=Math.round(c.width*4/3);
                const ctx=c.getContext('2d');
                if (facingMode==='user') { ctx.translate(c.width,0); ctx.scale(-1,1); }
                ctx.drawImage(video, sx, sy, sw, sh, 0, 0, c.width, c.height);
                ctx.setTransform(1,0,0,1,0,0);

                const now=new Date();
                let wmLines=['{{ $kegiatan->nama_kegiatan }}'];
                if (camAddress) wmLines.push(camAddress);
                wmLines.push(now.toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'})+' '+now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'})+' WIB');
                if (camLat&&camLng) wmLines.push('Lat: '+camLat.toFixed(6)+'  Lng: '+camLng.toFixed(6));

                const fontSize=Math.max(Math.round(c.width*0.026),12), lineH=fontSize+5, blockH=wmLines.length*lineH+16;
                ctx.fillStyle='rgba(0,0,0,0.5)'; ctx.fillRect(0,c.height-blockH,c.width,blockH);
                ctx.font='600 '+fontSize+'px Inter, sans-serif'; ctx.fillStyle='#fff'; ctx.shadowColor='rgba(0,0,0,0.8)'; ctx.shadowBlur=3;
                wmLines.forEach((line,i) => { ctx.fillText(line, 10, c.height-blockH+lineH*(i+1)); });

                capturedDataUrl=c.toDataURL('image/jpeg',0.75);
                document.getElementById('capturedPhoto').src=capturedDataUrl;
                showCamPanel('preview'); stopStream();
                if (window._camWmInterval) clearInterval(window._camWmInterval);
                if (camWatchId) { navigator.geolocation.clearWatch(camWatchId); camWatchId=null; }
            }

            document.getElementById('btnStartCamera').addEventListener('click', openCamera);
            document.getElementById('btnCapture').addEventListener('click', doCountdownAndCapture);
            document.getElementById('btnSwitchCamera').addEventListener('click', function() { facingMode=facingMode==='user'?'environment':'user'; openCamera(); });
            document.getElementById('btnRetake').addEventListener('click', function() { capturedDataUrl=null; fotoInput.value=''; openCamera(); });
            document.getElementById('btnUsePhoto').addEventListener('click', function() {
                fotoInput.value=capturedDataUrl; document.getElementById('confirmedPhoto').src=capturedDataUrl; showCamPanel('confirmed');
            });
            document.getElementById('btnRetakeConfirmed').addEventListener('click', function() { capturedDataUrl=null; fotoInput.value=''; openCamera(); });
            document.getElementById('btnRetryCamera').addEventListener('click', openCamera);
        })();
        @endif
    </script>
</body>
</html>

