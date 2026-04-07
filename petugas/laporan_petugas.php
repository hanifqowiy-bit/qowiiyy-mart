<?php
session_start(); // Memulai session untuk mengecek login

include "config.php"; // Menghubungkan ke database

/* ============================
   CEK LOGIN PETUGAS (YANG BARU)
   ============================ */
if(!isset($_SESSION['petugas_login']) || $_SESSION['petugas_login'] !== true){
    // Jika belum login sebagai petugas, kembalikan ke halaman login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan</title>

<!-- ICON: Font Awesome untuk ikon menu dan card -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* RESET: Menghilangkan margin/padding default */
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}

/* LAYOUT UMUM */
body{background:#f2f4f7}

.wrapper{
    display:flex;
    height:100vh;
}

/* ======================
   SIDEBAR NAVIGASI
   ====================== */
.sidebar{
 width:230px;
 background:#2196f3;
 color:white;
 padding:20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

.sidebar a{
 display:block;
 color:white;
 text-decoration:none;
 padding:10px;
 border-radius:5px;
}

.sidebar a:hover,
.active{
 background:rgba(255,255,255,.2); /* Efek hover */
}

/* ======================
   AREA UTAMA (MAIN)
   ====================== */
.main{
    flex:1;
    padding:20px;
    background:white;
}

/* HEADER DI MAIN */
.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:20px;
}

/* ======================
   CARD LAPORAN
   ====================== */
.cards{
 display:flex;
 gap:20px;
 margin-top:20px;
 flex-wrap:wrap;
}

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

.card:hover{
 transform:scale(1.05); /* Animasi zoom saat hover */
}

.card h3{
 margin-bottom:20px;
}

.icon{
 font-size:55px;
 text-align:right;
 opacity:.9;
}

.card i{
 color:white; /* Warna ikon */
}

</style>
</head>

<body>

<div class="wrapper">

<!-- ======================
     SIDEBAR MENU KIRI
     ====================== -->
<div class="sidebar">

<h2>KOWI-MART</h2> <!-- Judul sidebar -->

<!-- Menu navigasi petugas -->
<a href="dashboard.php">Dashboard</a>
<a href="transaksi.php">Transaksi</a>
<a href="produk.php">Data Produk</a>
<a href="laporan_petugas.php" class="active">Laporan</a>

<br><br>

<a href="logout.php">Keluar</a> <!-- Tombol logout -->

</div>

<!-- ======================
     BAGIAN UTAMA KANAN
     ====================== -->
<div class="main">

<div class="header">
<h3>Laporan</h3> <!-- Judul halaman laporan -->
</div>

<div class="cards">

<!-- CARD PENJUALAN -->
<a href="penjualan_petugas.php" class="card">
 <h3>Laporan Penjualan</h3>
 <div class="icon">
  <i class="fas fa-chart-line"></i> <!-- Ikon grafik -->
 </div>
</a>

<!-- CARD TRANSAKSI -->
<a href="transaksi_petugas.php" class="card">
 <h3>Laporan Transaksi</h3>
 <div class="icon">
  <i class="fas fa-file-invoice"></i> <!-- Ikon dokumen -->
 </div>
</a>

<!-- CARD STOK -->
<a href="stok_petugas.php" class="card">
 <h3>Laporan Stok</h3>
 <div class="icon">
  <i class="fas fa-box"></i> <!-- Ikon box/stok -->
 </div>
</a>

</div>

</div>
</div>

</body>
</html>