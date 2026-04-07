<?php
session_start(); 
// Memulai session agar halaman hanya bisa diakses oleh user login

include "../config.php";
// Menghubungkan ke database melalui file config

// CEK ROLE USER
if($_SESSION['role']!='admin'){
 header("Location: ../login.php"); // Jika bukan admin, langsung diarahkan ke login
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan</title>

<!-- ICON (Font Awesome) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Digunakan untuk ikon pada menu dan kartu -->

<style>

/* RESET */
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}

/* LAYOUT UMUM */
body{background:#f2f4f7}

.wrapper{display:flex;height:100vh}
/* wrapper = membagi halaman jadi sidebar + main */

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
 background:rgba(255,255,255,.2); /* efek hover & menu aktif */
}

/* MAIN CONTENT */
.main{flex:1;padding:20px;background:white}

/* HEADER HALAMAN */
.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:20px;
}

/* CARD CONTAINER */
.cards{
 display:flex;
 gap:20px;
 margin-top:20px;
 flex-wrap:wrap; /* supaya rapi di layar kecil */
}

/* CARD INDIVIDU */
.card{
 width:220px;
 height:160px;
 background:#2196f3;
 color:white;
 border-radius:10px;
 padding:20px;
 cursor:pointer;
 transition:.2s;
 text-decoration:none;
}

/* EFEK HOVER CARD */
.card:hover{
 transform:scale(1.05);
}

/* JUDUL CARD */
.card h3{
 margin-bottom:20px;
}

/* IKON BESAR */
.icon{
 font-size:55px;
 text-align:right;
 opacity:.9;
}

.card i{
 color:white;
}

</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">

<h2>KOWI-MART</h2> <!-- Judul sidebar -->

<!-- MENU -->
<a href="dashboard.php">Dashboard</a>
<a href="manajemen_user.php">Manajemen User</a>
<a href="laporan.php" class="active">Laporan</a>

<!-- MENU YANG DIBENARKAN -->
<a href="manajemen_produk.php">Manajemen Produk</a>
<a href="manajemen_transaksi.php">Manajemen Transaksi</a>
<a href="backup_restore.php">Backup / Restore</a>

<br><br>

<a href="../logout.php">Keluar</a> <!-- Tombol logout -->

</div>

<!-- MAIN -->
<div class="main">

<div class="header">
<h3>Laporan</h3> <!-- Judul halaman -->
</div>

<div class="cards">

<!-- CARD LAPORAN PENJUALAN -->
<a href="laporan_penjualan.php" class="card">
 <h3>Laporan Penjualan</h3>
 <div class="icon">
  <i class="fas fa-chart-line"></i> <!-- Ikon grafik -->
 </div>
</a>

<!-- CARD LAPORAN TRANSAKSI -->
<a href="laporan_transaksi.php" class="card">
 <h3>Laporan Transaksi</h3>
 <div class="icon">
  <i class="fas fa-file-invoice"></i> <!-- Ikon invoice -->
 </div>
</a>

<!-- CARD LAPORAN STOK -->
<a href="laporan_stok.php" class="card">
 <h3>Laporan Stok</h3>
 <div class="icon">
  <i class="fas fa-box"></i> <!-- Ikon kotak / stok -->
 </div>
</a>

</div>

</div>
</div>

</body>
</html>