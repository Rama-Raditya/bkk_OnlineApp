<?php
require_once 'config/koneksi.php';

if(!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil parameter page
 $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Statistik
 $stmt = $pdo->query("SELECT COUNT(DISTINCT id) as total FROM pendaftar");
 $total_pendaftar = $stmt->fetch()['total'];

 $stmt = $pdo->query("SELECT COUNT(*) as total FROM pembayaran WHERE status = 'pending'");
 $total_pending = $stmt->fetch()['total'];

 $stmt = $pdo->query("SELECT COUNT(*) as total FROM pembayaran WHERE status = 'approved'");
 $total_approved = $stmt->fetch()['total'];

 $stmt = $pdo->query("SELECT COUNT(*) as total FROM pembayaran WHERE status = 'rejected'");
 $total_rejected = $stmt->fetch()['total'];

// Fungsi untuk status badge
function getStatusBadge($status) {
    if(!$status) return '<span class="badge bg-secondary">Belum Bayar</span>';
    switch($status) {
        case 'approved':
            return '<span class="badge bg-success">Lunas</span>';
        case 'rejected':
            return '<span class="badge bg-danger">Ditolak</span>';
        default:
            return '<span class="badge bg-warning text-dark">Pending</span>';
    }
}

// Handle notification
 $success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
 $error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BKK Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #2c3e50;
            padding-top: 20px;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 15px 20px;
            color: white;
            font-size: 1.3rem;
            font-weight: bold;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover {
            background: #34495e;
            padding-left: 30px;
        }
        .sidebar-menu a.active {
            background: #3498db;
        }
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
        }
        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 15px 20px;
            border-top: 1px solid #34495e;
            color: #ecf0f1;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }
        .top-navbar {
            background: white;
            padding: 15px 30px;
            margin: -20px -20px 20px -20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* --- PERUBAHAN DIMULAI DI SINI --- */
        /* Gaya baru untuk Quick Actions (Stats Cards) */
        .quick-stats-card {
            transition: transform 0.2s ease-in-out;
        }
        .quick-stats-card:hover {
            transform: translateY(-5px);
        }
        .quick-stats-card .card-body {
            padding: 1.5rem;
        }
        .quick-stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .quick-stats-card h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .quick-stats-card p {
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        /* --- PERUBAHAN BERAKHIR DI SINI --- */

        .content-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .content-card .card-header {
            background: white;
            border-bottom: 2px solid #e9ecef;
            padding: 15px 20px;
            font-weight: 600;
            color: #2c3e50;
        }
        .table-responsive {
            border-radius: 0 0 8px 8px;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-briefcase-fill"></i> BKK Online
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="admin.php?page=dashboard" class="<?= $page == 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="admin.php?page=pendaftar" class="<?= $page == 'pendaftar' ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Pendaftar</span>
                </a>
            </li>
            <li>
                <a href="admin.php?page=pembayaran" class="<?= $page == 'pembayaran' ? 'active' : '' ?>">
                    <i class="bi bi-credit-card-fill"></i>
                    <span>Riwayat Pembayaran</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                <div>
                    <small>Logged in as:</small><br>
                    <strong><?= $_SESSION['admin_name'] ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div>
                <h4 class="mb-0">
                    <?php 
                    switch($page) {
                        case 'pendaftar': echo 'Data Pendaftar'; break;
                        case 'pembayaran': echo 'Riwayat Pembayaran'; break;
                        default: echo 'Dashboard';
                    }
                    ?>
                </h4>
                <small class="text-muted">
                    <?php 
                    switch($page) {
                        case 'pendaftar': echo 'Kelola data pendaftar'; break;
                        case 'pembayaran': echo 'Kelola pembayaran'; break;
                        default: echo 'Dashboard';
                    }
                    ?>
                </small>
            </div>
            <div class="d-flex align-items-center">
                <input type="text" class="form-control me-3" placeholder="Search for..." style="width: 300px;">
                <a href="logout.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i> <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($page == 'dashboard'): ?>
            <!-- --- PERUBAHAN DIMULAI DI SINI --- -->
            <!-- Quick Actions (Stats Cards) Baru -->
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="admin.php?page=pendaftar" class="text-decoration-none">
                        <div class="card quick-stats-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="text-primary"><?= $total_pendaftar ?></h2>
                                    <p class="text-muted">Total Pendaftar</p>
                                </div>
                                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="admin.php?page=pembayaran" class="text-decoration-none">
                        <div class="card quick-stats-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="text-warning"><?= $total_pending ?></h2>
                                    <p class="text-muted">Pending Approval</p>
                                </div>
                                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="admin.php?page=pembayaran" class="text-decoration-none">
                        <div class="card quick-stats-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="text-success"><?= $total_approved ?></h2>
                                    <p class="text-muted">Approved</p>
                                </div>
                                <div class="stats-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="admin.php?page=pembayaran" class="text-decoration-none">
                        <div class="card quick-stats-card border-0 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="text-danger"><?= $total_rejected ?></h2>
                                    <p class="text-muted">Rejected</p>
                                </div>
                                <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- --- PERUBAHAN BERAKHIR DI SINI --- -->

            <!-- Quick Info -->
            <div class="row">
                <div class="col-md-12">
                    <div class="content-card">
                        <div class="card-header">
                            <i class="bi bi-info-circle"></i> Informasi Dashboard
                        </div>
                        <div class="card-body p-4">
                            <h5>Selamat Datang, Administrator!</h5>
                            <p class="mb-3">Berikut adalah ringkasan sistem BKK Online:</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <strong><?= $total_pendaftar ?></strong> Total Pendaftar Terdaftar</li>
                                        <li class="mb-2"><i class="bi bi-clock text-warning"></i> <strong><?= $total_pending ?></strong> Pembayaran Menunggu Verifikasi</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <strong><?= $total_approved ?></strong> Pembayaran Disetujui</li>
                                        <li class="mb-2"><i class="bi bi-x-circle text-danger"></i> <strong><?= $total_rejected ?></strong> Pembayaran Ditolak</li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex gap-2">
                                <a href="admin.php?page=pendaftar" class="btn btn-primary">
                                    <i class="bi bi-people"></i> Lihat Data Pendaftar
                                </a>
                                <a href="admin.php?page=pembayaran" class="btn btn-success">
                                    <i class="bi bi-credit-card"></i> Kelola Pembayaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif($page == 'pendaftar'): ?>
            <!-- Data Pendaftar -->
            <?php
            $stmt = $pdo->query("SELECT * FROM pendaftar ORDER BY id DESC");
            $pendaftar = $stmt->fetchAll();
            ?>
            <div class="content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people-fill"></i> Data Pendaftar</span>
                    <span class="badge bg-primary"><?= count($pendaftar) ?> Pendaftar</span>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <select class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                                <span class="ms-2 text-muted">entries per page</span>
                            </div>
                            <div class="col-md-6 text-end">
                                <input type="text" id="searchPendaftar" class="form-control form-control-sm" placeholder="Cari nama, NKK, email..." style="width: 300px; display: inline-block;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NKK</th>
                                    <th>JK</th>
                                    <th>Asal Sekolah</th>
                                    <th>Email</th>
                                    <th>No HP</th>
                                    <th>Pengalaman</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody id="tablePendaftar">
                                <?php if(count($pendaftar) > 0): ?>
                                    <?php foreach($pendaftar as $index => $p): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($p['nama']) ?></strong></td>
                                        <td><?= htmlspecialchars($p['nkk']) ?></td>
                                        <td><?= htmlspecialchars($p['jenis_kelamin']) ?></td>
                                        <td><?= htmlspecialchars($p['asal_sekolah']) ?></td>
                                        <td><?= htmlspecialchars($p['email']) ?></td>
                                        <td><?= htmlspecialchars($p['no_hp']) ?></td>
                                        <td><span class="badge bg-info"><?= htmlspecialchars($p['pengalaman']) ?></span></td>
                                        <td><?= htmlspecialchars(substr($p['alamat'], 0, 30)) ?>...</td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">Belum ada data pendaftar</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php elseif($page == 'pembayaran'): ?>
            <!-- Riwayat Pembayaran -->
            <?php
            $stmt = $pdo->query("
                SELECT 
                    p.nama,
                    p.nkk,
                    p.email,
                    pay.id as payment_id,
                    pay.tanggal,
                    pay.jumlah,
                    pay.bukti,
                    pay.status
                FROM pembayaran pay
                JOIN pendaftar p ON pay.id_pendaftar = p.id
                ORDER BY pay.tanggal DESC
            ");
            $pembayaran = $stmt->fetchAll();
            ?>
            <div class="content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-credit-card-fill"></i> Riwayat Pembayaran</span>
                    <span class="badge bg-success"><?= count($pembayaran) ?> Transaksi</span>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <select class="form-select form-select-sm" id="filterStatus" style="width: auto; display: inline-block;">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-6 text-end">
                                <input type="text" id="searchPembayaran" class="form-control form-control-sm" placeholder="Cari nama, NKK..." style="width: 300px; display: inline-block;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pendaftar</th>
                                    <th>NKK</th>
                                    <th>Email</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Bukti</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tablePembayaran">
                                <?php if(count($pembayaran) > 0): ?>
                                    <?php foreach($pembayaran as $index => $pay): ?>
                                    <tr data-status="<?= $pay['status'] ?>">
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($pay['nama']) ?></strong></td>
                                        <td><?= htmlspecialchars($pay['nkk']) ?></td>
                                        <td><?= htmlspecialchars($pay['email']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($pay['tanggal'])) ?></td>
                                        <td><strong>Rp <?= number_format($pay['jumlah'], 0, ',', '.') ?></strong></td>
                                        <td>
                                            <a href="uploads/<?= htmlspecialchars($pay['bukti']) ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                        </td>
                                        <td><?= getStatusBadge($pay['status']) ?></td>
                                        <td>
                                            <?php if($pay['status'] == 'pending'): ?>
                                                <form action="proses_admin.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="payment_id" value="<?= $pay['payment_id'] ?>">
                                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm" onclick="return confirm('Setujui pembayaran ini?')" title="Approve">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran ini?')" title="Reject">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small">
                                                    <i class="bi bi-check-circle"></i> Diproses
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">Belum ada data pembayaran</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search Pendaftar
        document.getElementById('searchPendaftar')?.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tablePendaftar tr');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Search Pembayaran
        document.getElementById('searchPembayaran')?.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tablePembayaran tr');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Filter Status
        document.getElementById('filterStatus')?.addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#tablePembayaran tr');
            
            rows.forEach(row => {
                if(filter === '') {
                    row.style.display = '';
                } else {
                    let status = row.getAttribute('data-status');
                    row.style.display = status === filter ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>