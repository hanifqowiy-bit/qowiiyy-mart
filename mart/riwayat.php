<?php
session_start(); // Mulai session
include "config.php"; // Koneksi database

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil ID user

// Ambil semua transaksi user beserta status refund jika ada
$data = mysqli_query($koneksi,"
SELECT 
    t.*,
    r.status AS refund_status
FROM transaksi t
LEFT JOIN refund r ON t.id = r.transaksi_id
WHERE t.user_id='$user_id'
ORDER BY t.id DESC
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
.refund-btn{
    display:inline-block;
    padding:8px 14px;
    background:#ff9800;
    color:white;
    text-decoration:none;
    border-radius:6px;
    font-size:14px;
}
.refund-btn:hover{
    background:#e68900;
}
.refund-status{
    display:inline-block;
    padding:6px 12px;
    border-radius:6px;
    font-size:14px;
    color:white;
}
.refund-diajukan{background:#ff9800;}
.refund-disetujui{background:green;}
.refund-ditolak{background:red;}
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
    <th>Tanggal</th>
    <th>Status</th>
    <th>Refund</th>
</tr>

<?php if(mysqli_num_rows($data) > 0): ?>
    <?php while($t = mysqli_fetch_assoc($data)): ?>
<tr>
    <td><?= $t['nama_produk']; ?></td>
    <td>Rp <?= number_format($t['harga']); ?></td>
    <td><?= $t['jumlah']; ?></td>
    <td><?= date('d-m-Y', strtotime($t['tanggal'])); ?></td>
    <td><?= $t['status']; ?></td>
    <td>
        <?php
        if($t['refund_status'] == 'diajukan'){
            echo '<span class="refund-status refund-diajukan">DIAJUKAN</span>';
        } elseif($t['refund_status'] == 'disetujui'){
            echo '<span class="refund-status refund-disetujui">DISETUJUI</span>';
        } elseif($t['refund_status'] == 'ditolak'){
            echo '<span class="refund-status refund-ditolak">DITOLAK</span>';
        } else {
            echo '<a href="refund.php?riwayat_id='.$t['id'].'" class="refund-btn">Ajukan Refund</a>';
        }
        ?>
    </td>
</tr>
    <?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="empty">
        Belum ada transaksi
    </td>
</tr>
<?php endif; ?>
</table>

<a href="pemesanan.php" class="back">← Kembali</a>

</div>

</body>
</html>