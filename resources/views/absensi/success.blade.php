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
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
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
        }
        
        .btn-home {
            background: linear-gradient(135deg, #198754, #20c997);
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
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>
            
            <h3 class="mb-3">Absensi Berhasil!</h3>
            
            <p class="text-muted mb-4">
                Terima kasih telah melakukan absensi.<br>
                Data kehadiran Anda telah tercatat.
            </p>
            
            <div class="alert alert-success py-2 px-3 d-inline-block">
                <i class="bi bi-check-circle-fill me-2"></i>
                <small>Absensi berhasil disimpan</small>
            </div>
            
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
