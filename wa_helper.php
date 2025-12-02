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
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
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
    curl_close($curl);
    
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
 * Fungsi untuk mengirim notifikasi upload bukti bayar
 * @param string $no_hp - Nomor HP pendaftar
 * @return bool - Status pengiriman
 */
function kirimNotifUpload($no_hp) {
    $no_hp = formatNoHP($no_hp);
    $pesan = "Terima kasih, bukti pembayaran BKK Anda telah kami terima. Status: MENUNGGU VERIFIKASI ADMIN. Harap menunggu 1x24 jam. - Panitia BKK";
    
    $response = kirimWa($no_hp, $pesan);
    
    // Log untuk debugging (opsional)
    error_log("WA Upload - Target: $no_hp | Response: $response");
    
    return true;
}

/**
 * Fungsi untuk mengirim notifikasi pembayaran disetujui
 * @param string $no_hp - Nomor HP pendaftar
 * @return bool - Status pengiriman
 */
function kirimNotifApproved($no_hp) {
    $no_hp = formatNoHP($no_hp);
    $pesan = "Selamat! Pembayaran BKK Anda telah dinyatakan LUNAS. Akun Anda resmi terdaftar di sistem BKK. - Panitia BKK";
    
    $response = kirimWa($no_hp, $pesan);
    
    // Log untuk debugging (opsional)
    error_log("WA Approved - Target: $no_hp | Response: $response");
    
    return true;
}

/**
 * Fungsi untuk mengirim notifikasi pembayaran ditolak
 * @param string $no_hp - Nomor HP pendaftar
 * @return bool - Status pengiriman
 */
function kirimNotifRejected($no_hp) {
    $no_hp = formatNoHP($no_hp);
    $pesan = "Maaf, bukti pembayaran BKK Anda DITOLAK. Silakan upload ulang bukti yang benar. - Panitia BKK";
    
    $response = kirimWa($no_hp, $pesan);
    
    // Log untuk debugging (opsional)
    error_log("WA Rejected - Target: $no_hp | Response: $response");
    
    return true;
}
?>