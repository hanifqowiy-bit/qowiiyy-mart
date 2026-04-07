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

/* ================= TAMBAH ================= */
if(isset($_POST['tambah'])){

 $nama       = $_POST['nama'];
 $harga      = $_POST['harga'];
 $stok       = $_POST['stok'];
 $deskripsi  = $_POST['deskripsi'];

 $foto = $_FILES['foto']['name'];
 $tmp  = $_FILES['foto']['tmp_name'];

 move_uploaded_file($tmp,"../assets/".$foto);

 mysqli_query($koneksi,"
  INSERT INTO products(name,price,stock,photo,description)
  VALUES('$nama','$harga','$stok','$foto','$deskripsi')
 ");
}


/* ================= UPDATE ================= */
if(isset($_POST['update'])){

 $id      = $_POST['id'];
 $nama    = $_POST['nama'];
 $harga   = $_POST['harga'];
 $sisa    = $_POST['stok'];
 $terjual = $_POST['terjual'];

 // hitung ulang stok asli
 $stok_baru = $sisa + $terjual;

 if($_FILES['foto']['name']!=""){

  $foto = $_FILES['foto']['name'];
  $tmp  = $_FILES['foto']['tmp_name'];

  move_uploaded_file($tmp,"../assets/".$foto);

  mysqli_query($koneksi,"
   UPDATE products SET
   name='$nama',
   price='$harga',
   stock='$stok_baru',
   photo='$foto'
   WHERE id='$id'
  ");

 }else{

  mysqli_query($koneksi,"
   UPDATE products SET
   name='$nama',
   price='$harga',
   stock='$stok_baru'
   WHERE id='$id'
  ");
 }
}


/* ================= HAPUS ================= */
if(isset($_GET['hapus'])){

 mysqli_query($koneksi,"
  DELETE FROM products WHERE id='$_GET[hapus]'
 ");

 header("Location: produk.php");
 exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Data Produk</title>

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
.main{flex:1;padding:20px;background:white}

/* HEADER */
.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:15px;
}

/* BUTTON */
.btn{
 padding:6px 12px;
 border:none;
 border-radius:4px;
 cursor:pointer;
}

.btn-add{background:#2196f3;color:white}
.btn-edit{background:#0992C2;color:white}
.btn-del{background:#f44336;color:white}

/* TABLE */
table{width:100%;border-collapse:collapse}

th,td{
 border:1px solid #ddd;
 padding:8px;
 text-align:center;
}

th{background:#2196f3;color:white}

img{
 width:60px;
 height:60px;
 object-fit:cover;
 border-radius:5px;
}

/* MODAL */
.modal{
 display:none;
 position:fixed;
 top:0;left:0;
 width:100%;height:100%;
 background:rgba(0,0,0,.5);
 justify-content:center;
 align-items:center;
}

.modal-box{
 background:white;
 width:350px;
 padding:20px;
 border-radius:8px;
}

.modal-box h3{text-align:center;margin-bottom:10px}

.modal-box input,
.modal-box textarea{
 width:100%;
 padding:8px;
 margin:5px 0;
 border:1px solid #ccc;
 border-radius:5px;
}

.modal-box textarea{
 resize:none;
 height:80px;
}

.close{
 float:right;
 cursor:pointer;
 color:red;
}

</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">

<h2>KOWI-MART</h2>

<a href="dashboard.php">Dashboard</a>
<a href="transaksi.php">Transaksi</a>
<a href="produk.php" class="active">Data Produk</a>
<a href="laporan_petugas.php">Laporan</a>

<br><br>

<a href="logout.php">Keluar</a>

</div>

<!-- MAIN -->
<div class="main">

<div class="header">
<h3>Data Produk</h3>
</div>

<!-- TOMBOL TAMBAH PRODUK -->
<button class="btn btn-add" onclick="openTambah()" style="margin-bottom: 15px;">
    + Tambah Produk
</button>

<table>

<tr>
 <th>No</th>
 <th>Nama</th>
 <th>Harga</th>
 <th>Sisa Stok</th>
 <th>Foto</th>
 <th>Aksi</th>
</tr>

<?php

$no=1;

$q = mysqli_query($koneksi,"
SELECT 
 p.id,
 p.name,
 p.price,
 p.stock,
 p.photo,
 IFNULL(SUM(t.jumlah),0) AS terjual,
 (p.stock - IFNULL(SUM(t.jumlah),0)) AS sisa_stok
FROM products p
LEFT JOIN transaksi t 
 ON p.name = t.nama_produk
GROUP BY p.id
");

while($p=mysqli_fetch_assoc($q)){
?>

<tr>
 <td><?= $no++ ?></td>
 <td><?= $p['name'] ?></td>
 <td>Rp <?= number_format($p['price']) ?></td>
 <td><?= $p['sisa_stok'] ?></td>

 <td>
    <img src="../assets/<?= $p['photo'] ?>">
 </td>

 <td>

 <button class="btn btn-edit"
 onclick="openEdit(
 '<?= $p['id'] ?>',
 '<?= $p['name'] ?>',
 '<?= $p['price'] ?>',
 '<?= $p['sisa_stok'] ?>',
 '<?= $p['terjual'] ?>'
 )">
 Edit
 </button>

 <a href="?hapus=<?= $p['id'] ?>"
 onclick="return confirm('Hapus produk?')"
 class="btn btn-del">
 Hapus
 </a>

 </td>
</tr>

<?php } ?>

</table>

</div>
</div>


<!-- MODAL TAMBAH -->
<div class="modal" id="modalTambah">

<div class="modal-box">

<span class="close" onclick="closeModal()">&times;</span>

<h3>Tambah Produk</h3>

<form method="POST" enctype="multipart/form-data">

<input name="nama" placeholder="Nama Produk" required>
<input name="harga" type="number" placeholder="Harga" required>
<input name="stok" type="number" placeholder="Stok Awal" required>

<textarea name="deskripsi" placeholder="Deskripsi Produk" required></textarea>

<input type="file" name="foto" required>

<br><br>

<button name="tambah" class="btn btn-add">Simpan</button>

</form>

</div>
</div>


<!-- MODAL EDIT -->
<div class="modal" id="modalEdit">

<div class="modal-box">

<span class="close" onclick="closeModal()">&times;</span>

<h3>Edit Produk</h3>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" id="eid">
<input type="hidden" name="terjual" id="eterjual">

<input name="nama" id="enama" required>
<input name="harga" type="number" id="eharga" required>
<input name="stok" type="number" id="estok" required>

<input type="file" name="foto">

<small>Kosongkan jika tidak ganti foto</small>

<br><br>

<button name="update" class="btn btn-edit">Update</button>

</form>

</div>
</div>


<script>

function openTambah(){
 document.getElementById('modalTambah').style.display='flex';
}

function openEdit(id,nama,harga,stok,terjual){

 document.getElementById('eid').value=id;
 document.getElementById('enama').value=nama;
 document.getElementById('eharga').value=harga;
 document.getElementById('estok').value=stok;
 document.getElementById('eterjual').value=terjual;

 document.getElementById('modalEdit').style.display='flex';
}

function closeModal(){
 document.getElementById('modalTambah').style.display='none';
 document.getElementById('modalEdit').style.display='none';
}

</script>

</body>
</html>