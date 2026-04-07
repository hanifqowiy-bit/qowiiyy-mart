<?php
session_start(); 
include "config.php"; 

$error = "";

if(isset($_POST['login'])){

  $username = $_POST['username'];
  $password = $_POST['password'];

  $q = mysqli_query($koneksi,
   "SELECT * FROM users WHERE username='$username'");

  $data = mysqli_fetch_assoc($q);

  if($data){

    if(password_verify($password, $data['password'])){

      $_SESSION['id']   = $data['id'];
      $_SESSION['role'] = $data['role'];

      if($data['role'] == 'admin'){
        header("Location: admin/dashboard.php");
      }else{
        $error = "Bukan admin!";
      }

    }else{
      $error = "Password salah!";
    }

  }else{
    $error = "Username tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Admin</title>

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

<h2>Login Admin</h2>

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