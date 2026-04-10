<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

/* AMBIL DATA USER */
$user_id = $_SESSION['user_id'];
$qUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($qUser);

/* CEK MODE */
$mode = "";
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
elseif(!empty($_SESSION['cart'])){
    $mode = "cart";
}
else{
    header("Location:pemesanan.php");
    exit();
}

/* HITUNG SUBTOTAL */
$subtotal = 0;
if($mode=="single"){
    $subtotal = $produk['price'];
} else {
    foreach($_SESSION['cart'] as $id=>$qty){
        $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id'");
        $p = mysqli_fetch_assoc($q);
        $subtotal += $p['price']*$qty;
    }
}

/* BIAYA ADMIN */
$biaya_admin = 5000; // dasar
?>

<!DOCTYPE html>
<html>
<head>
<title>Transfer</title>
<style>
body{font-family:Arial;background:#f2f6f9}
.container{display:flex;justify-content:center;margin-top:40px}
.box{background:white;border:1px solid #ccc;padding:25px;width:400px;border-radius:10px}
.form-group{margin-bottom:15px}
.form-group label{display:block;margin-bottom:5px}
.form-group input,.form-group textarea,.form-group select{width:100%;padding:7px;border:1px solid #ccc;border-radius:5px}
.btn{width:100%;background:#e53935;color:white;border:none;padding:10px;border-radius:6px;cursor:pointer}
.btn:hover{opacity:0.9}
.back{display:block;margin-top:12px;text-align:center;text-decoration:none;color:#1e9bd7;font-size:14px}
.transfer-box{padding:10px;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;margin-bottom:15px}
</style>
</head>
<body>
<div class="container">
<div class="box">

<h3>Rincian Belanja (Transfer)</h3>

<div class="transfer-box">
<b>Tujuan Transfer</b><br>
DANA : <b>081234567890</b><br>
A/N : <b>Hanip</b>
</div>

<?php if($mode=="single"): ?>
<p><?= $produk['name'] ?></p>
<p>Rp <?= number_format($produk['price']) ?></p>
<?php else: ?>
<?php
foreach($_SESSION['cart'] as $id=>$qty){
    $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id'");
    $p = mysqli_fetch_assoc($q);
    $sub = $p['price']*$qty;
?>
<p><?= $p['name'] ?> (<?= $qty ?>x)</p>
<p>Rp <?= number_format($sub) ?></p>
<hr>
<?php } ?>
<?php endif; ?>

<b>Subtotal: Rp <span id="subtotal"><?= number_format($subtotal) ?></span></b><br>
<b>Biaya Admin: Rp <span id="biaya_admin"><?= number_format($biaya_admin) ?></span></b><br>
<b>Total Bayar: Rp <span id="total_bayar"><?= number_format($subtotal+$biaya_admin) ?></span></b>

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
<input type="text" name="no_hp" value="<?= $user['no_hp'] ?? '' ?>" required>
</div>

<div class="form-group">
<label>Alamat</label>
<textarea name="alamat" required><?= $user['alamat'] ?? '' ?></textarea>
</div>

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

<div class="form-group">
<label>Bukti Pembayaran</label>
<input type="file" name="bukti" required>
</div>

<button type="submit" name="pesan" class="btn">Buat Pesanan</button>

<?php $back = $_SESSION['back_to'] ?? 'pemesanan.php'; ?>
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

let subtotal = <?= $subtotal ?>;
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