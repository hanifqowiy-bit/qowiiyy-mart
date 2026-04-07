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
<title>Laporan Transaksi</title>

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

/* STATUS */
.success{
 color:green;
 font-weight:bold;
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
<h3>Laporan Transaksi</h3>
</div>

<div class="box">

<table>

<tr>
 <th>ID</th>
 <th>Username</th>
 <th>Tanggal</th>
 <th>Status</th>
</tr>

<?php

$q=mysqli_query($koneksi,"
 SELECT 
   transaksi.id,
   users.username,
   transaksi.tanggal,
   transaksi.status
 FROM transaksi
 JOIN users ON transaksi.user_id = users.id
 ORDER BY transaksi.tanggal DESC
");

while($d=mysqli_fetch_assoc($q)){
?>

<tr>
 <td><?= $d['id'] ?></td>
 <td><?= $d['username'] ?></td>
 <td><?= substr($d['tanggal'], 0, 10) ?></td>
 <td><?= $d['status'] ?></td>
</tr>

<?php } ?>

</table>

<a href="laporan_petugas.php" class="back">← Kembali</a>

</div>

</body>
</html>