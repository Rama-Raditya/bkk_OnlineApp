<?php
require_once 'config/koneksi.php';
require_once 'wa_helper.php'; // Include WhatsApp helper

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $nkk = trim($_POST['nkk']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $asal_sekolah = trim($_POST['asal_sekolah']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $pengalaman = $_POST['pengalaman'];
    $alamat = trim($_POST['alamat']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi
    if(empty($nama) || empty($nkk) || empty($email) || empty($password)) {
        $error = "Semua field wajib diisi!";
    } elseif($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } elseif(strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // Cek duplikasi NKK dan Email
        $stmt = $pdo->prepare("SELECT id FROM pendaftar WHERE nkk = ? OR email = ?");
        $stmt->execute([$nkk, $email]);
        
        if($stmt->rowCount() > 0) {
            $error = "NKK atau Email sudah terdaftar!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert data
            $stmt = $pdo->prepare("INSERT INTO pendaftar (nama, nkk, jenis_kelamin, asal_sekolah, email, no_hp, pengalaman, alamat, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if($stmt->execute([$nama, $nkk, $jenis_kelamin, $asal_sekolah, $email, $no_hp, $pengalaman, $alamat, $hashed_password])) {
                // Kirim notifikasi WhatsApp
                kirimNotifRegistrasi($no_hp, $nama, $nkk);
                
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - BKK Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-briefcase-fill"></i> BKK Online
            </a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Form Pendaftaran</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="bi bi-check-circle-fill"></i> <?= $success ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <a href="login.php" class="btn btn-primary">Login Sekarang</a>
                        <?php else: ?>
                        
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NKK (Nomor Kartu Keluarga) <span class="text-danger">*</span></label>
                                    <input type="text" name="nkk" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Asal Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" name="asal_sekolah" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No HP <span class="text-danger">*</span></label>
                                    <input type="text" name="no_hp" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Pengalaman Kerja <span class="text-danger">*</span></label>
                                <select name="pengalaman" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Belum Ada">Belum Ada</option>
                                    <option value="< 1 Tahun">< 1 Tahun</option>
                                    <option value="1-3 Tahun">1-3 Tahun</option>
                                    <option value="> 3 Tahun">> 3 Tahun</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="alamat" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Daftar Sekarang
                                </button>
                                <a href="login.php" class="btn btn-outline-secondary">
                                    Sudah punya akun? Login
                                </a>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>