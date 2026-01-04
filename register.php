<?php
require_once 'koneksi.php';
$pdo = getPDO();

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if(!$name || !$email || !$password){
        $errors[] = 'Semua field wajib diisi.';
    }
    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO customers (name,email,password) VALUES (?,?,?)');
        try{
            $stmt->execute([$name,$email,$hash]);
            header('Location: login.php'); exit;
        }catch(PDOException $e){
            $errors[] = 'Email sudah terdaftar.';
        }
    }
}
?>
<!DOCTYPE html>
<html><body>
<h2>Register</h2>
<?php foreach($errors as $err) echo '<p style="color:red">'.htmlspecialchars($err).'</p>'; ?>
<form method="post">
    <label>Nama</label><br>
    <input name="name"><br>
    <label>Email</label><br>
    <input name="email" type="email"><br>
    <label>Password</label><br>
    <input name="password" type="password"><br>
    <button type="submit">Daftar</button>
</form>
<p><a href="login.php">Login</a></p>
</body></html>
