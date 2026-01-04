<?php
require_once 'koneksi.php';
$pdo = getPDO();
if(empty($_SESSION['customer_id'])){ header('Location: login.php'); exit; }
$cid = $_SESSION['customer_id'];
$stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ?');
$stmt->execute([$cid]);
$user = $stmt->fetch();
$msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $u = $pdo->prepare('UPDATE customers SET name = ?, phone = ?, address = ? WHERE id = ?');
    $u->execute([$name,$phone,$address,$cid]);
    $msg = 'Profil diperbarui.';
    $_SESSION['customer_name'] = $name;
    $stmt->execute([$cid]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html><body>
<h2>Profil Saya</h2>
<?php if($msg) echo '<p style="color:green">'.htmlspecialchars($msg).'</p>'; ?>
<form method="post">
    <label>Nama</label><br>
    <input name="name" value="<?=htmlspecialchars($user['name'])?>"><br>
    <label>Phone</label><br>
    <input name="phone" value="<?=htmlspecialchars($user['phone'])?>"><br>
    <label>Alamat</label><br>
    <textarea name="address"><?=htmlspecialchars($user['address'])?></textarea><br>
    <button type="submit">Simpan</button>
</form>
<p><a href="my_orders.php">Lihat Riwayat Pesanan</a></p>
<p><a href="index.php">Kembali ke Toko</a></p>
</body></html>
