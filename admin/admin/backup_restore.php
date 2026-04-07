<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
include "../config.php";

/* Proteksi Admin */
if($_SESSION['role']!='admin'){
    header("Location: ../login.php");
    exit;
}

/* Folder Backup */
$backup_dir = "../../backup/";
if(!is_dir($backup_dir)){
    mkdir($backup_dir,0777,true);
}

/* Database Config */
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kowi_mart";


/* ================= BACKUP ================= */
if(isset($_POST['backup'])){

    $date = date("Y-m-d_H-i-s");
    $filename = "backup_".$date.".sql";
    $filepath = $backup_dir.$filename;

    $koneksi = mysqli_connect($host,$user,$pass,$db);

    $sql = "-- DATABASE BACKUP $db \n";
    $sql .= "-- ".date("Y-m-d H:i:s")."\n\n";

    $tables = [];

    /* Ambil semua tabel */
    $q = mysqli_query($koneksi,"SHOW TABLES");

    while($row = mysqli_fetch_row($q)){
        $tables[] = $row[0];
    }

    foreach($tables as $table){

        /* Struktur Tabel */
        $q2 = mysqli_query($koneksi,"SHOW CREATE TABLE `$table`");
        $row2 = mysqli_fetch_row($q2);

        $sql .= "\nDROP TABLE IF EXISTS `$table`;\n";
        $sql .= $row2[1].";\n\n";

        /* Data Tabel */
        $q3 = mysqli_query($koneksi,"SELECT * FROM `$table`");

        while($row3 = mysqli_fetch_assoc($q3)){

            $sql .= "INSERT INTO `$table` VALUES(";

            $data = [];

            foreach($row3 as $val){

                if($val===NULL){
                    $data[]="NULL";
                }else{
                    $data[]="'".mysqli_real_escape_string($koneksi,$val)."'";
                }

            }

            $sql .= implode(",",$data).");\n";

        }

        $sql.="\n\n";
    }

    /* Simpan ke file */
    file_put_contents($filepath,$sql);

    header("Location: backup_restore.php?status=backup_success");
    exit;
}



/* ================= RESTORE (FIX FINAL + FOREIGN KEY) ================= */
if(isset($_POST['restore'])){

    if(!empty($_FILES['restore_file']['name'])){

        $tmp = $_FILES['restore_file']['tmp_name'];
        $sql = file_get_contents($tmp);

        $koneksi = mysqli_connect($host,$user,$pass,$db);

        /* Matikan FK */
        mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0;");

        /* Pecah query */
        $queries = explode(";\n", $sql);

        foreach($queries as $query){
            $query = trim($query);
            if($query){
                mysqli_query($koneksi, $query);
            }
        }

        /* Hidupkan FK */
        mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1;");

        header("Location: backup_restore.php?status=restore_success");
        exit;
    }
}



/* ================= DELETE ================= */
if(isset($_GET['delete'])){

    $file = $backup_dir.$_GET['delete'];

    if(file_exists($file)){
        unlink($file);
    }

    header("Location: backup_restore.php");
    exit;
}


/* ================= LIST FILE ================= */
$files = array_diff(scandir($backup_dir),['.','..']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Backup & Restore</title>

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

.sidebar a:hover,.active{
 background:rgba(255,255,255,.2);
}

/* MAIN */
.main{
 flex:1;
 padding:20px;
 background:white;
 overflow:auto;
}

.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:15px;
}

table{
 width:100%;
 border-collapse:collapse;
 margin-top:15px;
}

th,td{
 border:1px solid #ddd;
 padding:8px;
 text-align:center;
}

th{
 background:#2196f3;
 color:white;
}

.btn{
 padding:6px 12px;
 border:none;
 border-radius:4px;
 color:white;
 text-decoration:none;
 cursor:pointer;
}

.btn-primary{background:#2196f3}
.btn-danger{background:#f44336}
.btn-success{background:#4caf50}

.status{
 background:#e3f2fd;
 padding:10px;
 margin-bottom:15px;
 border-left:5px solid #2196f3;
}

</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">

<h2>KOWI-MART</h2>

<a href="dashboard.php">Dashboard</a>
<a href="manajemen_user.php">Manajemen User</a>
<a href="laporan.php">Laporan</a>
<a href="manajemen_produk.php">Manajemen Produk</a>
<a href="manajemen_transaksi.php">Manajemen Transaksi</a>
<a href="backup_restore.php" class="active">Backup / Restore</a>

<br><br>

<a href="../logout.php">Keluar</a>

</div>


<!-- MAIN -->
<div class="main">

<div class="header">
<h3>Backup & Restore Database</h3>
</div>


<!-- STATUS -->
<?php if(isset($_GET['status'])){ ?>

<div class="status">

<?php
 if($_GET['status']=='backup_success'){
  echo "Backup berhasil dibuat.";
 }
 if($_GET['status']=='restore_success'){
  echo "Restore database berhasil.";
 }
?>

</div>

<?php } ?>


<!-- BACKUP -->
<form method="post">
<button type="submit" name="backup" class="btn btn-primary">
 Backup Sekarang
</button>
</form>


<!-- TABLE -->
<table>

<tr>
 <th>No</th>
 <th>Nama File</th>
 <th>Ukuran</th>
 <th>Aksi</th>
</tr>

<?php
$no=1;

foreach($files as $file){

 $bytes = filesize($backup_dir.$file);

 if($bytes < 1024*1024){
    $size = round($bytes/1024,2)." KB";
 }else{
    $size = round($bytes/1024/1024,2)." MB";
 }
?>

<tr>

<td><?= $no++ ?></td>
<td><?= $file ?></td>
<td><?= $size ?> MB</td>

<td>

<a href="<?= $backup_dir.$file ?>" download class="btn btn-success">
 Download
</a>

<a href="?delete=<?= $file ?>" 
 onclick="return confirm('Hapus file ini?')"
 class="btn btn-danger">

 Hapus

</a>

</td>

</tr>

<?php } ?>

</table>


<!-- RESTORE -->
<h3 style="margin-top:30px;">Restore Database</h3>

<form method="post" enctype="multipart/form-data">

<br>

<input type="file" name="restore_file" accept=".sql" required>

<br><br>

<button type="submit" name="restore" class="btn btn-primary">
 Restore Sekarang
</button>

</form>

</div>
</div>

</body>
</html>