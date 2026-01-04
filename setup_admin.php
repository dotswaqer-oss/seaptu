<?php
// Script untuk membuat admin awal. Jalankan sekali dari browser atau CLI.
require_once 'koneksi.php';
$pdo = getPDO();
if(!$pdo) { echo "Koneksi DB gagal."; exit; }
$user = 'admin';
$pass = 'admin123';
$hash = password_hash($pass, PASSWORD_DEFAULT);
try{
    $stmt = $pdo->prepare('INSERT INTO admins (username,password,name) VALUES (?,?,?)');
    $stmt->execute([$user,$hash,'Administrator']);
    echo "Admin dibuat: user=admin, pass=admin123 (silakan ganti segera).";
}catch(PDOException $e){
    echo "Gagal: " . $e->getMessage();
}
