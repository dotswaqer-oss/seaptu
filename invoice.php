<?php
require_once 'koneksi.php';
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT o.*, c.name, c.email, c.address FROM orders o JOIN customers c ON o.customer_id=c.id WHERE o.id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();
if(!$order){ echo '<p>Order tidak ditemukan.</p>'; exit; }
$stmtItems = $pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id = ?');
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();
// simple HTML invoice
?><!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Invoice #<?=htmlspecialchars($order['id'])?></title>
<style>body{font-family:Arial,Helvetica,sans-serif;padding:20px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:8px;text-align:left}</style>
</head><body>
<h2>Invoice - Mukarakat</h2>
<p>Order ID: <?=htmlspecialchars($order['id'])?><br>Nama: <?=htmlspecialchars($order['name'])?><br>Email: <?=htmlspecialchars($order['email'])?><br>Alamat: <?=nl2br(htmlspecialchars($order['address']))?></p>
<table>
<tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
<?php foreach($items as $it): ?>
<tr>
  <td><?=htmlspecialchars($it['name'])?></td>
  <td><?=number_format($it['price'],0,',','.')?></td>
  <td><?=$it['quantity']?></td>
  <td><?=number_format($it['price']*$it['quantity'],0,',','.')?></td>
</tr>
<?php endforeach; ?>
<tr><td colspan="3" style="text-align:right"><strong>Total</strong></td><td><strong><?=number_format($order['total'],0,',','.')?></strong></td></tr>
</table>
<p>Status: <?=htmlspecialchars($order['status'])?></p>
</body></html>
