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

/* AMBIL NAMA PETUGAS */
$nama = $_SESSION['petugas_username'] ?? 'Petugas';

/* HITUNG DATA */
$produk    = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM products"));
$transaksi = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM transaksi"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Petugas</title>

<!-- LUCIDE ICON -->
<script src="https://unpkg.com/lucide@latest"></script>

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
.main{
 flex:1;
 padding:20px;
 background:white;
}

/* HEADER */
.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:20px;
}

/* BOX CARD */
.box{
 display:flex;
 gap:20px;
 flex-wrap:wrap;
}

/* CARD */
.card{
 background:white;
 width:220px;
 padding:20px;
 border-radius:10px;
 text-align:center;
 box-shadow:0 2px 5px rgba(0,0,0,0.2);
 display:flex;
 flex-direction:column;
 align-items:center;
 gap:8px;
}

.card svg{width:32px;height:32px;color:#2196f3;}

.card h3{
 margin:0;
 color:#555;
}

.card p{
 font-size:32px;
 margin-top:10px;
 color:#2196f3;
 font-weight:bold;
}

/* ANIMATIONS */
.card{
 transition:0.3s ease;
 animation: fadeUp 0.6s ease;
}

.card:hover{
 transform:translateY(-5px);
 box-shadow:0 6px 15px rgba(0,0,0,0.2);
}

.card p{
 animation: pop 0.6s ease;
}

/* KEYFRAMES */
@keyframes fadeUp{
 from{opacity:0;transform:translateY(20px);}
 to{opacity:1;transform:translateY(0);}
}

@keyframes pop{
 0%{transform:scale(0.8);}
 70%{transform:scale(1.1);}
 100%{transform:scale(1);}
}

</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">
<h2>KOWI-MART</h2>

<a href="dashboard.php" class="active">Dashboard</a>
<a href="transaksi.php">Transaksi</a>
<a href="produk.php">Data Produk</a>
<a href="laporan_petugas.php">Laporan</a>

<br><br>

<a href="logout.php">Keluar</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
<h3>Dashboard Petugas</h3>
</div>

<h2>Selamat Datang, <?= $nama ?> 👋</h2>

<br>

<div class="box">

    <div class="card">
        <h3>Total Produk</h3>
        <div style="display:flex;align-items:center;gap:8px;">
            <i data-lucide="package"></i>
            <p><?= $produk ?></p>
        </div>
    </div>

    <div class="card">
        <h3>Total Transaksi</h3>
        <div style="display:flex;align-items:center;gap:8px;">
            <i data-lucide="shopping-cart"></i>
            <p><?= $transaksi ?></p>
        </div>
    </div>

</div>

<!-- GRAFIK -->
<div style="
    margin-top:30px;
    background:white;
    padding:15px;
    border-radius:10px;
    max-width:600px;
    box-shadow:0 2px 5px rgba(0,0,0,0.2);
">
    <h3 style="margin-bottom:15px; color:#333;">Statistik Data</h3>

    <canvas id="barChart" style="width:100%; max-height:220px;"></canvas>

</div>

</div> <!-- END MAIN -->
</div> <!-- END WRAPPER -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
lucide.createIcons();

const ctx = document.getElementById('barChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Produk', 'Transaksi'],
        datasets: [{
            label: 'Jumlah Data',
            data: [<?= $produk ?>, <?= $transaksi ?>],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>