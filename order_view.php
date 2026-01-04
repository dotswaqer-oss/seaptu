<?php
require_once 'koneksi.php';
$pdo = getPDO();
if(empty($_SESSION['customer_id'])){ header('Location: login.php'); exit; }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT o.*, c.name FROM orders o JOIN customers c ON o.customer_id=c.id WHERE o.id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();
if(!$order || $order['customer_id'] != $_SESSION['customer_id']){ echo '<p>Order tidak ditemukan atau akses ditolak.</p>'; exit; }
$stmt2 = $pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id = ?');
$stmt2->execute([$id]);
$items = $stmt2->fetchAll();
?>
<!DOCTYPE html><body>
<h2>Detail Order #<?=$order['id']?></h2>
<p>Status: <?=htmlspecialchars($order['status'])?></p>
<p>Total: <?=number_format($order['total'],0,',','.')?></p>
<table border="1" cellpadding="8">
    <tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
    <?php foreach($items as $it): ?>
    <tr>
        <td><?=htmlspecialchars($it['name'])?></td>
        <td><?=number_format($it['price'],0,',','.')?></td>
        <td><?=$it['quantity']?></td>
        <td><?=number_format($it['price'] * $it['quantity'],0,',','.')?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php if($order['status'] === 'pending' && $order['payment_method'] === 'bank_transfer'): ?>
    <p><a href="payment.php?id=<?=$order['id']?>">Lakukan Pembayaran (Simulasi)</a></p>
<?php endif; ?>
<p><a href="my_orders.php">Kembali</a></p>
</body></html>
