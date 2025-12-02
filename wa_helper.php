<?php
/**
 * WhatsApp Helper - Fonnte API Integration
 * File ini berisi fungsi untuk mengirim notifikasi WhatsApp
 */

/**
 * Fungsi untuk mengirim pesan WhatsApp via Fonnte API
 * @param string $target - Nomor HP tujuan (format: 628xxxxx)
 * @param string $pesan - Isi pesan yang akan dikirim
 * @return string - Response dari API
 */
function kirimWa($target, $pesan) {
    // Token Fonnte - Ganti dengan token Anda
    $token = "psGRyr4sgu6JomJwo2T5"; // Placeholder - Ganti dengan token asli
    
    // Validasi target
    if(empty($target) || empty($pesan)) {
        error_log("WA Helper - Error: Target atau pesan kosong");
        return json_encode(['status' => 'error', 'message' => 'Target atau pesan kosong']);
    }
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $pesan,
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    ));
    
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    
    curl_close($curl);
    
    // Log untuk debugging
    if($curlError) {
        error_log("WA Helper - cURL Error: $curlError");
    }
    error_log("WA Helper - HTTP Code: $httpCode | Target: $target | Response: $response");
    
    return $response;
}

/**
 * Fungsi untuk format nomor HP ke format internasional
 * @param string $no_hp - Nomor HP (08xxx atau 628xxx atau +628xxx)
 * @return string - Nomor HP dalam format 628xxx
 */
function formatNoHP($no_hp) {
    // Hapus semua karakter non-digit
    $no_hp = preg_replace('/[^0-9]/', '', $no_hp);
    
    // Jika diawali dengan 0, ganti dengan 62
    if (substr($no_hp, 0, 1) == '0') {
        $no_hp = '62' . substr($no_hp, 1);
    }
    
    // Jika tidak diawali dengan 62, tambahkan 62
    if (substr($no_hp, 0, 2) != '62') {
        $no_hp = '62' . $no_hp;
    }
    
    return $no_hp;
}

/**
 * Fungsi untuk mengirim notifikasi registrasi berhasil
 * @param string $no_hp - Nomor HP pendaftar
 * @param string $nama - Nama lengkap pendaftar
 * @param string $nkk - NKK pendaftar
 * @return bool - Status pengiriman
 */
function kirimNotifRegistrasi($no_hp, $nama, $nkk) {
    try {
        $no_hp = formatNoHP($no_hp);
        
        if(empty($no_hp) || empty($nama) || empty($nkk)) {
            error_log("WA Registrasi - Error: Parameter tidak lengkap");
            return false;
        }
        
        $pesan = "   *REGISTRASI BERHASIL*   \n";
        $pesan .= "Selamat! Pendaftaran Anda telah berhasil.\n\n";
        $pesan .= "📋 *DATA PENDAFTARAN*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "👤 Nama     : *{$nama}*\n";
        $pesan .= "🆔 NKK      : {$nkk}\n\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "🏢 *Panitia BKK*\n";
        $pesan .= "Bursa Kerja Khusus\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "_Pesan otomatis, mohon tidak membalas_";
        
        $response = kirimWa($no_hp, $pesan);
        
        // Log untuk debugging
        error_log("WA Registrasi - Target: $no_hp | Nama: $nama | NKK: $nkk | Response: $response");
        
        return true;
    } catch (Exception $e) {
        error_log("WA Registrasi - Exception: " . $e->getMessage());
        return false;
    }
}

/**
 * Fungsi untuk mengirim notifikasi upload bukti bayar
 * @param string $no_hp - Nomor HP pendaftar
 * @param string $nama - Nama pendaftar
 * @param int $jumlah - Jumlah pembayaran
 * @return bool - Status pengiriman
 */
function kirimNotifUpload($no_hp, $nama, $jumlah) {
    try {
        $no_hp = formatNoHP($no_hp);
        
        if(empty($no_hp) || empty($nama)) {
            error_log("WA Upload - Error: Parameter tidak lengkap");
            return false;
        }
        
        $pesan = "   *BUKTI PEMBAYARAN*   \n";
        $pesan .= "   *BERHASIL DITERIMA*   \n";
        $pesan .= "Terima kasih *{$nama}*,\n";
        $pesan .= "Bukti pembayaran BKK Anda telah kami terima.\n\n";
        $pesan .= "💰 *DETAIL PEMBAYARAN*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "👤 Nama        : *{$nama}*\n";
        $pesan .= "💵 Jumlah      : Rp " . number_format($jumlah, 0, ',', '.') . "\n";
        $pesan .= "📅 Tanggal     : " . date('d/m/Y H:i') . " WIB\n";
        $pesan .= "⏳ Status      : *MENUNGGU VERIFIKASI*\n\n";
        $pesan .= "⚠️ *INFORMASI PENTING:*\n";
        $pesan .= "• Proses verifikasi maksimal 1x24 jam\n";
        $pesan .= "• Anda akan mendapat notifikasi setelah diverifikasi\n";
        $pesan .= "• Pastikan nomor ini aktif untuk menerima notifikasi\n\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "🏢 *Panitia BKK*\n";
        $pesan .= "Bursa Kerja Khusus\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "_Pesan otomatis, mohon tidak membalas_";
        
        $response = kirimWa($no_hp, $pesan);
        
        // Log untuk debugging
        error_log("WA Upload - Target: $no_hp | Nama: $nama | Jumlah: $jumlah | Response: $response");
        
        return true;
    } catch (Exception $e) {
        error_log("WA Upload - Exception: " . $e->getMessage());
        return false;
    }
}

/**
 * Fungsi untuk mengirim notifikasi pembayaran disetujui
 * @param string $no_hp - Nomor HP pendaftar
 * @param string $nama - Nama pendaftar
 * @param int $jumlah - Jumlah pembayaran
 * @return bool - Status pengiriman
 */
function kirimNotifApproved($no_hp, $nama, $jumlah) {
    $no_hp = formatNoHP($no_hp);
    
    $pesan = "   *PEMBAYARAN DISETUJUI*   \n";
    $pesan .= "   ✅ *LUNAS* ✅   \n";
    $pesan .= "🎉 Selamat *{$nama}*!\n\n";
    $pesan .= "Pembayaran BKK Anda telah dinyatakan *LUNAS* dan disetujui oleh admin.\n\n";
    $pesan .= "💰 *DETAIL PEMBAYARAN*\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $pesan .= "👤 Nama        : *{$nama}*\n";
    $pesan .= "💵 Jumlah      : Rp " . number_format($jumlah, 0, ',', '.') . "\n";
    $pesan .= "✅ Status      : *LUNAS*\n";
    $pesan .= "📅 Verifikasi  : " . date('d/m/Y H:i') . " WIB\n\n";
    $pesan .= "🎓 *SELAMAT!*\n";
    $pesan .= "Akun Anda telah *RESMI TERDAFTAR* di sistem BKK Online.\n\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $pesan .= "🏢 *Panitia BKK*\n";
    $pesan .= "Bursa Kerja Khusus\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $pesan .= "_Pesan otomatis, mohon tidak membalas_";
    
    $response = kirimWa($no_hp, $pesan);
    
    // Log untuk debugging (opsional)
    error_log("WA Approved - Target: $no_hp | Nama: $nama | Response: $response");
    
    return true;
}

/**
 * Fungsi untuk mengirim notifikasi pembayaran ditolak
 * @param string $no_hp - Nomor HP pendaftar
 * @param string $nama - Nama pendaftar
 * @param int $jumlah - Jumlah pembayaran
 * @return bool - Status pengiriman
 */
function kirimNotifRejected($no_hp, $nama, $jumlah) {
    $no_hp = formatNoHP($no_hp);
    
    $pesan = "   *PEMBAYARAN DITOLAK*   \n";
    $pesan .= "   ❌ *REJECTED* ❌   \n";
    $pesan .= "Kepada Yth. *{$nama}*,\n\n";
    $pesan .= "Mohon maaf, bukti pembayaran BKK Anda *DITOLAK* oleh admin.\n\n";
    $pesan .= "💰 *DETAIL PEMBAYARAN*\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $pesan .= "👤 Nama        : *{$nama}*\n";
    $pesan .= "💵 Jumlah      : Rp " . number_format($jumlah, 0, ',', '.') . "\n";
    $pesan .= "❌ Status      : *DITOLAK*\n";
    $pesan .= "📅 Verifikasi  : " . date('d/m/Y H:i') . " WIB\n\n";
    $pesan .= "⚠️ *ALASAN PENOLAKAN:*\n";
    $pesan .= "• Bukti pembayaran tidak jelas/blur\n";
    $pesan .= "• Nominal tidak sesuai\n";
    $pesan .= "• Format file tidak valid\n";
    $pesan .= "• Data tidak lengkap\n\n";
    $pesan .= "📝 *SOLUSI:*\n";
    $pesan .= "Silakan login ke sistem BKK Online dan *UPLOAD ULANG* bukti pembayaran yang benar.\n\n";
    $pesan .= "💡 *TIPS:*\n";
    $pesan .= "• Pastikan foto/scan jelas dan terbaca\n";
    $pesan .= "• Cek nominal pembayaran sudah benar\n";
    $pesan .= "• Gunakan format JPG/PNG/PDF\n";
    $pesan .= "• Ukuran file maksimal 2MB\n\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $pesan .= "🏢 *Panitia BKK*\n";
    $pesan .= "Bursa Kerja Khusus\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $pesan .= "_Pesan otomatis, mohon tidak membalas_";
    
    $response = kirimWa($no_hp, $pesan);
    
    // Log untuk debugging (opsional)
    error_log("WA Rejected - Target: $no_hp | Nama: $nama | Response: $response");
    
    return true;
}
?>