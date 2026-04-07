<?php
include "config.php"; // Menghubungkan ke file konfigurasi database

$id = $_GET['id']; // Mengambil ID transaksi dari URL

/* Ambil data transaksi + user */
$q = mysqli_query($koneksi, "
    SELECT transaksi.*, users.username
    FROM transaksi
    JOIN users ON transaksi.user_id = users.id
    WHERE transaksi.id = '$id'
");
// Eksekusi query di atas

$t = mysqli_fetch_assoc($q); // Mengambil hasil query sebagai array asosiatif
$total = $t['harga'] * $t['jumlah']; // Menghitung total harga
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Konfirmasi Pembayaran</title>

<style>
    /* Reset dan style dasar */
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:Arial, sans-serif;
    }

    /* Background halaman */
    body{
        background:#f0f4f8;
        padding:40px;
        display:flex;
        justify-content:center;
    }

    /* Container utama */
    .container{
        background:white;
        width:500px;
        padding:25px;
        border-radius:10px;
        box-shadow:0 5px 15px rgba(0,0,0,0.15);
    }

    /* Judul */
    h2{
        text-align:center;
        margin-bottom:20px;
        color:#2196f3;
        font-size:22px;
    }

    /* Tabel detail transaksi */
    table{
        width:100%;
        border-collapse:collapse;
        margin-bottom:20px;
    }

    td{
        padding:10px;
        border-bottom:1px solid #eee;
        font-size:15px;
    }

    td:first-child{
        font-weight:bold;
        width:40%;
        color:#333;
    }

    /* Tombol */
    .btn{
        display:inline-block;
        padding:10px 15px;
        background:#2196f3;
        color:white;
        text-decoration:none;
        border-radius:6px;
        margin-right:5px;
        transition:0.2s;
    }

    .btn:hover{
        background:#0d7adf;
    }

    /* Tombol tolak */
    .reject{
        background:#e53935;
    }
    .reject:hover{
        background:#c62828;
    }

    /* Center text */
    .center{
        text-align:center;
    }
</style>

</head>
<body>

<div class="container">

<h2>Detail Konfirmasi Pembayaran</h2> <!-- Judul halaman -->

<!-- Tabel informasi transaksi -->
<table>
<tr><td>ID Transaksi</td><td><?= $t['id'] ?></td></tr>
<tr><td>Username</td><td><?= $t['username'] ?></td></tr>
<tr><td>Jumlah</td><td><?= $t['jumlah'] ?></td></tr>
<tr><td>Harga</td><td>Rp <?= number_format($t['harga']) ?></td></tr>
<tr><td>Total</td><td>Rp <?= number_format($total) ?></td></tr>
<tr><td>Metode</td><td><?= !empty($t['bukti']) ? "TRANSFER" : "COD" ?></td></tr>

<!-- Bukti pembayaran -->
<tr>
<td>Bukti</td>
<td>
<?php if(!empty($t['bukti'])){ ?>
   <!-- Jika ada bukti transfer -->
   <a class="btn" href="../assets/upload/<?= $t['bukti'] ?>" target="_blank">Lihat Bukti</a>
<?php }else{ ?>
   Tidak ada (COD)
<?php } ?>
</td>
</tr>

<tr><td>Tanggal</td><td><?= $t['tanggal'] ?></td></tr>
<tr><td>Status</td><td><?= $t['status'] ?></td></tr>
</table>

<div class="center">

<!-- Tombol konfirmasi -->
<a href="update_status.php?id=<?= $t['id'] ?>&aksi=konfirmasi" class="btn">
    Konfirmasi
</a>

<!-- Tombol tolak -->
<a href="update_status.php?id=<?= $t['id'] ?>&aksi=tolak" class="btn reject">
    Tolak
</a>

<br><br>

<!-- Tombol kembali -->
<a href="transaksi.php" class="btn">Kembali</a>

</div>

</div>

</body>
</html>