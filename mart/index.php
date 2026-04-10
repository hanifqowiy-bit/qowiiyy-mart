<?php
// Menampilkan semua error (mode debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config.php";

/* ====================================================
   PROSES LOGIN
   Jika tombol login ditekan, ambil username & password
==================================================== */
if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($q);

    /* ====================================================
       CEK PASSWORD
       password_verify → cocokkan password input dengan hash
    ===================================================== */
    if ($data && password_verify($password, $data['password'])) {

        // Jika login berhasil → buat session dan pindah ke dashboard
        $_SESSION['user_id'] = $data['id'];
        header("Location: dashboard.php");
        exit();

    } else {
        // Jika username atau password salah
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
/* Styling tampilan login */
*{
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    margin:0;
    background:#1e9be2;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* CARD LOGIN */
.box{
    width:400px;
    background:white;
    padding:35px 30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,.25);
}

/* JUDUL */
.box h2{
    text-align:center;
    margin-bottom:25px;
}

/* LABEL FORM */
label{
    font-size:13px;
    color:#333;
}

/* INPUT FORM */
input{
    width:100%;
    padding:11px;
    margin:5px 0 15px;
    border-radius:8px;
    border:1px solid #ccc;
}

/* BUTTON LOGIN */
button{
    width:100%;
    padding:12px;
    background:#1e9be2;
    border:none;
    color:white;
    border-radius:8px;
    font-size:14px;
    cursor:pointer;
}

button:hover{
    background:#1682bf;
}

/* TEXT BAWAH */
p{
    text-align:center;
    font-size:13px;
    margin-top:15px;
}

a{
    color:#1e9be2;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="box">

<h2>Login</h2>

<?php if (!empty($error)) : ?>
<!-- Tampilkan pesan error jika login gagal -->
<p style="color:red;text-align:center"><?= $error ?></p>
<?php endif; ?>

<!-- ==========================
     FORM LOGIN
========================== -->
<form method="POST">

    <label>Username</label>
    <input type="text" name="username" placeholder="masukkan Username" required>

    <label>Password</label>
    <input type="password" name="password" placeholder="masukkan Password" required>

    <button type="submit" name="login">Login</button>

</form>

<p>Belum punya akun? silahkan <a href="register.php">Daftar</a></p>

<!-- TAMBAHAN TANPA MERUBAH APAPUN -->
<p>atau masuk, <a href="home.php" style="color:#1e9be2;">TANPA AKUN</a></p>

</div>

</body>
</html>