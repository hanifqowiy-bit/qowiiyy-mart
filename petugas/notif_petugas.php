<?php
session_start();
include "config.php";

/* CEK LOGIN PETUGAS */
if(
   !isset($_SESSION['petugas_login']) ||
   $_SESSION['petugas_login'] !== true ||
   $_SESSION['petugas_role'] !== 'petugas'
){
    header("Location: login.php");
    exit();
}

/* TANDAI SEMUA NOTIF UNREAD SEBAGAI READ */
mysqli_query($koneksi, "UPDATE notifications SET status='read' WHERE status='unread'");

/* AMBIL NOTIFIKASI DENGAN DETAIL TRANSAKSI */
$query = mysqli_query($koneksi, "
    SELECT notif.*, users.nama_lengkap, users.username, transaksi.nama_produk, transaksi.harga, transaksi.jumlah
    FROM notifications AS notif
    LEFT JOIN users ON users.id = notif.user_id
    LEFT JOIN transaksi ON transaksi.id = notif.transaksi_id
    ORDER BY notif.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifikasi Pembayaran Masuk</title>

<style>
body{
    font-family:Arial;
    background:#f2f4f7;
    padding:30px;
}

.container{
    background:white;
    width:750px;
    margin:auto;
    padding:25px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#333;
}

.card{
    background:#fafafa;
    border:1px solid #ddd;
    padding:15px 20px;
    border-radius:8px;
    margin-bottom:15px;
    line-height:1.5;
}

.card b{
    color:#555;
}

.card small{
    color:#777;
}

.btn{
    margin-top:20px;
    display:block;
    text-align:center;
    padding:12px;
    background:#006aff;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-size:16px;
    transition:0.2s;
}

.btn:hover{
    background:#0051c7;
}
</style>
</head>
<body>

<div class="container">

<h2>Notifikasi Pembayaran Masuk</h2>
<hr><br>

<?php if(mysqli_num_rows($query) == 0): ?>
    <p style="text-align:center; color:#777;">Tidak ada notifikasi.</p>
<?php endif; ?>

<?php while($n = mysqli_fetch_assoc($query)): ?>
<div class="card">
    <b>Pengguna:</b> <?= htmlspecialchars($n['nama_lengkap'] ?? $n['username'] ?? '-') ?><br>
    <b>Produk:</b> <?= htmlspecialchars($n['nama_produk'] ?? '-') ?><br>
    <b>Jumlah Transfer:</b> Rp <?= number_format(($n['harga'] ?? 0) * ($n['jumlah'] ?? 0)) ?><br>
    <b>Status:</b> <?= htmlspecialchars($n['status'] ?? '-') ?><br>
    <b>Pesan:</b> <?= htmlspecialchars($n['pesan'] ?? '-') ?><br>
    <small>Waktu: <?= date('d-m-Y H:i', strtotime($n['waktu'] ?? date('Y-m-d H:i'))) ?></small>
</div>
<?php endwhile; ?>

<a href="dashboard.php" class="btn">← Kembali ke Dashboard</a>

</div>

</body>
</html>