<?php
// Simulasi sederhana integrasi Midtrans: tidak memanggil API eksternal.
require_once 'koneksi.php';
$pdo = getPDO();
if(empty($_SESSION['customer_id'])){ header('Location: login.php'); exit; }
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if(!$order){ echo '<p>Order tidak ditemukan.</p>'; exit; }
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // simulasi pembayaran sukses: set status paid dan generate ref
    $ref = 'MIDSIM-'.bin2hex(random_bytes(4));
    $u = $pdo->prepare('UPDATE orders SET status = ?, payment_ref = ? WHERE id = ?');
    $u->execute(['paid',$ref,$order_id]);
    header('Location: order_success.php?id='.$order_id); exit;
}
?>
<!DOCTYPE html><body>
<h2>Simulasi Midtrans</h2>
<p>Order ID: <?=$order['id']?> | Total: <?=number_format($order['total'],0,',','.')?></p>
<p>Tekan tombol untuk mensimulasikan pembayaran via Midtrans (sandbox)</p>
<form method="post"><button type="submit">Bayar (Simulasi Midtrans)</button></form>
<p><a href="index.php">Kembali</a></p>
</body></html>
