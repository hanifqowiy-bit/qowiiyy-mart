<?php
session_start(); // Mulai sesi untuk cek login

// Jika user belum login, arahkan ke halaman login
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pesanan Diproses</title>

<style>
/* Mengatur tampilan dasar halaman */
body{
    margin:0;
    font-family:Arial;
    background:#f5f5f5;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* Kotak konten utama */
.box{
    background:white;
    padding:30px 40px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
}

/* Icon centang hijau */
.check{
    font-size:60px;
    color:green;
    margin-bottom:15px;
}

/* Tombol kembali ke dashboard */
.btn{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    background:#2196f3;
    color:white;
    padding:10px 25px;
    border-radius:6px;
}
</style>

</head>

<body>

<div class="box"> <!-- Container utama -->

<div class="check">✔</div> <!-- Icon centang -->

<h2>Barang akan diproses</h2> <!-- Pesan status -->

<!-- Tombol untuk kembali ke dashboard -->
<a href="dashboard.php" class="btn">
Kembali ke Dashboard
</a>

</div>

</body>
</html>