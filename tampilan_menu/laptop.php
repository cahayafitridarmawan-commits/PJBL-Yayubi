<?php
/**
 * BACKEND CATERING YAYUBI
 * Fungsi: Menangkap data form dan meneruskannya ke WhatsApp
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil data dari form HTML
    // htmlspecialchars digunakan agar kode lebih aman dari serangan hacker
    $nama     = htmlspecialchars($_POST['nama']);
    $lokasi   = htmlspecialchars($_POST['lokasi']);
    $telp     = htmlspecialchars($_POST['telp']);
    $tanggal  = $_POST['tanggal'];
    $jam_awal = $_POST['jam_mulai'];
    $jam_akhir= $_POST['jam_selesai'];
    
    // Ambil kategori (dari input hidden yang kita buat tadi)
    $kategori = isset($_POST['kategori_pesanan']) ? $_POST['kategori_pesanan'] : "Umum";

    // Ambil menu yang dicentang (checkbox)
    if (isset($_POST['menu']) && is_array($_POST['menu'])) {
        $menu_dipilih = implode(", ", $_POST['menu']);
    } else {
        $menu_dipilih = "Belum memilih menu";
    }

    // 2. Pengaturan WhatsApp
    // GANTI NOMOR INI dengan nomor WhatsApp kamu (awali dengan 62, jangan pakai 0)
    $nomor_hp_tujuan = "628123456789"; 

    // 3. Susun format pesan agar rapi saat masuk ke WA
    $pesan_wa  = "*PESANAN BARU - YAYUBI CATERING*\n";
    $pesan_wa .= "------------------------------------------\n";
    $pesan_wa .= "*Kategori:* " . strtoupper($kategori) . "\n";
    $pesan_wa .= "*Nama:* " . $nama . "\n";
    $pesan_wa .= "*No. Telp:* " . $telp . "\n";
    $pesan_wa .= "*Tanggal Kirim:* " . $tanggal . "\n";
    $pesan_wa .= "*Jam:* " . $jam_awal . " s/d " . $jam_akhir . "\n";
    $pesan_wa .= "*Lokasi:* " . $lokasi . "\n";
    $pesan_wa .= "------------------------------------------\n";
    $pesan_wa .= "*Menu Pilihan:*\n" . $menu_dipilih . "\n";
    $pesan_wa .= "------------------------------------------\n";
    $pesan_wa .= "_Mohon segera diproses ya, Kak!_";

    // 4. Encode pesan agar bisa dibaca browser
    $link_wa = "https://api.whatsapp.com/send?phone=" . $nomor_hp_tujuan . "&text=" . urlencode($pesan_wa);

    // 5. Lempar (Redirect) user ke WhatsApp
    header("Location: " . $link_wa);
    exit;

} else {
    // Jika ada yang mencoba buka proses.php langsung tanpa isi form, balikkan ke index
    header("Location: index.html");
    exit;
}
?>