<?php
include "config.php";

if (isset($_POST['daftar'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $insert = mysqli_query($koneksi, "INSERT INTO users (username,email,password) VALUES ('$username','$email','$password')");

    if ($insert) {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>
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

/* CARD */
.container{
    width:400px;
    background:white;
    padding:35px 30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,.25);
}

/* TITLE */
.container h2{
    text-align:center;
    margin-bottom:25px;
}

/* LABEL */
label{
    font-size:13px;
    color:#333;
}

/* INPUT */
input{
    width:100%;
    padding:11px;
    margin:5px 0 15px;
    border-radius:8px;
    border:1px solid #ccc;
}

/* BUTTON */
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

/* TEXT */
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

<div class="container">

<h2>Daftar</h2>

<form method="POST">

    <label>Email</label>
    <input type="email" name="email" placeholder="masukkan Email" required>

    <label>Username</label>
    <input type="text" name="username" placeholder="masukkan Username" required>

    <label>Password</label>
    <input type="password" name="password" placeholder="masukkan Password" required>

    <button name="daftar">Daftar</button>

</form>

<p>Sudah punya akun? silahkan <a href="index.php">Login</a></p>

<!-- TAMBAHAN TANPA MERUBAH APAPUN -->
<p>atau masuk, <a href="home.php" style="color:#1e9be2;">TANPA AKUN</a></p>

</div>

</body>
</html>