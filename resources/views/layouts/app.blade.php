<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Absensi Digital')</title>
    <link rel="icon" href="https://portal.kemenagnganjuk.id/logo-kemenag.webp" type="image/webp">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --bg-light: #f8fafc;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--bg-light);
            min-height: 100vh;
        }
        
        /* Mobile-first responsive */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-brand img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }
        
        /* Card styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        /* Button styles */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        /* Form styles */
        .form-control, .form-select {
            border-radius: 8px;
            min-height: 48px;
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 14px;
        }
        
        /* Table responsive */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f1f5f9;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .table tbody td {
            vertical-align: middle;
            font-size: 14px;
        }
        
        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        /* Signature pad container */
        .signature-pad-container {
            border: 2px dashed #ced4da;
            border-radius: 12px;
            background-color: #fff;
            padding: 5px;
        }
        
        .signature-pad-container canvas {
            display: block;
            width: 100%;
            touch-action: none;
        }
        
        /* Badge styles */
        .badge-count {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 20px;
        }
        
        /* Pagination */
        .pagination {
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* URL link display */
        .url-box {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 12px;
            font-family: monospace;
            font-size: 13px;
            word-break: break-all;
            border: 1px solid #e2e8f0;
        }
        
        /* Action buttons group */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        /* QR Code modal */
        .qrcode-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            display: inline-block;
        }
        
        /* Responsive text */
        @media (max-width: 768px) {
            .btn {
                min-height: 48px;
                font-size: 15px;
            }
            
            .form-control, .form-select {
                min-height: 52px;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table {
                font-size: 13px;
            }
            
            .action-buttons .btn {
                min-height: 36px;
                font-size: 12px;
                padding: 0.25rem 0.5rem;
            }
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary no-print sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.kegiatan.index') }}">
                <img src="https://portal.kemenagnganjuk.id/logo-kemenag.webp" alt="Logo Kemenag" height="40" class="me-2">
                <span>Absensi Digital</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.kegiatan.index') }}">
                            <i class="bi bi-calendar-event me-1"></i>Kegiatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.kegiatan.create') }}">
                            <i class="bi bi-plus-circle me-1"></i>Buat Kegiatan
                        </a>
                    </li>
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Profil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-fluid px-3">
            @yield('content')
        </div>
    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (for some plugins) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @stack('scripts')
    
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

        // Copy to clipboard function
        function copyToClipboard(text, successMessage = 'Link berhasil disalin!') {
            navigator.clipboard.writeText(text).then(() => {
                showToast(successMessage);
            }).catch(() => {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showToast(successMessage);
            });
        }

        // Show flash messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'danger');
        @endif

        // Delete confirmation
        function confirmDelete(formId, itemName) {
            if (confirm(`Apakah Anda yakin ingin menghapus "${itemName}"?`)) {
                document.getElementById(formId).submit();
            }
        }
    </script>
</body>
</html>
