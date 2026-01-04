<?php
require_once 'koneksi.php';
$pdo = getPDO();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])){
    $pid = (int)$_POST['product_id'];
    $qty = max(1,(int)$_POST['qty']);
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if(isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] += $qty;
    else $_SESSION['cart'][$pid] = $qty;
    header('Location: cart.php'); exit;
}

// handle remove
if(isset($_GET['remove'])){
    $r = (int)$_GET['remove'];
    if(isset($_SESSION['cart'][$r])){
        unset($_SESSION['cart'][$r]);
    }
    header('Location: cart.php'); exit;
}

$cart = $_SESSION['cart'] ?? [];
$products = [];
$total = 0;
if($cart){
    $ids = array_keys($cart);
    $in  = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    foreach($rows as $r){
        $r['qty'] = $cart[$r['id']];
        $r['subtotal'] = $r['qty'] * $r['price'];
        $total += $r['subtotal'];
        $products[] = $r;
    }
}
?>
<!DOCTYPE html>
<html><body>
<h2>Keranjang</h2>
<?php if(empty($products)): ?>
    <p>Keranjang kosong.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th>Aksi</th></tr>
        <?php foreach($products as $p): ?>
        <tr>
            <td><?=htmlspecialchars($p['name'])?></td>
            <td><?=number_format($p['price'],0,',','.')?></td>
            <td><?=intval($p['qty'])?></td>
            <td><?=number_format($p['subtotal'],0,',','.')?></td>
            <td><a href="cart.php?remove=<?=$p['id']?>">Remove</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p>Total: <?=number_format($total,0,',','.')?></p>
    <p><a href="checkout.php">Checkout</a></p>
<?php endif; ?>
<p><a href="index.php">Lanjut belanja</a></p>
</body></html>
