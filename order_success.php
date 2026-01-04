<?php
require_once 'koneksi.php';
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT o.*, c.name, c.email FROM orders o JOIN customers c ON o.customer_id = c.id WHERE o.id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();
?>
<!DOCTYPE html><body>
<?php if(!$order){ echo '<p>Order tidak ditemukan.</p>'; exit;} ?>
<h2>Terima kasih! Pesanan Anda sudah dibuat.</h2>
<p>Order ID: <?=htmlspecialchars($order['id'])?></p>
<p>Total: <?=number_format($order['total'],0,',','.')?></p>
<p>Status: <?=htmlspecialchars($order['status'])?></p>
<p>Kami akan memproses pesanan Anda. Silakan tunggu validasi dari admin.</p>
<p><a href="index.php">Kembali ke toko</a></p>
</body></html>
