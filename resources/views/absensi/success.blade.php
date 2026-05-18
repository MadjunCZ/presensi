<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Absensi Berhasil</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
        }
        
        .card-body {
            padding: 2.5rem 1.5rem;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #198754, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }
        
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        h3 {
            font-weight: 700;
            color: #198754;
            font-size: 1.75rem;
            word-break: break-word;
        }
        
        .detail-item {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 0.875rem;
            margin-bottom: 0.75rem;
            border-left: 4px solid #198754;
        }
        
        .detail-label {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
            letter-spacing: 0.5px;
        }
        
        .detail-value {
            font-size: 0.95rem;
            color: #212529;
            font-weight: 600;
            word-break: break-word;
            line-height: 1.4;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #198754, #20c997);
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.2s ease;
        }
        
        .btn-home:hover {
            color: white;
            opacity: 0.9;
            transform: scale(1.02);
        }
        
        hr {
            margin: 1rem 0;
            opacity: 0.3;
        }
        
        /* Mobile Responsive */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .card {
                border-radius: 16px;
            }
            
            .card-body {
                padding: 1.5rem 1rem;
            }
            
            .success-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 1rem;
            }
            
            .success-icon i {
                font-size: 2.2rem;
            }
            
            h3 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .text-muted {
                font-size: 0.9rem !important;
                line-height: 1.5;
            }
            
            .alert {
                font-size: 0.85rem;
                padding: 0.5rem 0.75rem !important;
            }
            
            .detail-item {
                padding: 0.75rem;
                margin-bottom: 0.5rem;
            }
            
            .detail-label {
                font-size: 0.65rem;
            }
            
            .detail-value {
                font-size: 0.9rem;
            }
            
            h6 {
                font-size: 1rem;
                margin-bottom: 1rem !important;
            }
            
            .btn-home {
                padding: 12px 20px;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 400px) {
            .card-body {
                padding: 1.25rem 0.75rem;
            }
            
            .success-icon {
                width: 70px;
                height: 70px;
                margin: 0 auto 0.75rem;
            }
            
            .success-icon i {
                font-size: 1.8rem;
            }
            
            h3 {
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }
            
            .detail-item {
                padding: 0.625rem;
                margin-bottom: 0.375rem;
            }
            
            .detail-label {
                font-size: 0.6rem;
            }
            
            .detail-value {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    @php
        $kegiatan_id = session('kegiatan_id');
        $absensi_id = session('absensi_id');
        $kegiatan = $kegiatan_id ? \App\Models\Kegiatan::find($kegiatan_id) : null;
        $absensi = $absensi_id ? \App\Models\Absensi::find($absensi_id) : null;
    @endphp

    <div class="card">
        <div class="card-body text-center">
            <img src="https://portal.kemenagnganjuk.id/logo-kemenag.webp" alt="Logo Kemenag" height="60" class="mb-3" style="object-fit: contain;">
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>
            
            <h3 class="mb-3">Absensi Berhasil!</h3>
            
            <p class="text-muted mb-4">
                Terima kasih telah melakukan absensi.<br>
                Data kehadiran Anda telah tercatat.
            </p>
            
            <div class="alert alert-success py-2 px-3 d-inline-block mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                <small>Absensi berhasil disimpan</small>
            </div>

            @if($kegiatan || $absensi)
                <hr class="my-4">
                
                <div class="text-start mb-4">
                    <h6 class="text-dark mb-3 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Detail Kegiatan
                    </h6>

                    @if($kegiatan)
                        <div class="detail-item">
                            <div class="detail-label">Nama Kegiatan</div>
                            <div class="detail-value">{{ $kegiatan->nama_kegiatan }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Tanggal Kegiatan</div>
                            <div class="detail-value">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('l, d F Y') }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Lokasi</div>
                            <div class="detail-value">{{ $kegiatan->lokasi ?? '-' }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Penyelenggara</div>
                            <div class="detail-value">{{ $kegiatan->penyelenggara ?? '-' }}</div>
                        </div>
                    @endif

                    @if($absensi)
                        <div class="detail-item">
                            <div class="detail-label">Waktu Absensi</div>
                            <div class="detail-value">
                                {{ $absensi->waktu_absensi->translatedFormat('H:i:s') }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Nama Peserta</div>
                            <div class="detail-value">{{ $absensi->nama }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">NIP</div>
                            <div class="detail-value">{{ $absensi->nip }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Jabatan</div>
                            <div class="detail-value">{{ $absensi->jabatan }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Satuan Kerja</div>
                            <div class="detail-value">{{ $absensi->satker }}</div>
                        </div>
                    @endif
                </div>
            @endif

            <hr class="my-4">
            
            <p class="text-muted small mb-4">
                Anda dapat menutup halaman ini atau<br>
                membuka tautan absensi lain.
            </p>
        </div>
    </div>

    <!-- Auto redirect after 5 seconds -->
    <script>
        setTimeout(function() {
            // Optional: auto redirect
            // window.location.href = '/';
        }, 5000);
    </script>
</body>
</html>
