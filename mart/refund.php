<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$transaksi_id = $_GET['riwayat_id'];
$user_id = $_SESSION['user_id'];

/* AMBIL DATA TRANSAKSI */
$qTrans = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id='$transaksi_id' AND user_id='$user_id'");
$transaksi = mysqli_fetch_assoc($qTrans);

if (!$transaksi) {
    echo "Transaksi tidak ditemukan!";
    exit();
}

/* HITUNG JUMLAH REFUND */
$jumlah_refund = $transaksi['harga'] * $transaksi['jumlah'];

/* DATA DEFAULT BANK/REKENING */
$bank_default = $transaksi['bank'] ?? '';
$no_rek_default = $transaksi['no_rek'] ?? '';
$nama_pemilik_default = $transaksi['nama_rek'] ?? '';

if (isset($_POST['kirim'])) {
    $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan']);
    $bank_selected = mysqli_real_escape_string($koneksi, $_POST['bank']);
    $no_rek_input = mysqli_real_escape_string($koneksi, $_POST['no_rek']);
    $nama_input = mysqli_real_escape_string($koneksi, $_POST['nama_pemilik']);

    mysqli_query($koneksi, "
        INSERT INTO refund (transaksi_id, user_id, telp, bank_ewallet, alasan, status)
        VALUES ('$transaksi_id', '$user_id', '$no_rek_input', '$bank_selected', '$alasan', 'diajukan')
    ");

    echo "<script>
        alert('Refund berhasil diajukan');
        window.location='riwayat.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ajukan Refund</title>
<style>
body{
    font-family: Arial, sans-serif;
    background:#f2f6f9;
    margin:0;
    padding:0;
}
.container{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}
.box{
    background:white;
    padding:25px 30px;
    border-radius:10px;
    width:400px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
h2{
    text-align:center;
    margin-bottom:20px;
    color:#333;
}
p{
    margin:8px 0;
    font-size:14px;
}
label{
    display:block;
    margin:8px 0 4px;
    font-weight:bold;
}
input, select, textarea{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    margin-bottom:15px;
    font-size:14px;
    box-sizing:border-box;
}
textarea{
    resize:none;
}
button{
    width:100%;
    padding:10px;
    background:#e53935;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}
button:hover{
    opacity:0.9;
}
.refund-info{
    background:#fff3cd;
    border:1px solid #ffc107;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}
.back{
    display:block;
    margin-top:15px;
    text-align:center;
    text-decoration:none;
    color:#1e9bd7;
    font-size:14px;
}
</style>
</head>
<body>

<div class="container">
<div class="box">

<h2>Ajukan Refund</h2>

<div class="refund-info">
<p><b>Jumlah Refund:</b> Rp <?= number_format($jumlah_refund) ?></p>
</div>

<form method="POST">
    <label>Bank / E-Wallet</label>
    <select name="bank" required>
        <option value="">-- Pilih Bank / E-Wallet --</option>
        <option value="BCA" <?= $bank_default=='BCA'?'selected':'' ?>>BCA</option>
        <option value="Mandiri" <?= $bank_default=='Mandiri'?'selected':'' ?>>Mandiri</option>
        <option value="BRI" <?= $bank_default=='BRI'?'selected':'' ?>>BRI</option>
        <option value="DANA" <?= $bank_default=='DANA'?'selected':'' ?>>DANA</option>
        <option value="OVO" <?= $bank_default=='OVO'?'selected':'' ?>>OVO</option>
        <option value="GoPay" <?= $bank_default=='GoPay'?'selected':'' ?>>GoPay</option>
    </select>

    <label>No Rekening / Nomor HP</label>
    <input type="text" name="no_rek" value="<?= htmlspecialchars($no_rek_default) ?>" required placeholder="Masukkan No Rekening / Nomor HP">

    <label>Nama Pemilik Rekening / Akun</label>
    <input type="text" name="nama_pemilik" value="<?= htmlspecialchars($nama_pemilik_default) ?>" required placeholder="Masukkan Nama Pemilik">

    <label>Alasan Refund</label>
    <textarea name="alasan" required placeholder="Masukkan alasan refund"></textarea>

    <button type="submit" name="kirim">Kirim Refund</button>
</form>

<a href="riwayat.php" class="back">← Kembali ke Riwayat</a>

</div>
</div>

</body>
</html>