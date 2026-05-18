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
    <meta property="og:image" content="https://ppid.kemenagnganjuk.id/logo-kemenag.png">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $kegiatan->nama_kegiatan ?? 'Form Absensi' }}">
    <meta name="twitter:description" content="Form Absensi - {{ $kegiatan->nama_kegiatan ?? 'Kegiatan' }}">
    <meta name="twitter:image" content="https://ppid.kemenagnganjuk.id/logo-kemenag.png">
    
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="https://ppid.kemenagnganjuk.id/logo-kemenag.png" alt="Logo Kemenag" height="50" class="me-3" style="object-fit: contain;">
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
                        <img src="https://ppid.kemenagnganjuk.id/logo-kemenag.png" alt="Logo Kemenag" height="80" class="mb-3" style="object-fit: contain; ">
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
                
                <!-- GPS Loader -->
                <div class="gps-loader" id="gpsLoader">
                    <div class="spinner-border mb-3" role="status"></div>
                    <p class="text-muted mb-1">Mengambil lokasi Anda...</p>
                    <small class="text-muted">Pastikan GPS aktif dan izinkan akses lokasi</small>
                </div>
                
                <!-- GPS Content (hidden initially) -->
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
                
                <!-- GPS Error -->
                <div id="gpsError" style="display:none;" class="text-center py-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-1 d-block mb-2"></i>
                    <p class="text-danger fw-semibold mb-1" id="gpsErrorMsg">GPS tidak tersedia</p>
                    <small class="text-muted">Pastikan GPS aktif dan izinkan akses lokasi di browser</small>
                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="btnRetryGps">
                            <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
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
                    
                    <!-- Hidden signature field -->
                    <input type="hidden" name="ttd" id="ttdSignature" value="">
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
        // GPS LOCATION LOGIC
        // ========================
        (function() {
            const kegiatanLat = {{ $kegiatan->latitude }};
            const kegiatanLng = {{ $kegiatan->longitude }};
            const radiusMeter = {{ $kegiatan->radius_meter }};
            
            let map, userMarker, kegiatanMarker, radiusCircle, watchId;
            window.gpsUserDalamRadius = false;

            function initGpsMap() {
                map = L.map('mapAbsensi').setView([kegiatanLat, kegiatanLng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap',
                    maxZoom: 19
                }).addTo(map);

                kegiatanMarker = L.marker([kegiatanLat, kegiatanLng]).addTo(map)
                    .bindPopup('<b>Lokasi Kegiatan</b><br>{{ $kegiatan->lokasi ?? $kegiatan->nama_kegiatan }}');

                radiusCircle = L.circle([kegiatanLat, kegiatanLng], {
                    radius: radiusMeter,
                    color: '#4caf50', fillColor: '#4caf50',
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
                document.getElementById('gpsLoader').style.display = 'none';
                document.getElementById('gpsContent').style.display = 'block';
                document.getElementById('gpsError').style.display = 'none';

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

                document.getElementById('gpsJarak').textContent = Math.round(jarak) + ' meter';
                document.getElementById('gpsAccuracy').textContent = '±' + Math.round(accuracy) + ' meter';

                const badge = document.getElementById('gpsStatusBadge');
                const submitBtn = document.getElementById('submitBtn');
                if (dalam) {
                    badge.className = 'gps-status-badge dalam';
                    badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Dalam Radius Absensi';
                    submitBtn.disabled = false;
                } else {
                    badge.className = 'gps-status-badge luar';
                    badge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Di Luar Radius (' + Math.round(jarak) + 'm)';
                    submitBtn.disabled = true;
                }

                const bounds = L.latLngBounds([[kegiatanLat, kegiatanLng], [lat, lng]]);
                map.fitBounds(bounds, {padding: [40, 40], maxZoom: 17});
            }

            function showGpsError(msg) {
                document.getElementById('gpsLoader').style.display = 'none';
                document.getElementById('gpsContent').style.display = 'none';
                document.getElementById('gpsError').style.display = 'block';
                document.getElementById('gpsErrorMsg').textContent = msg;
                document.getElementById('submitBtn').disabled = true;
            }

            function startGps() {
                if (!navigator.geolocation) {
                    showGpsError('Browser tidak mendukung GPS');
                    return;
                }
                document.getElementById('gpsLoader').style.display = 'block';
                document.getElementById('gpsContent').style.display = 'none';
                document.getElementById('gpsError').style.display = 'none';

                if (!map) initGpsMap();

                if (watchId) navigator.geolocation.clearWatch(watchId);
                watchId = navigator.geolocation.watchPosition(
                    function(pos) {
                        updateUserPosition(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
                    },
                    function(err) {
                        let msg = 'Gagal mengambil lokasi';
                        if (err.code === 1) msg = 'Akses lokasi ditolak. Aktifkan GPS.';
                        else if (err.code === 2) msg = 'Lokasi tidak tersedia.';
                        else if (err.code === 3) msg = 'Timeout mengambil lokasi.';
                        showGpsError(msg);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 5000 }
                );
            }

            // Disable submit initially until GPS verified
            document.getElementById('submitBtn').disabled = true;

            document.addEventListener('DOMContentLoaded', function() {
                startGps();
                setTimeout(function() { if(map) map.invalidateSize(); }, 500);
            });

            document.getElementById('btnRefreshGps').addEventListener('click', startGps);
            document.getElementById('btnRetryGps').addEventListener('click', startGps);
        })();
        @endif
    </script>
</body>
</html>
