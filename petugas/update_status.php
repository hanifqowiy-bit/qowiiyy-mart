<?php
include "config.php";

$id   = $_GET['id'];
$aksi = $_GET['aksi'];

if($aksi == "konfirmasi"){
    $status = "berhasil";
}else if($aksi == "tolak"){
    $status = "ditolak";
}else{
    $status = "pending";
}

mysqli_query($koneksi,"
    UPDATE transaksi 
    SET status='$status'
    WHERE id='$id'
");

header("Location: transaksi.php");
exit();
?>