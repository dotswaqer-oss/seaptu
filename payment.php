<?php
require_once 'koneksi.php';
$pdo = getPDO();
if(empty($_SESSION['customer_id'])){ header('Location: login.php'); exit; }
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT o.*, c.email FROM orders o JOIN customers c ON o.customer_id=c.id WHERE o.id = ?');
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if(!$order){ echo '<p>Order tidak ditemukan.</p>'; exit; }
if($order['customer_id'] != $_SESSION['customer_id']){ echo '<p>Anda tidak memiliki akses ke order ini.</p>'; exit; }

// apabila form submit (simpan bukti bayar simulasi)
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $ref = trim($_POST['payment_ref'] ?? '');
    if($ref){
        $u = $pdo->prepare('UPDATE orders SET status = ?, payment_ref = ? WHERE id = ?');
        $u->execute(['paid',$ref,$order_id]);
        header('Location: order_success.php?id='.$order_id); exit;
    }else{
        $error = 'Masukkan kode referensi/bukti pembayaran.';
    }
}
?>
<!DOCTYPE html><body>
<h2>Simulasi Pembayaran - Bank Transfer</h2>
<p>Order ID: <?=htmlspecialchars($order['id'])?></p>
<p>Total: <?=number_format($order['total'],0,',','.')?></p>
<h3>Instruksi Pembayaran:</h3>
<ul>
    <li>Transfer ke: Bank Contoh - 123456789 a.n. Mukarakat</li>
    <li>Nominal: <?=number_format($order['total'],0,',','.')?></li>
    <li>Gunakan referensi pembayaran saat transfer.</li>
</ul>
<?php if(!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
    <label>Kode Referensi / No. Bukti (simulasi)</label><br>
    <input name="payment_ref" required><br>
    <button type="submit">Saya sudah transfer (Simulasi)</button>
</form>
<p><a href="index.php">Kembali ke Toko</a></p>
</body></html>
