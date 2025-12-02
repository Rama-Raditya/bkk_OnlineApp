<?php
require_once 'config/koneksi.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM pendaftar WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $asal_sekolah = trim($_POST['asal_sekolah']);
    $no_hp = trim($_POST['no_hp']);
    $pengalaman = $_POST['pengalaman'];
    $alamat = trim($_POST['alamat']);
    
    if(empty($nama) || empty($asal_sekolah) || empty($no_hp)) {
        $error = "Nama, Asal Sekolah, dan No HP wajib diisi!";
    } else {
        $stmt = $pdo->prepare("UPDATE pendaftar SET nama = ?, asal_sekolah = ?, no_hp = ?, pengalaman = ?, alamat = ? WHERE id = ?");
        
        if($stmt->execute([$nama, $asal_sekolah, $no_hp, $pengalaman, $alamat, $_SESSION['user_id']])) {
            $_SESSION['user_name'] = $nama;
            $success = "Profil berhasil diperbarui!";
            
            // Refresh data
            $stmt = $pdo->prepare("SELECT * FROM pendaftar WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        } else {
            $error = "Gagal memperbarui profil!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - BKK Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="bi bi-briefcase-fill"></i> BKK Online
            </a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($user['nama']) ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Profil</h4>
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
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NKK <small class="text-muted">(tidak bisa diubah)</small></label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['nkk']) ?>" disabled>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin <small class="text-muted">(tidak bisa diubah)</small></label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['jenis_kelamin']) ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Asal Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" name="asal_sekolah" class="form-control" value="<?= htmlspecialchars($user['asal_sekolah']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <small class="text-muted">(tidak bisa diubah)</small></label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No HP <span class="text-danger">*</span></label>
                                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Pengalaman Kerja</label>
                                <select name="pengalaman" class="form-select">
                                    <option value="Belum Ada" <?= $user['pengalaman'] == 'Belum Ada' ? 'selected' : '' ?>>Belum Ada</option>
                                    <option value="< 1 Tahun" <?= $user['pengalaman'] == '< 1 Tahun' ? 'selected' : '' ?>>< 1 Tahun</option>
                                    <option value="1-3 Tahun" <?= $user['pengalaman'] == '1-3 Tahun' ? 'selected' : '' ?>>1-3 Tahun</option>
                                    <option value="> 3 Tahun" <?= $user['pengalaman'] == '> 3 Tahun' ? 'selected' : '' ?>>> 3 Tahun</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($user['alamat']) ?></textarea>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>