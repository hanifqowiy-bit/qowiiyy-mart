<?php
session_start();
include "config.php";

$_SESSION['back_to'] = 'pemesanan.php';

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

/* LOGIKA SEARCH — size dihapus */
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $produk = mysqli_query($koneksi,
        "SELECT * FROM products 
         WHERE name LIKE '%$cari%'"
    );
} else {
    $produk = mysqli_query($koneksi,"SELECT * FROM products");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Pemesanan</title>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f2f6f9;
}

.container{
    display:flex;
    min-height:100vh;
    width:100%;
}

/* SIDEBAR */
.sidebar{
    width:220px;
    background:#1e9bd7;
    min-height:100vh;
    color:white;
    padding:20px;
    position:sticky;
    top:0;
}

.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:8px;
    color:white;
    text-decoration:none;
    padding:12px;
    margin-bottom:10px;
    border-radius:6px;
}

.sidebar a:hover,
.sidebar .active{
    background:rgba(255,255,255,0.2);
}

/* CONTENT */
.content{
    flex:1;
    padding:25px;
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h1{
    font-size:32px;
}

.header-right a{
    display:inline-flex;
    align-items:center;
    gap:6px;
    background:#1e9bd7;
    color:white;
    padding:8px 15px;
    border-radius:20px;
    text-decoration:none;
    margin-left:10px;
    font-size:14px;
}

.header-right a:hover{
    background:#157db3;
}

.title{
    font-size:20px;
    margin-bottom:15px;
}

/* SEARCH */
.search-area{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:18px;
}

.back-btn{
    padding:10px 15px;
    background:#1e9bd7;
    border-radius:8px;
    color:white;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:6px;
    font-size:14px;
}

.back-btn:hover{
    background:#157db3;
}

.search-box{
    flex:1;
}

.search-box input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #aaa;
    font-size:14px;
}

/* =========================================
   PRODUK — DISESUAIKAN DENGAN DASHBOARD.PHP
========================================= */
.produk-list{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(200px,1fr));
    gap:25px;
    margin-bottom:20px;
}

.card{
    background:white;
    border-radius:12px;
    padding:12px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:all 0.3s ease;
    cursor:pointer;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.card img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:10px;
}

.card-body{
    padding:0;
}

.card-body h3{
    font-size:15px;
    margin:10px 0 5px;
}

.price{
    color:#169bd5;
    font-weight:bold;
    font-size:15px;
    margin-bottom:10px;
}

/* tombol */
.btn-group{
    display:flex;
    justify-content:space-between;
}

.btn-cart,
.btn-buy{
    flex:1;
    border:none;
    padding:7px;
    font-size:13px;
    cursor:pointer;
    border-radius:5px;
    margin:0 2px;
}

.btn-cart{
    background:#ddd;
}

.btn-buy{
    background:#1e9bd7;
    color:white;
}

.btn-buy:hover{
    background:#157db3;
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

.popup-btn{
    display:flex;
    justify-content:space-between;
    margin-top:20px;
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

.judul-produk{
    font-size:20px;
    margin-bottom:15px;
    color:#333;
    border-left:5px solid #169bd5;
    padding-left:10px;
}

/* FOOTER */
.footer{
    margin-top:auto;
    padding:15px;
    text-align:center;
    background:white;
    border-radius:10px;
    color:#666;
    font-size:14px;
    box-shadow:0 2px 8px rgba(14, 101, 216, 0.05);
}

.footer b{
    color:#169bd5;
}
</style>

<script>
let idProduk = "";

function openPopup(id){
    idProduk = id;
    document.getElementById("popup").style.display = "flex";
}

function closePopup(){
    document.getElementById("popup").style.display = "none";
}

function pilihMetode(metode){
    if(metode === "Transfer"){
        window.location.href = "configure_transfer.php?id=" + idProduk;
    }
    else if(metode === "COD"){
        window.location.href = "configure_cod.php?id=" + idProduk;
    }
}
</script>

</head>
<body>

<div class="container">

<div class="sidebar">

    <h2>KOWI-MART</h2>

    <a href="dashboard.php">
        <i data-lucide="home"></i> Home
    </a>

    <a href="pemesanan.php" class="active">
        <i data-lucide="shopping-cart"></i> Pemesanan
    </a>

    <a href="logout.php">
        <i data-lucide="log-out"></i> Keluar
    </a>

</div>

<div class="content">

<div class="header">
    <div>
        <h1>Selamat</h1>
        <p>Berbelanja</p>
    </div>

    <div class="header-right">
        <a href="keranjang.php">
            <i data-lucide="shopping-cart"></i> Keranjang
        </a>

        <a href="riwayat.php">
            <i data-lucide="history"></i> Riwayat
        </a>
    </div>
</div>

<div class="search-area">

    <?php if(isset($_GET['cari']) && $_GET['cari'] != ""){ ?>
        <a href="pemesanan.php" class="back-btn">
            <i data-lucide="arrow-left"></i> Kembali
        </a>
    <?php } ?>

    <form method="GET" class="search-box">
        <input 
            type="text" 
            name="cari" 
            placeholder="Cari produk..." 
            value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
    </form>

</div>

<div class="judul-produk">Produk</div>

<div class="produk-list">

<?php while($p=mysqli_fetch_assoc($produk)): ?>

<div class="card">

    <img src="../assets/<?php echo $p['photo']; ?>">

    <div class="card-body">

        <h3><?php echo $p['name']; ?></h3>

        <div class="price">
            Rp <?php echo number_format($p['price']); ?>
        </div>

        <div class="btn-group">

            <a href="keranjang.php?add=<?php echo $p['id']; ?>">
                <button class="btn-cart">
                    <i data-lucide="shopping-cart"></i>
                </button>
            </a>

            <button class="btn-buy"
            onclick="openPopup('<?php echo $p['id']; ?>')">
                Beli
            </button>

        </div>

    </div>

</div>

<?php endwhile; ?>

</div>

<div class="footer">
    <b>KOWI-MART</b><br>
    &copy; 2026 KOWI-MART. All Rights Reserved.
</div>

</div>

</div>

<!-- POPUP -->
<div class="popup" id="popup">
    <div class="popup-box">
        <h3>Pilih Metode Pembayaran</h3>

        <div class="popup-btn">
            <button class="btn-transfer" onclick="pilihMetode('Transfer')">Transfer</button>
            <button class="btn-cod" onclick="pilihMetode('COD')">COD</button>
        </div>

        <button class="btn-close" onclick="closePopup()">Batal</button>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>