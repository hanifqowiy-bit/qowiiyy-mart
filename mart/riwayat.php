<?php
session_start(); // Mulai session

include "config.php"; // Koneksi database

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])){
    header("Location:index.php"); 
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil ID user

// Ambil semua transaksi user
$data = mysqli_query($koneksi,"
SELECT * FROM transaksi
WHERE user_id='$user_id'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Riwayat Transaksi</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f2f6f9;
}

.header{
    background:#1e9bd7;
    color:white;
    padding:15px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
}

.container{
    width:80%;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#1e9bd7;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    border-bottom:1px solid #ccc;
    text-align:center;
}

.back{
    display:inline-block;
    margin-top:20px;
    font-size:22px;
    text-decoration:none;
    color:#1e9bd7;
}

.back:hover{
    color:#157db3;
}

.empty{
    text-align:center;
    padding:20px;
    color:#777;
}
</style>
</head>

<body>

<div class="header">
KOWI-MART
</div>

<div class="container">

<h2>Riwayat Transaksi</h2>

<br>

<table>

<tr>
    <th>Nama Produk</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Status</th> <!-- Kolom baru -->
</tr>

<?php if(mysqli_num_rows($data) > 0): ?> 

<?php while($t = mysqli_fetch_assoc($data)): ?> 

<tr>
    <td><?= $t['nama_produk']; ?></td>
    <td>Rp <?= number_format($t['harga']); ?></td>
    <td><?= $t['jumlah']; ?></td>
    <td>
        <?= $t['status']; ?> 
    </td>
</tr>

<?php endwhile; ?>

<?php else: ?> 

<tr>
    <td colspan="4" class="empty">
        Belum ada transaksi
    </td>
</tr>

<?php endif; ?>

</table>

<a href="pemesanan.php" class="back">← Kembali</a>

</div>

</body>
</html>