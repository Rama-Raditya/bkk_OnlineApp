<?php
require_once 'config/koneksi.php';
require_once 'wa_helper.php'; // Include WhatsApp helper

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = $_POST['jumlah'];
    $id_pendaftar = $_SESSION['user_id'];
    
    // Validasi file
    if(isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        $file = $_FILES['bukti'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validasi ekstensi
        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
        if(!in_array($fileExt, $allowedExt)) {
            $_SESSION['error'] = "Format file tidak valid! Gunakan JPG, PNG, atau PDF.";
            header('Location: dashboard.php');
            exit;
        }
        
        // Validasi ukuran (max 2MB)
        if($fileSize > 2 * 1024 * 1024) {
            $_SESSION['error'] = "Ukuran file terlalu besar! Maksimal 2MB.";
            header('Location: dashboard.php');
            exit;
        }
        
        // Buat folder uploads jika belum ada
        if(!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        // Generate nama file unik
        $newFileName = 'bukti_' . $id_pendaftar . '_' . time() . '.' . $fileExt;
        $destination = 'uploads/' . $newFileName;
        
        // Upload file
        if(move_uploaded_file($fileTmp, $destination)) {
            // Simpan ke database
            $stmt = $pdo->prepare("INSERT INTO pembayaran (id_pendaftar, tanggal, jumlah, bukti, status) VALUES (?, NOW(), ?, ?, 'pending')");
            
            if($stmt->execute([$id_pendaftar, $jumlah, $newFileName])) {
                // FITUR BARU: Ambil nomor HP user dari database
                $stmt = $pdo->prepare("SELECT no_hp, nama FROM pendaftar WHERE id = ?");
                $stmt->execute([$id_pendaftar]);
                $user = $stmt->fetch();
                
                if($user && !empty($user['no_hp'])) {
                    // Kirim notifikasi WhatsApp
                    try {
                        kirimNotifUpload($user['no_hp'], $user['nama'], $jumlah);
                        $_SESSION['success'] = "Bukti pembayaran berhasil diupload! Notifikasi telah dikirim ke WhatsApp Anda. Menunggu verifikasi admin.";
                    } catch (Exception $e) {
                        // Jika gagal kirim WA, tetap sukses upload
                        error_log("Gagal kirim WA: " . $e->getMessage());
                        $_SESSION['success'] = "Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.";
                    }
                } else {
                    $_SESSION['success'] = "Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.";
                    $_SESSION['warning'] = "Nomor HP tidak ditemukan. Notifikasi WhatsApp tidak dapat dikirim.";
                }
            } else {
                $_SESSION['error'] = "Gagal menyimpan data pembayaran!";
            }
        } else {
            $_SESSION['error'] = "Gagal mengupload file!";
        }
    } else {
        $_SESSION['error'] = "Tidak ada file yang diupload!";
    }
}

header('Location: dashboard.php');
exit;
?>