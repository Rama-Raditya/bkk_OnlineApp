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
    <style>
        .hero-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
            border-radius: 20px;
            margin-bottom: 60px;
        }
        .feature-icon {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            font-size: 2.5rem;
            margin-bottom: 25px;
            transition: transform 0.3s ease;
        }
        .feature-icon:hover {
            transform: translateY(-10px);
        }
        .testimonial-card {
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .brand-logo {
            height: 50px;
            filter: grayscale(100%);
            opacity: 0.6;
            transition: all 0.3s ease;
        }
        .brand-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.05);
        }
        .stat-card {
            text-align: center;
            padding: 30px;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 15px;
            line-height: 1;
        }
        .feature-card {
            border-radius: 20px;
            padding: 40px;
            height: 100%;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: white;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .process-step {
            position: relative;
            text-align: center;
            padding: 30px 20px;
        }
        .process-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 60px;
            right: -50%;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #0d6efd, #e9ecef);
            z-index: -1;
        }
        @media (max-width: 768px) {
            .process-step:not(:last-child)::after {
                display: none;
            }
        }
        .step-number {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .step-number:hover {
            transform: scale(1.1);
        }
        .cta-button {
            padding: 18px 50px;
            font-size: 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 10px;
        }
        .cta-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(13, 110, 253, 0.3);
        }
        .testimonial-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f8f9fa;
        }
        .rating {
            color: #ffc107;
        }
        .section-padding {
            padding: 80px 0;
        }
        .section-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 80px;
            height: 5px;
            background: #0d6efd;
            border-radius: 3px;
        }
        .hero-image {
            max-width: 100%;
            height: auto;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .job-card {
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .job-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .badge-custom {
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
        }
        .faq-item {
            margin-bottom: 15px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .accordion-button:not(.collapsed) {
            background-color: #0d6efd;
            color: white;
        }
        .accordion-button:focus {
            box-shadow: none;
        }
    </style>
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

    <!-- Hero Section -->
    <div class="container section-padding">
        <div class="hero-section p-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Platform Bursa Kerja Khusus Terpercaya</h1>
                    <p class="lead mb-4">Menghubungkan talenta berkualitas dengan perusahaan terkemuka. Bergabunglah dengan ribuan alumni yang telah berhasil mendapatkan pekerjaan impian mereka.</p>
                    
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start">
                        <a href="register.php" class="btn btn-primary btn-lg cta-button">
                            <i class="bi bi-person-plus-fill me-2"></i> Daftar Sekarang
                        </a>
                        <a href="login.php" class="btn btn-outline-primary btn-lg cta-button">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <img src="https://widyaadalah.sch.id/wp-content/uploads/2025/09/Ilustrasi-konsep-BKK-Bursa-Kerja-Khusus-sebagai-jembatan-yang-menghubungkan-siswa-lulusan-SMK-berseragam-dengan-gedung-perkantoran-dunia-industri.2.954Z-1024x559.webp" alt="Hero Image" class="hero-image img-fluid rounded-4">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container section-padding">
        <div class="text-center mb-5">
            <h2 class="section-title">Mengapa BKK Online?</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h4 class="fw-bold">Proses Cepat</h4>
                    <p class="text-muted">Daftar dan dapatkan pekerjaan dalam hitungan hari dengan sistem yang efisien</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="fw-bold">Aman & Terpercaya</h4>
                    <p class="text-muted">Data pribadi Anda terlindungi dengan enkripsi tingkat tinggi</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                        <i class="bi bi-building"></i>
                    </div>
                    <h4 class="fw-bold">200+ Perusahaan</h4>
                    <p class="text-muted">Bergabung dengan perusahaan terkemuka di Indonesia</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h4 class="fw-bold">85% Tingkat Penempatan</h4>
                    <p class="text-muted">Rasio penempatan kerja tertinggi di industri</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="container section-padding">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number text-primary">5000+</div>
                    <h5 class="text-muted">Pencari Kerja</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number text-success">200+</div>
                    <h5 class="text-muted">Perusahaan</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number text-warning">3500+</div>
                    <h5 class="text-muted">Lowongan</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number text-info">85%</div>
                    <h5 class="text-muted">Penempatan</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Section -->
    <div class="container section-padding">
        <div class="text-center mb-5">
            <h2 class="section-title">Cara Kerja BKK Online</h2>
        </div>
        <div class="row">
            <div class="col-md-3 process-step">
                <div class="step-number bg-primary text-white">1</div>
                <h4>Daftar Akun</h4>
                <p class="text-muted">Buat akun dengan mengisi formulir pendaftaran</p>
            </div>
            <div class="col-md-3 process-step">
                <div class="step-number bg-primary text-white">2</div>
                <h4>Lengkapi Profil</h4>
                <p class="text-muted">Isi data pendidikan dan keahlian Anda</p>
            </div>
            <div class="col-md-3 process-step">
                <div class="step-number bg-primary text-white">3</div>
                <h4>Bayar Biaya</h4>
                <p class="text-muted">Lakukan pembayaran dan upload bukti</p>
            </div>
            <div class="col-md-3 process-step">
                <div class="step-number bg-primary text-white">4</div>
                <h4>Dapatkan Pekerjaan</h4>
                <p class="text-muted">Akses ribuan lowongan kerja</p>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="container section-padding">
        <div class="text-center mb-5">
            <h2 class="section-title">Apa Kata Mereka?</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="d-flex mb-3">
                        <img src="https://picsum.photos/seed/user1/70/70.jpg" class="testimonial-img" alt="User">
                        <div class="ms-3">
                            <h5 class="mb-0 fw-bold">Ahmad Rizki</h5>
                            <small class="text-muted">Software Engineer</small>
                        </div>
                    </div>
                    <p class="mb-3">"BKK Online membantu saya mendapatkan pekerjaan pertama saya. Prosesnya mudah dan banyak pilihan perusahaan berkualitas."</p>
                    <div class="rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="d-flex mb-3">
                        <img src="https://picsum.photos/seed/user2/70/70.jpg" class="testimonial-img" alt="User">
                        <div class="ms-3">
                            <h5 class="mb-0 fw-bold">Siti Nurhaliza</h5>
                            <small class="text-muted">Marketing Manager</small>
                        </div>
                    </div>
                    <p class="mb-3">"Saya sangat merekomendasikan BKK Online untuk teman-teman yang sedang mencari kerja. Platform ini sangat membantu."</p>
                    <div class="rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="d-flex mb-3">
                        <img src="https://picsum.photos/seed/user3/70/70.jpg" class="testimonial-img" alt="User">
                        <div class="ms-3">
                            <h5 class="mb-0 fw-bold">Budi Santoso</h5>
                            <small class="text-muted">HR Manager</small>
                        </div>
                    </div>
                    <p class="mb-3">"Sebagai perusahaan, kami sangat puas dengan kualitas kandidat yang kami dapatkan melalui BKK Online."</p>
                    <div class="rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Partners Section -->
    <div class="container section-padding">
        <div class="text-center mb-5">
            <h2 class="section-title">Mitra Perusahaan</h2>
        </div>
        <div class="row g-4 align-items-center">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <img src="https://picsum.photos/seed/company1/180/60.jpg" class="brand-logo img-fluid" alt="Partner">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <img src="https://picsum.photos/seed/company2/180/60.jpg" class="brand-logo img-fluid" alt="Partner">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <img src="https://picsum.photos/seed/company3/180/60.jpg" class="brand-logo img-fluid" alt="Partner">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <img src="https://picsum.photos/seed/company4/180/60.jpg" class="brand-logo img-fluid" alt="Partner">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <img src="https://picsum.photos/seed/company5/180/60.jpg" class="brand-logo img-fluid" alt="Partner">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <img src="https://picsum.photos/seed/company6/180/60.jpg" class="brand-logo img-fluid" alt="Partner">
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Jobs Section -->
    <div class="container section-padding">
        <div class="text-center mb-5">
            <h2 class="section-title">Lowongan Terbaru</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card job-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="card-title">Frontend Developer</h4>
                            <span class="badge bg-primary badge-custom">Full-time</span>
                        </div>
                        <h5 class="text-muted mb-3">PT Teknologi Maju</h5>
                        <p class="card-text">Mencari frontend developer berpengalaman dengan React.js dan Vue.js. Minimal 2 tahun pengalaman.</p>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> Jakarta</small>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i> 2 hari yang lalu</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="#" class="btn btn-outline-primary w-100">Detail Lowongan</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card job-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="card-title">Marketing Executive</h4>
                            <span class="badge bg-success badge-custom">Part-time</span>
                        </div>
                        <h5 class="text-muted mb-3">CV Kreatif Indonesia</h5>
                        <p class="card-text">Dibutuhkan marketing executive untuk mengembangkan strategi pemasaran digital dan meningkatkan brand awareness.</p>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> Surabaya</small>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i> 3 hari yang lalu</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="#" class="btn btn-outline-primary w-100">Detail Lowongan</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="#" class="btn btn-primary btn-lg cta-button">Lihat Semua Lowongan</a>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="container section-padding">
        <div class="text-center mb-5">
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="faq-item">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Apa itu BKK Online?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    BKK Online adalah platform digital yang menghubungkan pencari kerja dengan perusahaan. Ini adalah sistem Bursa Kerja Khusus yang memfasilitasi proses rekrutmen secara online.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Bagaimana cara mendaftar di BKK Online?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Anda dapat mendaftar dengan mengklik tombol "Daftar Sekarang" di halaman beranda. Isi formulir pendaftaran dengan data yang lengkap dan valid, lalu ikuti langkah-langkah selanjutnya.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Berapa biaya pendaftaran di BKK Online?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Biaya pendaftaran di BKK Online adalah Rp 50.000 yang dapat dibayarkan melalui transfer bank ke rekening yang telah ditentukan. Bukti pembayaran harus diunggah ke sistem untuk verifikasi.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="container section-padding">
        <div class="text-center">
            <h2 class="section-title">Siap Memulai Karir Anda?</h2>
            <p class="lead mb-5">Bergabunglah dengan ribuan pencari kerja yang telah berhasil menemukan pekerjaan impian mereka</p>
            <a href="register.php" class="btn btn-primary btn-lg cta-button">
                <i class="bi bi-info-circle me-2"></i> Ingin Tahu Lebih Banyak?
            </a>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4">
        <p class="mb-0">&copy; 2024 BKK Online. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>