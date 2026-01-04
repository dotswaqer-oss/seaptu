<?php
require_once 'koneksi.php';
$pdo = getPDO();
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM customers WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password'])){
        $_SESSION['customer_id'] = $user['id'];
        $_SESSION['customer_name'] = $user['name'];
        header('Location: index.php'); exit;
    }else{
        $errors[] = 'Email atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html><body>
<h2>Login</h2>
<?php foreach($errors as $err) echo '<p style="color:red">'.htmlspecialchars($err).'</p>'; ?>
<form method="post">
    <label>Email</label><br>
    <input name="email" type="email"><br>
    <label>Password</label><br>
    <input name="password" type="password"><br>
    <button type="submit">Login</button>
</form>
<p><a href="register.php">Register</a></p>
</body></html>
