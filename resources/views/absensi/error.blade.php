<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Link Tidak Valid</title>
    
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
            background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
        }
        
        .error-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #dc3545, #ff6b6b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .error-icon i {
            font-size: 3rem;
            color: white;
        }
        
        h3 {
            font-weight: 700;
            color: #dc3545;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 600;
            color: white;
        }
        
        .btn-home:hover {
            color: white;
            opacity: 0.9;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body text-center p-5">
            <img src="https://portal.kemenagnganjuk.id/logo-kemenag.webp" alt="Logo Kemenag" height="60" class="mb-3" style="object-fit: contain;">
            <div class="error-icon">
                <i class="bi bi-x-lg"></i>
            </div>
            
            <h3 class="mb-3">Link Tidak Valid</h3>
            
            <p class="text-muted mb-4">
                Maaf, tautan absensi yang Anda akses<br>
                tidak valid atau sudah tidak berlaku.
            </p>
            
            <div class="alert alert-danger py-2 px-3 d-inline-block">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <small>Kegiatan tidak ditemukan</small>
            </div>
            
            <hr class="my-4">
            
            <p class="text-muted small mb-4">
                Silakan hubungi administrator<br>
                untuk mendapatkan link absensi yang valid.
            </p>
            
            <a href="/" class="btn btn-home w-100">
                <i class="bi bi-house me-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
