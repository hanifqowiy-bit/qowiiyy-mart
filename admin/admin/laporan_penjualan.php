<?php
session_start();
include "../config.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan Penjualan</title>

<style>

*{
 margin:0;
 padding:0;
 box-sizing:border-box;
 font-family:Arial;
}

body{
 background:#f2f4f7;
 padding:20px;
}

.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:20px;
}

.box{
 background:white;
 padding:20px;
 border-radius:8px;
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
<h3>Laporan Penjualan</h3>
</div>

<div class="box">

<table>

<tr>
 <th>No</th>
 <th>Nama Produk</th>
 <th>Tanggal</th>
 <th>Jumlah</th>
 <th>Total</th>
</tr>

<?php

$no = 1;

$q = mysqli_query($koneksi,"
 SELECT nama_produk, harga, jumlah, tanggal
 FROM transaksi
 ORDER BY tanggal DESC
");

if(mysqli_num_rows($q)==0){

 echo "<tr>
        <td colspan='5'>Data belum ada</td>
       </tr>";

}else{

 while($d = mysqli_fetch_assoc($q)){

  $total = $d['harga'] * $d['jumlah'];
?>

<tr>
 <td><?= $no++ ?></td>
 <td><?= $d['nama_produk'] ?></td>
<td><?= substr($d['tanggal'], 0, 10) ?></td>
 <td><?= $d['jumlah'] ?></td>
 <td>Rp <?= number_format($total) ?></td>
</tr>

<?php
 }
}
?>

</table>

<a href="laporan.php" class="back">← Kembali</a>

</div>

</body>
</html>
