<?php
session_start();
include "config.php";

// Cek login petugas
if(!isset($_SESSION['petugas_login']) || $_SESSION['petugas_role'] != 'petugas'){
    header("Location: login.php");
    exit();
}

// Proses ACC refund
if(isset($_POST['status_action'])){
    $refund_id = $_POST['refund_id'];
    $action = $_POST['status_action'];

    if($action == 'setuju'){
        mysqli_query($koneksi, "UPDATE refund SET status='disetujui' WHERE id='$refund_id'");
        // Tambahkan logika pengembalian dana di sini jika perlu
    } elseif($action == 'tolak'){
        mysqli_query($koneksi, "UPDATE refund SET status='ditolak' WHERE id='$refund_id'");
    }

    // Redirect pakai PHP agar tidak delay atau not found
    header("Location: data_refund.php");
    exit();
}

// Ambil data refund
$data = mysqli_query($koneksi, "
    SELECT refund.*, users.username, transaksi.nama_produk, transaksi.jumlah, transaksi.harga
    FROM refund
    JOIN users ON refund.user_id = users.id
    JOIN transaksi ON refund.transaksi_id = transaksi.id
    ORDER BY refund.tanggal DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Data Refund</title>
<style>
.back-btn {
    display: inline-block;
    padding: 8px 15px;
    background: #2196f3;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: 15px;
}
table {border-collapse: collapse; width: 100%;}
th, td {border: 1px solid #ccc; padding: 10px; text-align: center;}
th {background: #1e9bd7; color: white;}
button {padding:5px 10px; margin:2px; border:none; border-radius:4px; cursor:pointer;}
.setuju {background:#28a745; color:white;}
.tolak {background:#dc3545; color:white;}
form {display:inline; margin:0;}
</style>
</head>
<body>

<h2>Data Refund Pengguna</h2>
<a href="transaksi.php" class="back-btn">← Kembali</a>

<table>
<tr>
    <th>No</th>
    <th>User</th>
    <th>No Telp / Relasi</th>
    <th>Produk</th>
    <th>Jumlah</th>
    <th>Total Refund</th>
    <th>Bank / Ewallet</th>
    <th>Alasan</th>
    <th>Status</th>
</tr>

<?php 
$no = 1;
while($r = mysqli_fetch_assoc($data)){
    $total_refund = $r['harga'] * $r['jumlah'];
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $r['username']; ?></td>
    <td><?= $r['telp']; ?></td>
    <td><?= $r['nama_produk']; ?></td>
    <td><?= $r['jumlah']; ?></td>
    <td>Rp <?= number_format($total_refund); ?></td>
    <td><?= $r['bank_ewallet']; ?></td>
    <td><?= $r['alasan']; ?></td>
    <td>
        <?php if($r['status']=='diajukan'){ ?>
            <form method="POST" action="">
                <input type="hidden" name="refund_id" value="<?= $r['id']; ?>">
                <button type="submit" name="status_action" value="setuju" class="setuju">Setuju</button>
            </form>
            <form method="POST" action="">
                <input type="hidden" name="refund_id" value="<?= $r['id']; ?>">
                <button type="submit" name="status_action" value="tolak" class="tolak">Tolak</button>
            </form>
        <?php } else {
            echo ucfirst($r['status']);
        } ?>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>