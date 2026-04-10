<?php
session_start();
include "config.php";

/* AMBIL DATA USER UNTUK ALAMAT OTOMATIS */
$user_id = $_SESSION['user_id'];
$qUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($qUser);

/* CEK LOGIN */
if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$mode = "";

/* MODE SINGLE PRODUCT */
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

/* MODE CART */
elseif(!empty($_SESSION['cart'])){
    $mode = "cart";
}

/* MODE INVALID */
else{
    header("Location:pemesanan.php");
    exit();
}

/* ====================================================
   BIAYA ADMIN + LOGIKA PENGIRIMAN
   → COD juga bisa ada tambahan biaya untuk ekspres
==================================================== */
$biaya_admin = 5000; // dasar
?>
<!DOCTYPE html>
<html>
<head>
<title>COD Checkout</title>

<style>
body{font-family:Arial;background:#f2f6f9}
.container{display:flex;justify-content:center;margin-top:40px}
.box{background:white;border:1px solid #ccc;padding:25px;width:400px;border-radius:10px}
.form-group{margin-bottom:15px}
.form-group label{display:block;margin-bottom:5px}
.form-group input,.form-group textarea,.form-group select{width:100%;padding:7px;border:1px solid #ccc;border-radius:5px}
.btn{width:100%;background:#43a047;color:white;border:none;padding:10px;border-radius:6px;cursor:pointer}
.btn:hover{opacity:0.9}
.back{display:block;margin-top:12px;text-align:center;text-decoration:none;color:#1e9bd7;font-size:14px}
</style>

</head>

<body>

<div class="container">
<div class="box">

<h3>Rincian Belanja (COD)</h3>

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
<?php endif; ?>

<b>Subtotal: Rp <span id="subtotal"><?= number_format($mode=="single"?$produk['price']:$total) ?></span></b><br>
<b>Biaya Admin: Rp <span id="biaya_admin"><?= number_format($biaya_admin) ?></span></b><br>
<b>Total Bayar: Rp <span id="total_bayar"><?= number_format(($mode=="single"?$produk['price']:$total)+$biaya_admin) ?></span></b>

<br>

<h3>Alamat Pengiriman</h3>

<form action="proses.php" method="POST">

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
<input type="text" name="telp" value="<?= $user['no_hp'] ?? '' ?>" required>
</div>

<div class="form-group">
<label>Alamat</label>
<textarea name="alamat" required><?= $user['alamat'] ?? '' ?></textarea>
</div>

<!-- PILIHAN PENGIRIMAN -->
<div class="form-group">
<label>Pilih Pengiriman</label>
<select name="pengiriman" id="pengiriman" required>
    <option value="">-- Pilih Kurir --</option>
    <option value="J&T Reguler">J&T Reguler</option>
    <option value="J&T Ekspres">J&T Ekspres (+Rp10.000)</option>
    <option value="SiCepat Reguler">SiCepat Reguler</option>
    <option value="SiCepat Ekspres">SiCepat Ekspres (+Rp10.000)</option>
    <option value="POS Reguler">POS Reguler</option>
    <option value="POS Ekspres">POS Ekspres (+Rp10.000)</option>
</select>
</div>

<button type="submit" name="pesan" class="btn">Buat Pesanan COD</button>

<?php $back = isset($_SESSION['back_to']) ? $_SESSION['back_to'] : 'pemesanan.php'; ?>
<a href="<?= $back ?>" class="back">← Kembali</a>

</form>

</div>
</div>

<script>
// Update total saat pilih pengiriman
const pengiriman = document.getElementById('pengiriman');
const subtotalEl = document.getElementById('subtotal');
const biayaAdminEl = document.getElementById('biaya_admin');
const totalEl = document.getElementById('total_bayar');

let subtotal = <?= $mode=="single"?$produk['price']:$total ?>;
let biayaAdmin = <?= $biaya_admin ?>;

function updateTotal(){
    let tambahan = 0;
    if(pengiriman.value.toLowerCase().includes('ekspres')) tambahan = 10000;
    biayaAdminEl.textContent = (biayaAdmin + tambahan).toLocaleString('id-ID');
    totalEl.textContent = (subtotal + biayaAdmin + tambahan).toLocaleString('id-ID');
}

pengiriman.addEventListener('change', updateTotal);
updateTotal();
</script>

</body>
</html>