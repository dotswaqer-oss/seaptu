<?php
require_once 'koneksi.php';
$pdo = getPDO();

// ambil produk
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mukarakat - Toko Sepatu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo"><img src="images/WhatsApp_Image_2023-12-19_at_17.16.10_486e93bd-removebg-preview.png" alt="Mukarakat logo"></a>
        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#about">about</a>
            <a href="#products">products</a>
            <a href="#review">review</a>
            <a href="#size chart">size chart</a>
        </nav>
        <div class="icons">
            <div class="fas fa-search" id="search-btn"></div>
            <div class="fas fa-shopping-cart" id="cart-btn"></div>
            <div class="fas fa-bars" id="menu-btn"></div>
            <?php if(!empty($_SESSION['customer_id'])): ?>
                <a href="profile.php">Halo, <?=htmlspecialchars($_SESSION['customer_name'] ?? 'Pelanggan')?></a> |
                <a href="my_orders.php">Pesanan</a> |
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a> | <a href="register.php">Register</a>
            <?php endif; ?>
        </div>

        <div class="search-form">
            <input type="search" id="search-box" placeholder="search here...">
            <label for="search-box" class="fas fa-search"></label>
        </div>
    </header>

    <section class="home" id="home">
        <div class="content">
            <h3>Selamat datang di Mukarakat</h3>
            <p>Toko sepatu lokal terpercaya sejak 2019. Pilih sepatu favoritmu sekarang.</p>
            <a href="#products" class="btn">Lihat Produk</a>
        </div>
    </section>

    <section class="about" id="about">
        <h1 class="heading"> <span>about</span> </h1>
        <div class="row">
            <div class="image"><img src="./images/fullsepatu.jpeg" alt="about"></div>
            <div class="content">
                <h3>tentang kami</h3>
                <p>mukarakat menjual beberapa brand sepatu lokal seperti ventela, patrobas, dan warrior, tersedia berbagai ukuran dan warna.</p>
                <p>mukarakat adalah toko online sejak 2019. Gambar dan real pict hampir 100% mirip, berkualitas dan terpercaya.</p>
                <a href="#products" class="btn">learn more</a>
            </div>
        </div>
    </section>

    <section class="menu products" id="products">
        <h1 class="heading"> our <span>PRODUCTS</span> </h1>
        <div class="box-container">
            <?php foreach($products as $p): ?>
            <div class="box">
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?=htmlspecialchars($p['name'])?>">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <div class="price">Rp <?= number_format($p['price'],0,',','.') ?></div>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <input type="number" name="qty" value="1" min="1" max="<?= $p['stock'] ?>">
                    <button class="btn" type="submit" name="add">add to cart</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="review" id="review">
        <h1 class="heading"> review </h1>
        <div class="box-container">
            <div class="box">
                <div class="image"><img src="./images/ventela back to 70s.jpeg" alt=""></div>
                <div class="content"><h3>ventela back to 70s</h3><div class="price">Rp 150.000 <span>Rp 200.000</span></div></div>
            </div>
            <div class="box">
                <div class="image"><img src="./images/patrobasequiblownavy.jpeg" alt=""></div>
                <div class="content"><h3>Patrobas Equib Low Navy</h3><div class="price">Rp 159.900 <span>Rp 209.900</span></div></div>
            </div>
        </div>
    </section>

    <section class="size-chart" id="size chart">
        <h1 class="heading"> size <span>chart</span> </h1>
        <div class="box-container">
            <div class="box"><div class="content"><p>Silakan luangkan waktu sejenak untuk mengamati size chart kami. Ini akan membantumu memilih ukuran yang sesuai.</p></div></div>
            <div class="box"><img src="./images/sizechart.jpg" alt="sizechart" class="quote"></div>
        </div>
    </section>

    <section class="footer">
        <div class="share"><a href="#" class="fab fa-instagram"></a> <a href="#" class="fab fa-whatsapp"></a></div>
        <div class="links"><a href="#home">home</a> <a href="#about">about</a> <a href="#products">products</a> <a href="#size chart">size chart</a></div>
        <div class="credit">paskalis fajar aprianto | 22.01.55.0039</div>
    </section>

    <!-- cart modal -->
    <div id="cart-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:2000;align-items:center;justify-content:center;">
        <div style="background:#fff;width:90%;max-width:900px;padding:1rem;position:relative;border-radius:8px">
            <button id="cart-close" style="position:absolute;right:8px;top:8px">Close</button>
            <iframe src="cart.php" style="width:100%;height:60vh;border:none;"></iframe>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
