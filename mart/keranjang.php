<?php
session_start();
include "config.php";

/* ===========================================
   MENYIMPAN HALAMAN ASAL
   digunakan agar tombol "kembali" tahu harus
   balik ke mana (keranjang.php)
=========================================== */
$_SESSION['back_to'] = 'keranjang.php';

/* ===========================================
   JIKA KERANJANG BELUM ADA, BUAT ARRAY EMPTY
=========================================== */
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}


/* ===========================================
   TAMBAH PRODUK KE KERANJANG (ADD)
=========================================== */
if(isset($_GET['add'])){
    $id = $_GET['add'];

    // Jika sudah ada → tambah 1
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]++;
    }else{
        // Jika belum ada → set qty = 1
        $_SESSION['cart'][$id] = 1;
    }

    header("Location: keranjang.php");
    exit();
}

/* ===========================================
   TOMBOL PLUS (TAMBAH QTY)
=========================================== */
if(isset($_GET['plus'])){
    $id = $_GET['plus'];
    $_SESSION['cart'][$id]++;
    header("Location: keranjang.php");
    exit();
}

/* ===========================================
   TOMBOL MINUS (KURANGI QTY)
   jika qty > 1 → kurangi
   jika qty = 1 → hapus item
=========================================== */
if(isset($_GET['minus'])){
    $id = $_GET['minus'];

    if($_SESSION['cart'][$id] > 1){
        $_SESSION['cart'][$id]--;
    }else{
        unset($_SESSION['cart'][$id]);
    }

    header("Location: keranjang.php");
    exit();
}

/* ===========================================
   HAPUS PRODUK DARI KERANJANG
=========================================== */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    unset($_SESSION['cart'][$id]);

    header("Location: keranjang.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Keranjang</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#f5f5f5;
}

/* HEADER */
.header{
    background:#2196f3;
    color:white;
    padding:15px 30px;
    font-size:22px;
    font-weight:bold;
}

/* CONTAINER */
.container{
    padding:30px 50px;
}

/* TITLE */
.title{
    margin-bottom:25px;
    font-size:26px;
    font-weight:bold;
    color:#333;
}

/* ITEM */
.item{
    display:flex;
    align-items:center;
    background:white;
    padding:15px;
    border-radius:10px;
    margin-bottom:15px;
}

/* IMAGE */
.item img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:8px;
    margin-right:20px;
}

/* INFO PRODUK */
.info{
    flex:1;
}

.info h4{
    margin:0;
}

.info p{
    margin:5px 0;
    color:#555;
}

/* QTY BUTTON */
.qty{
    display:flex;
    align-items:center;
    gap:10px;
    margin-right:20px;
}

.qty a{
    text-decoration:none;
    border:1px solid #333;
    padding:3px 8px;
    border-radius:4px;
    color:black;
}

/* DELETE BUTTON */
.delete{
    background:red;
    color:white;
    text-decoration:none;
    padding:6px 12px;
    border-radius:6px;
    font-size:14px;
}

/* TOTAL */
.total{
    margin-top:30px;
    background:white;
    padding:20px;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:20px;
    font-weight:bold;
}

/* CHECKOUT BUTTON */
.checkout{
    background:#2196f3;
    color:white;
    padding:10px 20px;
    border-radius:8px;
    border:none;
    cursor:pointer;
    font-size:16px;
}

/* BACK BUTTON */
.back{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    font-size:30px;
    color:#2196f3;
}

.empty{
    text-align:center;
    margin-top:50px;
    color:#777;
}

/* POPUP */
.popup{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.popup-box{
    background:white;
    width:350px;
    padding:25px;
    border-radius:10px;
    text-align:center;
}

.popup-box h3{
    margin-bottom:20px;
}

.popup-btn{
    display:flex;
    justify-content:space-between;
}

.btn-transfer,
.btn-cod{
    width:45%;
    padding:10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
    background:#2196f3;
}

.btn-close{
    margin-top:15px;
    background:#aaa;
    color:white;
    border:none;
    padding:7px 15px;
    border-radius:5px;
    cursor:pointer;
}

</style>


<script>

/* ===========================================
   MUNCULKAN POPUP PILIH METODE PEMBAYARAN
=========================================== */
function openPopup(){
    document.getElementById("popup").style.display = "flex";
}

/* ===========================================
   TUTUP POPUP
=========================================== */
function closePopup(){
    document.getElementById("popup").style.display = "none";
}

/* ===========================================
   PILIH METODE PEMBAYARAN
   Transfer → configure_transfer.php
   COD      → configure_cod.php
=========================================== */
function pilihMetode(metode){

    if(metode === "Transfer"){
        window.location.href = "configure_transfer.php";
    }

    if(metode === "COD"){
        window.location.href = "configure_cod.php";
    }

}

</script>


</head>

<body>

<div class="header">
KOWI-MART
</div>

<div class="container">

<h2 class="title">Keranjang Belanja</h2>

<?php 

$total = 0;

/* ===========================================
   JIKA KERANJANG KOSONG
=========================================== */
if(empty($_SESSION['cart'])): ?>

<div class="empty">
Keranjang kosong kaya hatiku
</div>

<?php else: ?>

<?php foreach($_SESSION['cart'] as $id=>$qty):

$q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id'");
$p = mysqli_fetch_assoc($q);

$subtotal = $p['price'] * $qty;
$total += $subtotal;

?>

<div class="item">

<img src="../assets/<?php echo $p['photo']; ?>">

<div class="info">
<h4><?= $p['name'] ?></h4>
<p>Rp <?= number_format($p['price']) ?></p>
<p>Subtotal: Rp <?= number_format($subtotal) ?></p>
</div>

<div class="qty">

<!-- tombol minus -->
<a href="?minus=<?= $id ?>">−</a>

<!-- jumlah produk -->
<span><?= $qty ?></span>

<!-- tombol plus -->
<a href="?plus=<?= $id ?>">+</a>

</div>

<!-- tombol hapus produk -->
<a href="?delete=<?= $id ?>" class="delete">
Hapus
</a>

</div>

<?php endforeach; ?>

<!-- TOTAL -->
<div class="total">

<span>Total:</span>

<span>Rp <?= number_format($total) ?></span>

<!-- tombol checkout -->
<button class="checkout" onclick="openPopup()">
Checkout
</button>

</div>

<?php endif; ?>

<!-- tombol kembali -->
<a href="pemesanan.php" class="back">←</a>

</div>


<!-- POPUP PEMBAYARAN -->
<div class="popup" id="popup">

    <div class="popup-box">

        <h3>Pilih Metode Pembayaran</h3>

        <div class="popup-btn">

            <!-- Transfer -->
            <button class="btn-transfer"
            onclick="pilihMetode('Transfer')">
                Transfer
            </button>

            <!-- COD -->
            <button class="btn-cod"
            onclick="pilihMetode('COD')">
                COD
            </button>

        </div>

        <!-- tombol batal -->
        <button class="btn-close" onclick="closePopup()">
            Batal
        </button>

    </div>

</div>

</body>
</html>