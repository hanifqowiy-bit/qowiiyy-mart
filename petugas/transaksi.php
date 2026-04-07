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
?>

<!DOCTYPE html>
<html>
<head>
<title>Manajemen Transaksi</title>

<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}

body{background:#f2f4f7}

.wrapper{display:flex;height:100vh}

/* SIDEBAR */
.sidebar{
 width:230px;
 background:#2196f3;
 color:white;
 padding:20px;
}

.sidebar h2{text-align:center;margin-bottom:30px}

.sidebar a{
 display:block;
 color:white;
 text-decoration:none;
 padding:10px;
 border-radius:5px;
}

.sidebar a:hover,
.active{
 background:rgba(255,255,255,.2);
}

/* MAIN */
.main{flex:1;padding:20px;background:white}

.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:15px;
}

table{
 width:100%;
 border-collapse:collapse;
}

th,td{
 border:1px solid #ddd;
 padding:8px;
 text-align:center;
}

th{
 background:#2196f3;
 color:white;
}

.btn{
 padding:5px 10px;
 background:#2196f3;
 color:white;
 border:none;
 border-radius:4px;
 text-decoration:none;
}

</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">

<h2>KOWI-MART</h2>

<a href="dashboard.php">Dashboard</a>
<a href="transaksi.php" class="active">Transaksi</a>
<a href="produk.php">Data Produk</a>
<a href="laporan_petugas.php">Laporan</a>

<br><br>

<a href="logout.php">Keluar</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
<h3>Manajemen Transaksi</h3>
</div>

<table>

<tr>
 <th>No</th>
 <th>Username</th>
 <th>Tanggal</th>
 <th>Total</th>
 <th>Metode</th>
 <th>Status</th> <!-- DITAMBAHKAN -->
 <th>Konfirmasi</th>
</tr>

<?php

$no = 1;

/* JOIN transaksi + users */
$q = mysqli_query($koneksi,"
 SELECT 
  transaksi.*,
  users.username
 FROM transaksi
 JOIN users ON transaksi.user_id = users.id
 ORDER BY tanggal DESC
");

while($t = mysqli_fetch_assoc($q)){

 // Hitung total
 $total = $t['harga'] * $t['jumlah'];

 // Tentukan metode
 if(!empty($t['bukti'])){
   $metode = "TRANSFER";
 }else{
   $metode = "COD";
 }

 // Ambil status dari database
 $status = $t['status']; 
?>

<tr>

<td><?= $no++ ?></td>
<td><?= $t['username'] ?></td>
<td><?= substr($t['tanggal'], 0, 10) ?></td>
<td>Rp <?= number_format($total) ?></td>
<td><?= $metode ?></td>

<!-- STATUS DITAMPILKAN -->
<td><?= $status ?></td>

<!-- Tombol menuju halaman konfirmasi -->
<td>
   <a href="konfirmasi_detail.php?id=<?= $t['id'] ?>" class="btn">
      Edit
   </a>
</td>

</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>