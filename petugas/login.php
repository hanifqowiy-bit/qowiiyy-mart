<?php 
session_start();
include "config.php";

$error = "";

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $q = mysqli_query($koneksi,"
        SELECT * FROM users 
        WHERE username='$username' 
        AND role='petugas'
        LIMIT 1
    ");

    if(mysqli_num_rows($q) == 1){

        $u = mysqli_fetch_assoc($q);

        if(password_verify($password, $u['password'])){

            $_SESSION['petugas_id']       = $u['id'];
            $_SESSION['petugas_username'] = $u['username'];
            $_SESSION['petugas_role']     = $u['role'];
            $_SESSION['petugas_login']    = true;

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Akun ini bukan petugas!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Petugas</title>

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
.login-box{
    width:400px;
    background:white;
    padding:35px 30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,.25);
}

/* TITLE */
.login-box h2{
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

/* ERROR */
.error{
    color:red;
    text-align:center;
    font-size:14px;
    margin-bottom:10px;
}
</style>

</head>

<body>

<div class="login-box">

<h2>Login Petugas</h2>

<?php if($error){ ?>
<p class="error"><?= $error ?></p>
<?php } ?>

<form method="POST">

    <label>Username</label>
    <input type="text" name="username" placeholder="Masukkan Username" required>

    <label>Password</label>
    <input type="password" name="password" placeholder="Masukkan Password" required>

    <button name="login">Login</button>

</form>

</div>

</body>
</html>