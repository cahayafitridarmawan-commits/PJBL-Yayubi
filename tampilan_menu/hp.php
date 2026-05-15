<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $nama = htmlspecialchars($_POST['nama']);
    $lokasi = htmlspecialchars($_POST['lokasi']);
    $telp = htmlspecialchars($_POST['telp']);
    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    
    // Mengambil menu yang dipilih (Array)
    $menu_pilihan = isset($_POST['menu']) ? $_POST['menu'] : [];
    $daftar_menu = implode(", ", $menu_pilihan);

    // Tampilan sederhana setelah konfirmasi
    echo "<h1>Terima Kasih, $nama!</h1>";
    echo "<p>Pesanan Anda telah kami terima.</p>";
    echo "<ul>";
    echo "<li>Tanggal: $tanggal</li>";
    echo "<li>Jam: $jam_mulai - $jam_selesai</li>";
    echo "<li>Menu: $daftar_menu</li>";
    echo "<li>Alamat: $lokasi</li>";
    echo "</ul>";
    echo "<p><strong>Jangan lupa screenshot halaman ini dan kirim ke WhatsApp Penjual!</strong></p>";
    echo "<a href='index.html'>Kembali ke Menu</a>";
} else {
    // Jika diakses langsung tanpa POST, balikkan ke menu
    header("Location: index.html");
}
?>