<?php
require_once 'koneksi.php';
$pdo = getPDO();
if(empty($_SESSION['customer_id'])){
    header('Location: login.php'); exit;
}
$cart = $_SESSION['cart'] ?? [];
if(!$cart){
    echo '<p>Keranjang kosong. <a href="index.php">Belanja dulu</a></p>'; exit;
}
$ids = array_keys($cart);
$in  = str_repeat('?,', count($ids) - 1) . '?';
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
$stmt->execute($ids);
$rows = $stmt->fetchAll();
$total = 0;
foreach($rows as $r){
    $subtotal = $r['price'] * $cart[$r['id']];
    $total += $subtotal;
}
?>
<!DOCTYPE html><body>
<h2>Checkout</h2>
<p>Total yang harus dibayar: <?=number_format($total,0,',','.')?></p>
<form method="post" action="process_checkout.php">
    <label>Metode Pembayaran</label>
    <select name="payment_method">
        <option value="bank_transfer">Bank Transfer</option>
        <option value="cod">Cash on Delivery</option>
    </select>
    <br><label>Alamat pengiriman</label><br>
    <textarea name="address" required><?=(isset($_SESSION['customer_address'])?htmlspecialchars($_SESSION['customer_address']):'')?></textarea>
    <br><button type="submit">Bayar / Proses Pesanan</button>
</form>
</body></html>
