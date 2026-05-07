<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Absensi Belum Dibuka{{ $kegiatan ? ' - ' . $kegiatan->nama_kegiatan : '' }}</title>

    <!-- Open Graph Meta Tags for Social Media Preview -->
    <meta property="og:title" content="{{ $kegiatan->nama_kegiatan ?? 'Absensi Belum Dibuka' }}">
    <meta property="og:description" content="Absensi Belum Dibuka - {{ $kegiatan->nama_kegiatan ?? 'Kegiatan' }}">
    <meta property="og:image" content="https://ppid.kemenagnganjuk.id/logo-kemenag.png">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $kegiatan->nama_kegiatan ?? 'Absensi Belum Dibuka' }}">
    <meta name="twitter:description" content="Absensi Belum Dibuka - {{ $kegiatan->nama_kegiatan ?? 'Kegiatan' }}">
    <meta name="twitter:image" content="https://ppid.kemenagnganjuk.id/logo-kemenag.png">
    
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
            background: linear-gradient(135deg, #0dcaf0 0%, #0d99ff 100%);
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
        
        .status-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #0dcaf0, #0d99ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }
        
        .status-icon i {
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
            color: #0d99ff;
            font-size: 1.75rem;
            word-break: break-word;
        }
        
        .detail-item {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 0.875rem;
            margin-bottom: 0.75rem;
            border-left: 4px solid #0dcaf0;
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
        
        .countdown-box {
            background: linear-gradient(135deg, #0dcaf0, #0d99ff);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .countdown-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        
        .countdown-time {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            line-height: 1.3;
        }
        
        .countdown-time-days {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
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
            
            .status-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 1rem;
            }
            
            .status-icon i {
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
            
            .countdown-box {
                padding: 1.25rem;
            }
            
            .countdown-time {
                font-size: 2rem;
            }
            
            .countdown-time-days {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 400px) {
            .card-body {
                padding: 1.25rem 0.75rem;
            }
            
            .status-icon {
                width: 70px;
                height: 70px;
                margin: 0 auto 0.75rem;
            }
            
            .status-icon i {
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
            
            .countdown-box {
                padding: 1rem;
            }
            
            .countdown-time {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body text-center">
            <img src="https://ppid.kemenagnganjuk.id/logo-kemenag.png" alt="Logo Kemenag" height="60" class="mb-3" style="object-fit: contain;">
            <div class="status-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            
            <h3 class="mb-3">Absensi Belum Dibuka</h3>
            
            <p class="text-muted mb-4">
                Absensi akan dibuka sesuai jadwal kegiatan.
            </p>
            
            <div class="alert alert-info py-2 px-3 d-inline-block mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                <small>Silahkan tunggu hingga absensi dibuka</small>
            </div>

            @if($kegiatan)
                <hr class="my-4">
                
                <div class="text-start mb-4">
                    <h6 class="text-dark mb-3 fw-bold">
                        <i class="bi bi-calendar-event me-2"></i>Detail Kegiatan
                    </h6>

                    <div class="detail-item">
                        <div class="detail-label">Nama Kegiatan</div>
                        <div class="detail-value">{{ $kegiatan->nama_kegiatan }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tanggal</div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('l, d F Y') }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Jam Mulai</div>
                        <div class="detail-value">{{ $kegiatan->jam_mulai }} WIB</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Jam Selesai</div>
                        <div class="detail-value">{{ $kegiatan->jam_selesai }} WIB</div>
                    </div>

                    @if($kegiatan->lokasi)
                        <div class="detail-item">
                            <div class="detail-label">Lokasi</div>
                            <div class="detail-value">{{ $kegiatan->lokasi }}</div>
                        </div>
                    @endif
                </div>

                <div class="countdown-box">
                    <div class="countdown-label">Absensi dibuka dalam:</div>
                    <div class="countdown-time">
                        <div id="countdownDays" class="countdown-time-days" style="display: none;"></div>
                        <div id="countdown">--:--:--</div>
                    </div>
                </div>
            @endif

            <hr class="my-4">
            
            <p class="text-muted small mb-0">
                <i class="bi bi-info-circle me-2"></i>
                Halaman ini akan otomatis refresh setiap 10 detik
            </p>
        </div>
    </div>

    <script>
        // Update countdown every second
        function updateCountdown() {
            const now = new Date();
            
            @php
                $jamMulai = \Carbon\Carbon::parse($kegiatan->tanggal->toDateString() . ' ' . $kegiatan->jam_mulai);
                $serverTime = now()->toDateTimeString();
            @endphp
            
            // Using server time as reference
            const targetTime = new Date('{{ $jamMulai->toIso8601String() }}').getTime();
            const currentTime = new Date().getTime();
            const diff = targetTime - currentTime;
            
            if (diff <= 0) {
                // Auto refresh if time is up
                location.reload();
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = String(Math.floor((diff / (1000 * 60 * 60)) % 24)).padStart(2, '0');
            const minutes = String(Math.floor((diff / 1000 / 60) % 60)).padStart(2, '0');
            const seconds = String(Math.floor((diff / 1000) % 60)).padStart(2, '0');
            
            const countdownDaysEl = document.getElementById('countdownDays');
            const countdownEl = document.getElementById('countdown');
            
            if (days > 0) {
                countdownDaysEl.style.display = 'block';
                countdownDaysEl.textContent = `${days} hari`;
                countdownEl.textContent = `${hours}:${minutes}:${seconds}`;
            } else {
                countdownDaysEl.style.display = 'none';
                countdownEl.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        
        // Update immediately
        updateCountdown();
        
        // Update every second
        setInterval(updateCountdown, 1000);
        
        // Auto refresh every 10 seconds
        setTimeout(() => {
            location.reload();
        }, 10000);
    </script>
</body>
</html>
