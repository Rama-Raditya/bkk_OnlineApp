<?php
require_once 'config/koneksi.php';

// Redirect jika sudah login
if(isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if(isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BKK Online - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-briefcase-fill"></i> BKK Online
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login_admin.php">Login Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 mb-4">Selamat Datang di BKK Online</h1>
                <p class="lead mb-4">Sistem Pendaftaran Bursa Kerja Khusus Online</p>
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-grid">
                                    <a href="register.php" class="btn btn-primary btn-lg">
                                        <i class="bi bi-person-plus-fill"></i> Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-grid">
                                    <a href="login.php" class="btn btn-outline-primary btn-lg">
                                        <i class="bi bi-box-arrow-in-right"></i> Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5">
                    <h4>Fitur Aplikasi</h4>
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <i class="bi bi-clipboard-check text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Pendaftaran Mudah</h5>
                                    <p>Proses pendaftaran cepat dan mudah</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <i class="bi bi-credit-card text-success" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Upload Bukti Bayar</h5>
                                    <p>Unggah bukti pembayaran dengan aman</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <i class="bi bi-graph-up text-warning" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Pantau Status</h5>
                                    <p>Pantau status pembayaran real-time</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2024 BKK Online. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>