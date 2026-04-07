<?php
session_start(); 
// Memulai sesi untuk memastikan hanya user yang sudah login yang bisa akses halaman

include "../config.php";
// Menghubungkan file ini dengan konfigurasi database

// CEK ROLE USER
if($_SESSION['role']!='admin'){
    // Jika role bukan admin maka redirect ke halaman login
    header("Location: ../login.php");
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
// Nomor urut untuk tabel laporan

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
// Query mengambil daftar produk + stok + jumlah terjual
// LEFT JOIN agar produk tetap tampil meskipun belum pernah terjual
// IFNULL digunakan agar nilai NULL menjadi 0 jika tidak ada transaksi

while($d=mysqli_fetch_assoc($q)){
// Looping setiap baris data produk

 $sisa = $d['stock'] - $d['terjual'];
 // Hitung sisa stok = stok awal - jumlah terjual
?>

<tr>
 <td><?= $no++ ?></td> <!-- Menampilkan nomor urut -->
 <td><?= $d['name'] ?></td> <!-- Nama Barang -->
 <td><?= $d['stock'] ?></td> <!-- Stok total -->
 <td><?= $d['terjual'] ?></td> <!-- Jumlah terjual -->
 <td><?= $sisa ?></td> <!-- Sisa stok -->
</tr>

<?php } ?>

</table>

<a href="laporan.php" class="back">← Kembali</a>
<!-- Tombol kembali ke halaman laporan utama -->

</div>

</body>
</html>