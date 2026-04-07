<?php
session_start();
include "../config.php";

/* CEK ROLE ADMIN */
if($_SESSION['role']!='admin'){
 header("Location: dashboard.php");
}

/* TAMBAH USER PETUGAS */
if(isset($_POST['tambah'])){
 $u=$_POST['username']; // ambil username
 $e=$_POST['email'];    // ambil email
 $p=password_hash($_POST['password'],PASSWORD_DEFAULT); // hash password

 // insert user petugas baru
 mysqli_query($koneksi,
 "INSERT INTO users(username,email,password,role)
  VALUES('$u','$e','$p','petugas')");
}

/* UPDATE DATA USER */
if(isset($_POST['update'])){
 $id=$_POST['id'];         // ambil id
 $u=$_POST['username'];    // ambil username baru
 $e=$_POST['email'];       // ambil email baru

 // jika password diisi, update password
 if($_POST['password']!=""){
  $p=password_hash($_POST['password'],PASSWORD_DEFAULT); // hash password

  mysqli_query($koneksi,
  "UPDATE users SET
   username='$u',
   email='$e',
   password='$p'
   WHERE id='$id'");
 }else{
  // jika password kosong, tidak diubah
  mysqli_query($koneksi,
  "UPDATE users SET
   username='$u',
   email='$e'
   WHERE id='$id'");
 }
}

/* HAPUS USER PETUGAS */
if(isset($_GET['hapus'])){
 // hanya boleh hapus user role petugas
 mysqli_query($koneksi,
"DELETE FROM users 
 WHERE id='$_GET[hapus]' 
 AND role='petugas'");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manajemen User</title>

<style>

/* RESET */
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}

/* LAYOUT */
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
.main{flex:1;background:white;padding:20px}

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

.modal-box input{
 width:100%;
 padding:8px;
 margin:5px 0;
 border:1px solid #ccc;
 border-radius:5px;
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

<!-- Navigasi sidebar -->
<a href="dashboard.php">Dashboard</a>
<a href="manajemen_user.php" class="active">Manajemen User</a>
<a href="laporan.php">Laporan</a>
<a href="manajemen_produk.php">Manajemen Produk</a>
<a href="manajemen_transaksi.php">Manajemen Transaksi</a>
<a href="backup_restore.php">Backup / Restore</a>

<br><br>

<a href="../logout.php">Keluar</a>

</div>

<!-- MAIN CONTENT -->
<div class="main">

<div class="header">
<h3>Manajemen User</h3>
</div>

<!-- Tombol buka modal tambah -->
<button class="btn btn-add" onclick="openTambah()">
+ Tambah Petugas
</button>

<br><br>

<table>

<tr>
<th>No</th>
<th>Username</th>
<th>Email</th>
<th>Role</th>
<th>Aksi</th>
</tr>

<?php
$no=1;
// Ambil semua user petugas
$q=mysqli_query($koneksi,
"SELECT * FROM users WHERE role='petugas'");

while($u=mysqli_fetch_assoc($q)){
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $u['username'] ?></td>
<td><?= $u['email'] ?></td>
<td><?= $u['role'] ?></td>

<td>

<!-- Tombol edit membuka modal edit -->
<button class="btn btn-edit"
onclick="openEdit(
'<?= $u['id'] ?>',
'<?= $u['username'] ?>',
'<?= $u['email'] ?>'
)">
Edit</button>

<!-- Link hapus -->
<a href="?hapus=<?= $u['id'] ?>"
onclick="return confirm('Hapus data?')"
class="btn btn-del">Hapus</a>

</td>
</tr>

<?php } ?>

</table>

</div>
</div>

<!-- MODAL TAMBAH PETUGAS -->
<div class="modal" id="modalTambah">

<div class="modal-box">

<span class="close" onclick="closeModal()">&times;</span>

<h3>Tambah Petugas</h3>

<form method="POST">

<input name="username" placeholder="Username" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>

<button name="tambah" class="btn btn-add">Simpan</button>

</form>

</div>
</div>

<!-- MODAL EDIT USER -->
<div class="modal" id="modalEdit">

<div class="modal-box">

<span class="close" onclick="closeModal()">&times;</span>

<h3>Edit User</h3>

<form method="POST">

<input type="hidden" name="id" id="eid"> <!-- simpan id -->

<input name="username" id="euser" required>
<input name="email" id="eemail" required>
<input name="password" type="password"
placeholder="Kosongkan jika tidak diubah">

<button name="update" class="btn btn-edit">Update</button>

</form>

</div>
</div>

<script>

/* Buka modal tambah */
function openTambah(){
 document.getElementById('modalTambah').style.display='flex';
}

/* Buka modal edit + isi data */
function openEdit(id,user,email){

 document.getElementById('eid').value=id;
 document.getElementById('euser').value=user;
 document.getElementById('eemail').value=email;

 document.getElementById('modalEdit').style.display='flex';
}

/* Tutup modal */
function closeModal(){
 document.getElementById('modalTambah').style.display='none';
 document.getElementById('modalEdit').style.display='none';
}

</script>

</body>
</html>