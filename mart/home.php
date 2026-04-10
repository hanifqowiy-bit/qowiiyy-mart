<?php
session_start();
include "config.php";

// LOGIKA SEARCH
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $produk = mysqli_query($koneksi, "SELECT * FROM products WHERE name LIKE '%$cari%' ");
} else {
    $produk = mysqli_query($koneksi, "SELECT * FROM products");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>KOWI-MART</title>
<script src="https://unpkg.com/lucide@latest"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}
body{background:#f2f6f9;}
.container{display:flex;min-height:100vh;width:100%;}

/* SIDEBAR */
.sidebar{width:220px;background:#1e9bd7;min-height:100vh;color:white;padding:20px;position:sticky;top:0;}
.sidebar h2{text-align:center;margin-bottom:30px;}
.sidebar a{display:flex;align-items:center;gap:8px;color:white;text-decoration:none;padding:12px;margin-bottom:10px;border-radius:6px;}
.sidebar a:hover,.sidebar .active{background:rgba(255,255,255,0.2);}

/* CONTENT */
.content{flex:1;padding:25px;min-height:100vh;display:flex;flex-direction:column;}

/* HEADER */
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;}
.header h1{font-size:32px;}
.header-right a{display:inline-flex;align-items:center;gap:6px;background:#1e9bd7;color:white;padding:8px 15px;border-radius:20px;text-decoration:none;margin-left:10px;font-size:14px;}
.header-right a:hover{background:#157db3;}

/* SEARCH */
.search-area{display:flex;align-items:center;gap:10px;margin-bottom:18px;}
.back-btn{padding:10px 15px;background:#1e9bd7;border-radius:8px;color:white;text-decoration:none;display:flex;align-items:center;gap:6px;font-size:14px;}
.back-btn:hover{background:#157db3;}
.search-box{flex:1;}
.search-box input{width:100%;padding:12px;border-radius:8px;border:1px solid #aaa;font-size:14px;}

/* PRODUK */
.produk-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:25px;margin-bottom:20px;}
.card{background:white;border-radius:12px;padding:12px;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,0.08);transition:all 0.3s ease;cursor:pointer;}
.card:hover{transform:translateY(-6px);box-shadow:0 8px 20px rgba(0,0,0,0.15);}
.card img{width:100%;height:200px;object-fit:cover;border-radius:10px;}
.card-body{padding:0;}
.card-body h3{font-size:15px;margin:10px 0 5px;}
.price{color:#169bd5;font-weight:bold;font-size:15px;margin-bottom:10px;}
.btn-group{display:flex;justify-content:space-between;}
.btn-cart,.btn-buy{flex:1;border:none;padding:7px;font-size:13px;cursor:pointer;border-radius:5px;margin:0 2px;}
.btn-cart{background:#ddd;}
.btn-buy{background:#1e9bd7;color:white;}
.btn-buy:hover{background:#157db3;}

/* FOOTER */
.footer{margin-top:auto;padding:15px;text-align:center;background:white;border-radius:10px;color:#666;font-size:14px;box-shadow:0 2px 8px rgba(14, 101, 216, 0.05);}
.footer b{color:#169bd5;}
</style>
</head>
<body>
<div class="container">

<div class="sidebar">
    <h2>KOWI-MART</h2>
    <a href="home.php" class="active"><i data-lucide="home"></i> Home</a>
    <a href="index.php"><i data-lucide="shopping-cart"></i> Pemesanan</a>
    <a href="index.php"><i data-lucide="log-out"></i> Keluar</a>
</div>

<div class="content">
    <div class="header">
        <div>
            <h1>Selamat Datang</h1>
            <p>Belanja mudah, cepat, terpercaya.</p>
        </div>

        <div class="header-right">
            <!-- Chat (dipindah ke posisi pertama) -->
            <a href="index.php" title="Chat Petugas"><i data-lucide="message-circle"></i></a>
            <!-- Profile (dipindah jadi kedua) -->
            <a href="index.php" title="Profile"><i data-lucide="user"></i></a>
            <!-- Login (ditambahkan) -->
            <a href="index.php" title="Login"><i data-lucide="log-in"></i> Login</a>
        </div>
    </div>

    <div class="search-area">
        <?php if(isset($_GET['cari']) && $_GET['cari'] != ""){ ?>
            <a href="home.php" class="back-btn"><i data-lucide="arrow-left"></i> Kembali</a>
        <?php } ?>
        <form method="GET" class="search-box">
            <input type="text" name="cari" placeholder="Cari produk..." value="<?php echo isset($_GET['cari'])?$_GET['cari']:''; ?>">
        </form>
    </div>

    <div class="judul-produk">Produk</div>

    <div class="produk-list">
    <?php while($p=mysqli_fetch_assoc($produk)): ?>
        <div class="card">
            <img src="../assets/<?php echo $p['photo']; ?>">
            <div class="card-body">
                <h3><?php echo $p['name']; ?></h3>
                <div class="price">Rp <?php echo number_format($p['price']); ?></div>
                <div class="btn-group">
                    <button class="btn-cart" onclick="window.location.href='index.php'"><i data-lucide="shopping-cart"></i></button>
                    <button class="btn-buy" onclick="window.location.href='index.php'">Beli</button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>

    <div class="footer">
        <b>KOWI-MART</b><br>&copy; 2026 KOWI-MART. All Rights Reserved.
    </div>

</div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>