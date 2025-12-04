<?php
require_once 'config/koneksi.php';

// Cek login
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM pendaftar WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Ambil data pembayaran
$stmt = $pdo->prepare("SELECT * FROM pembayaran WHERE id_pendaftar = ? ORDER BY tanggal DESC");
$stmt->execute([$_SESSION['user_id']]);
$pembayaran = $stmt->fetchAll();

// Fungsi untuk status badge
function getStatusBadge($status) {
    switch($status) {
        case 'approved':
            return '<span class="badge bg-success">Lunas</span>';
        case 'rejected':
            return '<span class="badge bg-danger">Ditolak</span>';
        default:
            return '<span class="badge bg-warning text-dark">Menunggu Verifikasi</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BKK Online</title>
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
        <h3 class="mb-4">Dashboard Pengguna</h3>
        
        <div class="row">
            <!-- Data Diri -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Data Diri</h5>
                        <a href="edit_profil.php" class="btn btn-light btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama</strong></td>
                                <td>: <?= htmlspecialchars($user['nama']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>NKK</strong></td>
                                <td>: <?= htmlspecialchars($user['nkk']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>: <?= htmlspecialchars($user['jenis_kelamin']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Asal Sekolah</strong></td>
                                <td>: <?= htmlspecialchars($user['asal_sekolah']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: <?= htmlspecialchars($user['email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>No HP</strong></td>
                                <td>: <?= htmlspecialchars($user['no_hp']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Pengalaman</strong></td>
                                <td>: <?= htmlspecialchars($user['pengalaman']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: <?= htmlspecialchars($user['alamat']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Upload Bukti Bayar -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-upload"></i> Upload Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                <strong>Ketentuan Upload:</strong><br>
                                • Format: JPG, PNG, atau PDF<br>
                                • Ukuran maksimal: 2MB<br>
                                • Pastikan bukti jelas dan terbaca
                            </small>
                        </div>
                        
                        <form action="upload_bayar.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Bayar (Rp)</label>
                                <input type="number" name="jumlah" class="form-control" placeholder="Contoh: 100000" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Bukti Pembayaran</label>
                                <input type="file" name="bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-cloud-upload"></i> Upload Bukti
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Pembayaran -->
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</h5>
            </div>
            <div class="card-body">
                <?php if(count($pembayaran) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Bukti</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pembayaran as $index => $p): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($p['tanggal'])) ?></td>
                                <td>Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="uploads/<?= htmlspecialchars($p['bukti']) ?>" target="_blank" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                                <td><?= getStatusBadge($p['status']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-secondary text-center">
                    <i class="bi bi-inbox"></i> Belum ada riwayat pembayaran
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>