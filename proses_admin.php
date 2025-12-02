<?php
require_once 'config/koneksi.php';
require_once 'wa_helper.php'; // Include WhatsApp helper

if(!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'];
    $action = $_POST['action'];
    
    // PENTING: Query JOIN untuk mendapatkan no_hp pendaftar
    $stmt = $pdo->prepare("
        SELECT p.no_hp, p.nama, pay.id_pendaftar, pay.jumlah
        FROM pembayaran pay
        INNER JOIN pendaftar p ON pay.id_pendaftar = p.id
        WHERE pay.id = ?
    ");
    $stmt->execute([$payment_id]);
    $data = $stmt->fetch();
    
    if($data) {
        $no_hp = $data['no_hp'];
        $nama = $data['nama'];
        
        if($action == 'approve') {
            // Update status menjadi approved
            $stmt = $pdo->prepare("UPDATE pembayaran SET status = 'approved' WHERE id = ?");
            
            if($stmt->execute([$payment_id])) {
                // Kirim notifikasi WhatsApp - Approved
                if(!empty($no_hp)) {
                    try {
                        kirimNotifApproved($no_hp, $nama, $data['jumlah']);
                        $_SESSION['success'] = "Pembayaran atas nama <strong>{$nama}</strong> berhasil disetujui! Notifikasi WhatsApp telah dikirim.";
                    } catch (Exception $e) {
                        error_log("Gagal kirim WA Approved: " . $e->getMessage());
                        $_SESSION['success'] = "Pembayaran berhasil disetujui! (Notifikasi WA gagal dikirim)";
                    }
                } else {
                    $_SESSION['success'] = "Pembayaran berhasil disetujui!";
                    $_SESSION['warning'] = "Nomor HP tidak ditemukan. Notifikasi tidak dapat dikirim.";
                }
            } else {
                $_SESSION['error'] = "Gagal menyetujui pembayaran!";
            }
            
        } elseif($action == 'reject') {
            // Update status menjadi rejected
            $stmt = $pdo->prepare("UPDATE pembayaran SET status = 'rejected' WHERE id = ?");
            
            if($stmt->execute([$payment_id])) {
                // Kirim notifikasi WhatsApp - Rejected
                if(!empty($no_hp)) {
                    try {
                        kirimNotifRejected($no_hp, $nama, $data['jumlah']);
                        $_SESSION['success'] = "Pembayaran atas nama <strong>{$nama}</strong> berhasil ditolak! Notifikasi WhatsApp telah dikirim.";
                    } catch (Exception $e) {
                        error_log("Gagal kirim WA Rejected: " . $e->getMessage());
                        $_SESSION['success'] = "Pembayaran berhasil ditolak! (Notifikasi WA gagal dikirim)";
                    }
                } else {
                    $_SESSION['success'] = "Pembayaran berhasil ditolak!";
                    $_SESSION['warning'] = "Nomor HP tidak ditemukan. Notifikasi tidak dapat dikirim.";
                }
            } else {
                $_SESSION['error'] = "Gagal menolak pembayaran!";
            }
        }
    } else {
        $_SESSION['error'] = "Data pembayaran tidak ditemukan!";
    }
}

header('Location: admin.php?page=pembayaran');
exit;
?>