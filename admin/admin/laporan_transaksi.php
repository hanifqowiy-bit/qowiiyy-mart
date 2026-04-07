<?php
session_start(); 
// Memulai session untuk memastikan user yang login dapat mengakses halaman

include "../config.php";
// Menyertakan konfigurasi database

// CEK ROLE USER (Hanya admin yang boleh masuk)
if($_SESSION['role']!='admin'){
 header("Location: ../login.php"); // Jika bukan admin, arahkan ke login
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

/* BACK BUTTON */
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
<h3>Laporan Transaksi</h3> <!-- Judul halaman -->
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

// Query mengambil data transaksi + username
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
// JOIN digunakan untuk menggabungkan data transaksi dengan username di tabel users

while($d=mysqli_fetch_assoc($q)){
// Loop menampilkan tiap data transaksi
?>

<tr>
 <td><?= $d['id'] ?></td> <!-- Menampilkan ID transaksi -->
 <td><?= $d['username'] ?></td> <!-- Username pemilik transaksi -->
 <td><?= substr($d['tanggal'], 0, 10) ?></td> <!-- Menampilkan tanggal (format YYYY-MM-DD) -->
 <td><?= $d['status'] ?></td> <!-- Status transaksi -->
</tr>

<?php } ?>

</table>

<a href="laporan.php" class="back">← Kembali</a>
<!-- Tombol menuju halaman laporan utama -->

</div>

</body>
</html>