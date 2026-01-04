<?php
require_once 'koneksi.php';
$pdo = getPDO();
if(empty($_SESSION['customer_id'])){ header('Location: login.php'); exit; }
$cid = $_SESSION['customer_id'];
$stmt = $pdo->prepare('SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC');
$stmt->execute([$cid]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html><body>
<h2>Riwayat Pesanan</h2>
<?php if(empty($orders)): ?>
    <p>Belum ada pesanan.</p>
<?php else: ?>
<table border="1" cellpadding="8">
    <tr><th>ID</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
    <?php foreach($orders as $o): ?>
    <tr>
        <td><?=$o['id']?></td>
        <td><?=number_format($o['total'],0,',','.')?></td>
        <td><?=htmlspecialchars($o['status'])?></td>
        <td><?=$o['created_at']?></td>
        <td><a href="order_view.php?id=<?=$o['id']?>">Lihat</a></td>
    </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
<p><a href="index.php">Kembali ke Toko</a></p>
</body></html>
