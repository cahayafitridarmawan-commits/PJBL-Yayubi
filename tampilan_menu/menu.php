<?php
/**
 * BACKEND CATERING YAYUBI (GABUNGAN LAPTOP & HP)
 * Fungsi: Menangkap data form, menampilkan halaman konfirmasi screenshot, 
 * dan menyediakan tombol/redirect otomatis ke WhatsApp.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil data dari form HTML dengan aman
    $nama        = htmlspecialchars($_POST['nama']);
    $lokasi      = htmlspecialchars($_POST['lokasi']);
    $telp        = htmlspecialchars($_POST['telp']);
    $tanggal     = htmlspecialchars($_POST['tanggal']);
    $jam_awal    = htmlspecialchars($_POST['jam_mulai']);
    $jam_akhir   = htmlspecialchars($_POST['jam_selesai']);
    
    // Solusi Sinkronisasi: Memeriksa name="kategori" (Tumpeng) atau name="kategori_pesanan" (Snack/Nasi Box)
    if (isset($_POST['kategori'])) {
        $kategori = htmlspecialchars($_POST['kategori']);
    } elseif (isset($_POST['kategori_pesanan'])) {
        $kategori = htmlspecialchars($_POST['kategori_pesanan']);
    } else {
        $kategori = "Umum";
    }

    // Ambil menu yang dicentang (checkbox)
    if (isset($_POST['menu']) && is_array($_POST['menu'])) {
        $menu_dipilih = implode(", ", $_POST['menu']);
    } else {
        $menu_dipilih = "Belum memilih menu";
    }

    // 2. Pengaturan WhatsApp (Ganti nomor ini jika diperlukan, gunakan kode negara 62)
    $nomor_hp_tujuan = "628123456789"; 

    // 3. Susun format teks rapi untuk WhatsApp
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

    // 4. Encode pesan teks untuk URL link WhatsApp
    $link_wa = "https://api.whatsapp.com/send?phone=" . $nomor_hp_tujuan . "&text=" . urlencode($pesan_wa);
    
    // 5. Tampilkan Struktur Halaman HTML Konfirmasi (Untuk Kebutuhan Syarat Screenshot)
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Konfirmasi Pesanan - Catering Yayubi</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #FAF5EE;
                color: #1a1a1a;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            .container {
                background: white;
                max-width: 500px;
                width: 100%;
                border-radius: 16px;
                box-shadow: 0 8px 24px rgba(232, 71, 10, 0.15);
                border: 1px solid #fde0cc;
                overflow: hidden;
                text-align: center;
            }
            .header {
                background: #E8470A;
                color: white;
                padding: 20px;
            }
            .header h1 { margin: 0; font-size: 22px; font-weight: 800; }
            .header p { margin: 5px 0 0; font-size: 13px; opacity: 0.9; }
            .body-content { padding: 24px; text-align: left; }
            .info-list { list-style: none; padding: 0; margin: 0 0 20px 0; }
            .info-list li { 
                padding: 10px 0; 
                border-bottom: 1px solid #f5e8de; 
                font-size: 14px;
            }
            .info-list li strong { color: #E8470A; }
            .note-box { 
                background: #fff3ec; 
                border: 1px dashed #E8470A; 
                padding: 12px; 
                border-radius: 10px; 
                font-size: 12px; 
                font-weight: 600; 
                color: #E8470A; 
                text-align: center;
                margin-bottom: 20px;
            }
            .btn-wa {
                display: block;
                background: #E8470A;
                color: white;
                text-decoration: none;
                padding: 15px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 15px;
                box-shadow: 0 4px 12px rgba(232, 71, 10, 0.3);
                text-align: center;
                transition: transform 0.2s;
            }
            .btn-wa:active { transform: scale(0.98); }
            .btn-back {
                display: inline-block;
                margin-top: 15px;
                color: #666;
                font-size: 13px;
                text-decoration: none;
                font-weight: 500;
            }
        </style>
        <script>
            // Otomatis mengalihkan / membuka WhatsApp setelah 3 detik halaman terbuka
            window.onload = function() {
                setTimeout(function() {
                    window.location.href = "<?php echo $link_wa; ?>";
                }, 3000);
            };
        </script>
    </head>
    <body>

    <div class="container">
        <div class="header">
            <h1>Terima Kasih, <?php echo $nama; ?>!</h1>
            <p>Pesanan Anda telah berhasil direkam</p>
        </div>
        <div class="body-content">
            <div class="note-box">
                📸 WAJIB SCREENSHOT HALAMAN INI SEBAGAI BUKTI NOTA!
            </div>
            
            <ul class="info-list">
                <li><strong>Kategori:</strong> <?php echo strtoupper($kategori); ?></li>
                <li><strong>Tanggal Kirim:</strong> <?php echo $tanggal; ?></li>
                <li><strong>Jam Ambil:</strong> <?php echo $jam_awal; ?> - <?php echo $jam_akhir; ?></li>
                <li><strong>Menu Dipilih:</strong> <?php echo $menu_dipilih; ?></li>
                <li><strong>Lokasi Tujuan:</strong> <?php echo $lokasi; ?></li>
                <li><strong>No. Telepon:</strong> <?php echo $telp; ?></li>
            </ul>

            <a href="<?php echo $link_wa; ?>" class="btn-wa">Kirim Nota ke WhatsApp →</a>
            <center><a href="index.html" class="btn-back">Kembali ke Menu Utama</a></center>
        </div>
    </div>

    </body>
    </html>
    <?php
    exit;

} else {
    // Jika mencoba bypass URL langsung tanpa isi data formulir
    header("Location: index.html");
    exit;
}
?>