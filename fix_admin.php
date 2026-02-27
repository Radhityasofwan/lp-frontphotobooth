<?php
// Tampilkan error jika ada masalah agar mudah di-debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Panggil konfigurasi database Anda
require_once 'config.php';

// Pastikan konstanta database dari config.php terbaca
if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASS') || !defined('DB_NAME')) {
    die("Error: Konstanta database (DB_HOST, DB_USER, dll) tidak ditemukan di config.php.");
}

// Buat koneksi baru
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error . " <br>Pastikan password dan user database di config.php sudah benar.");
}

// Kredensial yang akan dibuat
$username = 'admin';
$password = 'admin123';

// Enkripsi password menggunakan fungsi bawaan PHP server Anda agar 100% cocok saat login
// Kita gunakan PASSWORD_BCRYPT (standar Laravel/PHP modern)
$hash = password_hash($password, PASSWORD_BCRYPT);

// Hapus user admin lama jika ada (mencegah duplikat)
$conn->query("DELETE FROM users WHERE username = 'admin'");

// Masukkan user admin baru dengan hash yang valid
$sql = "INSERT INTO users (username, password_hash) VALUES ('$username', '$hash')";

if ($conn->query($sql) === TRUE) {
    echo "<div style='font-family:sans-serif; padding:20px; border:1px solid #4CAF50; background:#dff0d8; color:#3c763d; border-radius:5px;'>";
    echo "<h3>✅ Sukses! Akun Admin Berhasil Di-reset/Dibuat.</h3>";
    echo "Gunakan kredensial berikut untuk login:<br><br>";
    echo "Username: <b>" . $username . "</b><br>";
    echo "Password: <b>" . $password . "</b><br><br>";
    echo "<a href='admin/login.php' style='padding:10px 15px; background:#4CAF50; color:white; text-decoration:none; border-radius:3px;'>Coba Login Sekarang</a>";
    echo "<br><br><b>PENTING:</b> Segera hapus file <code>fix_admin.php</code> ini setelah Anda berhasil login demi keamanan aplikasi Anda!";
    echo "</div>";
} else {
    echo "Error menjalankan query: " . $conn->error;
}

$conn->close();
?>