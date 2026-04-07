<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$nama   = $_POST['nama'];
$telp   = $_POST['telp'];
$alamat = $_POST['alamat'];
$tanggal = date("Y-m-d");

$bukti = "";

if(isset($_FILES['bukti']) && $_FILES['bukti']['name'] != ""){

    $folder = "../assets/upload/";

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $nama_file = time() . "_" . $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];

    move_uploaded_file($tmp, $folder.$nama_file);

    $bukti = $nama_file;
}

if(isset($_POST['id_produk'])){

    $id_produk = $_POST['id_produk'];

    $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id_produk'");
    $p = mysqli_fetch_assoc($q);

    $nama_produk = $p['name'];
    $harga = $p['price'];
    $jumlah = 1;

    mysqli_query($koneksi,"
        INSERT INTO transaksi
        (user_id,nama_produk,harga,jumlah,alamat,bukti,tanggal)
        VALUES
        ('$user_id','$nama_produk','$harga','$jumlah','$alamat','$bukti','$tanggal')
    ");

}
else if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){

    foreach($_SESSION['cart'] as $id=>$qty){

        $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id'");
        $p = mysqli_fetch_assoc($q);

        $nama_produk = $p['name'];
        $harga = $p['price'];
        $jumlah = $qty;

        mysqli_query($koneksi,"
            INSERT INTO transaksi
            (user_id,nama_produk,harga,jumlah,alamat,bukti,tanggal)
            VALUES
            ('$user_id','$nama_produk','$harga','$jumlah','$alamat','$bukti','$tanggal')
        ");
    }

    unset($_SESSION['cart']);
}

header("Location: proses_sukses.php");
exit();