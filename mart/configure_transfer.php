<?php
session_start();
include "config.php";

/* ====================================================
   AMBIL DATA USER (UNTUK ALAMAT OTOMATIS)
==================================================== */
$user_id = $_SESSION['user_id'];
$qUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($qUser);

/* ====================================================
   CEK APAKAH USER SUDAH LOGIN
==================================================== */
if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$mode = "";

/* ====================================================
   MODE SINGLE PRODUCT
==================================================== */
if(isset($_GET['id'])){
    $mode = "single";

    $id = $_GET['id'];

    $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id'");
    $produk = mysqli_fetch_assoc($q);

    if(!$produk){
        header("Location:pemesanan.php");
        exit();
    }
}

/* ====================================================
   MODE CART
==================================================== */
elseif(!empty($_SESSION['cart'])){
    $mode = "cart";
}

/* ====================================================
   MODE INVALID
==================================================== */
else{
    header("Location:pemesanan.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Transfer</title>

<style>
/* Styling halaman checkout transfer */
body{
    font-family:Arial;
    background:#f2f6f9;
}

.container{
    display:flex;
    justify-content:center;
    margin-top:40px;
}

.box{
    background:white;
    border:1px solid #ccc;
    padding:25px;
    width:400px;
    border-radius:10px;
}

.form-group{
    margin-bottom:15px;
}

.form-group label{
    display:block;
    margin-bottom:5px;
}

.form-group input,
.form-group textarea{
    width:100%;
    padding:7px;
    border:1px solid #ccc;
    border-radius:5px;
}

.btn{
    width:100%;
    background:#e53935;
    color:white;
    border:none;
    padding:10px;
    border-radius:6px;
    cursor:pointer;
}

.btn:hover{
    opacity:0.9;
}

.back{
    display:block;
    margin-top:12px;
    text-align:center;
    text-decoration:none;
    color:#1e9bd7;
    font-size:14px;
}
</style>

</head>

<body>

<div class="container">
<div class="box">

<h3>Rincian Belanja (Transfer)</h3>

<?php if($mode=="single"): ?>

    <p><?= $produk['name'] ?></p>
    <p>Rp <?= number_format($produk['price']) ?></p>

<?php else: ?>

<?php
$total=0;

foreach($_SESSION['cart'] as $id=>$qty){

    $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id'");
    $p = mysqli_fetch_assoc($q);

    $sub = $p['price']*$qty;
    $total += $sub;
?>

<p><?= $p['name'] ?> (<?= $qty ?>x)</p>
<p>Rp <?= number_format($sub) ?></p>
<hr>

<?php } ?>

<b>Total: Rp <?= number_format($total) ?></b>

<?php endif; ?>

<br>

<h3>Alamat Pengiriman</h3>

<form action="proses.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="mode" value="<?= $mode ?>">

    <?php if($mode=="single"): ?>
    <input type="hidden" name="id_produk" value="<?= $produk['id'] ?>">
    <?php endif; ?>

    <div class="form-group">
    <label>Nama</label>
    <input type="text" name="nama" required>
    </div>

    <div class="form-group">
    <label>Telepon</label>
    <input type="text" name="telp" required>
    </div>

    <div class="form-group">
    <label>Alamat</label>
    <textarea name="alamat" required><?= $user['alamat'] ?? '' ?></textarea>
    </div>

    <div class="form-group">
    <label>Bukti Pembayaran</label>
    <input type="file" name="bukti" required>
    </div>

    <button type="submit" name="pesan" class="btn">
    Buat Pesanan
    </button>

<?php
$back = isset($_SESSION['back_to']) 
        ? $_SESSION['back_to'] 
        : 'pemesanan.php';
?>

<a href="<?= $back ?>" class="back">
← Kembali
</a>

</form>

</div>
</div>

</body>
</html>