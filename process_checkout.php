<?php
require_once 'koneksi.php';
$pdo = getPDO();
if(empty($_SESSION['customer_id'])){ header('Location: login.php'); exit; }
$cart = $_SESSION['cart'] ?? [];
if(!$cart){ header('Location: cart.php'); exit; }
$customer_id = $_SESSION['customer_id'];
$payment_method = $_POST['payment_method'] ?? 'bank_transfer';
$address = $_POST['address'] ?? '';

// hitung total
$ids = array_keys($cart);
$in  = str_repeat('?,', count($ids) - 1) . '?';
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
$stmt->execute($ids);
$rows = $stmt->fetchAll();
$total = 0;
foreach($rows as $r){ $total += $r['price'] * $cart[$r['id']]; }

// buat order
$pdo->beginTransaction();
try{
    $stmt = $pdo->prepare('INSERT INTO orders (customer_id,total,status,payment_method,created_at) VALUES (?,?,?,?,NOW())');
    // insert status 'pending' initially
    $stmt->execute([$customer_id,$total,'pending',$payment_method]);
    $order_id = $pdo->lastInsertId();
    $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id,product_id,price,quantity) VALUES (?,?,?,?)');
    foreach($rows as $r){
        $pid = $r['id'];
        $qty = $cart[$pid];
        $stmtItem->execute([$order_id,$pid,$r['price'],$qty]);
        // update stock
        $stmtUp = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
        $stmtUp->execute([$qty,$pid]);
    }
    $pdo->commit();
    // kosongkan keranjang
    unset($_SESSION['cart']);

    // jika metode bank transfer, arahkan ke halaman simulasi pembayaran
    if($payment_method === 'bank_transfer'){
        // kirim email notifikasi (lampirkan invoice HTML)
        $stmtC = $pdo->prepare('SELECT email,name FROM customers WHERE id = ?');
        $stmtC->execute([$customer_id]);
        $cust = $stmtC->fetch();
        if($cust && filter_var($cust['email'], FILTER_VALIDATE_EMAIL)){
            $to = $cust['email'];
            $subject = "Order #$order_id - Konfirmasi Pesanan";
            // ambil invoice html (include invoice.php dengan id)
            $backupGet = $_GET;
            $_GET['id'] = $order_id;
            ob_start();
            include __DIR__.'/invoice.php';
            $invoiceHtml = ob_get_clean();
            $_GET = $backupGet;
            $boundary = md5(time());
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
            $message = "--$boundary\r\n";
            $message .= "Content-Type: text/html; charset=ISO-8859-1\r\n\r\n";
            $message .= "Terima kasih, pesanan Anda telah dibuat.<br>\r\n";
            $message .= $invoiceHtml . "\r\n";
            $message .= "--$boundary\r\n";
            $message .= "Content-Type: application/octet-stream; name=invoice_{$order_id}.html\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "Content-Disposition: attachment; filename=invoice_{$order_id}.html\r\n\r\n";
            $message .= chunk_split(base64_encode($invoiceHtml)) . "\r\n";
            $message .= "--$boundary--";
            // kirim (perlu konfigurasi mail server lokal)
            @mail($to, $subject, $message, $headers);
        }
        header('Location: payment.php?id='.$order_id); exit;
    }
    // jika COD, langsung tandai 'paid' sebagai simulasi
    if($payment_method === 'cod'){
        $u = $pdo->prepare('UPDATE orders SET status = ?, payment_ref = ? WHERE id = ?');
        $u->execute(['paid','COD',$order_id]);
        header('Location: order_success.php?id='.$order_id); exit;
    }

    // default redirect
    header('Location: order_success.php?id='.$order_id);
    exit;
}catch(Exception $e){
    $pdo->rollBack();
    echo '<p>Gagal memproses pesanan: '.htmlspecialchars($e->getMessage()).'</p>';
}

