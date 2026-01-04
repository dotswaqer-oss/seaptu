<?php
// konfigurasi koneksi database. Sesuaikan username/password/host/dbname
function getPDO(){
    $host = '127.0.0.1';
    $db   = 'toko_sepatu';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try{
        return new PDO($dsn, $user, $pass, $opt);
    }catch(PDOException $e){
        echo "<p>Koneksi DB gagal: " . htmlspecialchars($e->getMessage()) . "</p>";
        return null;
    }
}

if(session_status() === PHP_SESSION_NONE) session_start();
// Catatan:
// - Gunakan fungsi getPDO() di atas untuk koneksi ke database menggunakan PDO.
// - Sesuaikan variabel di bawah ini jika Anda menjalankan di lingkungan lain.
// contoh konfigurasi lokal (XAMPP): host=127.0.0.1, user=root, pass kosong

// Jika Anda memang membutuhkan koneksi mysqli, buat file terpisah atau
// gunakan getPDO() untuk konsistensi. Baris mysqli lama telah dihapus.

?>