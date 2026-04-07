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
<title>Laporan Stok</title>

<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}

body{background:#f2f4f7;padding:20px}

/* HEADER */
.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:20px;
}

/* BOX */
.box{
 background:white;
 padding:20px;
 border-radius:8px;
}

/* TABLE */
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

/* BACK */
.back{
 display:inline-block;
 margin-top:15px;
 color:#2196f3;
 text-decoration:none;
 font-weight:bold;
}

</style>
</head>

<body>

<div class="header">
<h3>Laporan Stok</h3>
</div>

<div class="box">

<table>

<tr>
 <th>No</th>
 <th>Nama Barang</th>
 <th>Stok</th>
 <th>Terjual</th>
 <th>Sisa Stok</th>
</tr>

<?php

$no=1;

/* Hitung stok + terjual */
$q=mysqli_query($koneksi,"
 SELECT 
   p.name,
   p.stock,
   IFNULL(SUM(t.jumlah),0) AS terjual
 FROM products p
 LEFT JOIN transaksi t 
   ON p.name = t.nama_produk
 GROUP BY p.id
");

while($d=mysqli_fetch_assoc($q)){

 $sisa = $d['stock'] - $d['terjual'];
?>

<tr>
 <td><?= $no++ ?></td>
 <td><?= $d['name'] ?></td>
 <td><?= $d['stock'] ?></td>
 <td><?= $d['terjual'] ?></td>
 <td><?= $sisa ?></td>
</tr>

<?php } ?>

</table>

<a href="laporan_petugas.php" class="back">← Kembali</a>

</div>

</body>
</html>
