<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Kirim pesan
if (isset($_POST['kirim'])) {
    $pesan = mysqli_real_escape_string($koneksi, trim($_POST['pesan']));

    if ($pesan != "") {
        mysqli_query($koneksi, "
            INSERT INTO chat_petugas (user_id, pengirim, pesan)
            VALUES ('$user_id', 'user', '$pesan')
        ");
    }

    // Kembali ke halaman chat user yang sama
    header("Location: chat_petugas_user.php");
    exit();
}

// Ambil chat milik user ini saja
$chat = mysqli_query($koneksi, "
    SELECT * FROM chat_petugas
    WHERE user_id = '$user_id'
    ORDER BY id ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Petugas</title>

    <style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:Arial, sans-serif;
    }

    body{
        background:#f4f6f9;
        padding:30px 15px;
    }

    .container{
        max-width:850px;
        margin:auto;
        background:#fff;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 5px 18px rgba(0,0,0,0.08);
        display:flex;
        flex-direction:column;
        height:85vh;
    }

    .header{
        background:#169bd5;
        color:white;
        padding:18px 22px;
        font-size:22px;
        font-weight:bold;
    }

    .chat-box{
        flex:1;
        padding:20px;
        overflow-y:auto;
        background:#eef3f7;
    }

    .msg{
        max-width:75%;
        padding:12px 15px;
        border-radius:14px;
        margin-bottom:14px;
        word-break:break-word;
        line-height:1.5;
    }

    .msg.user{
        margin-left:auto;
        background:#169bd5;
        color:white;
        border-bottom-right-radius:5px;
    }

    .msg.petugas{
        background:#d9d9d9;
        color:#222;
        border-bottom-left-radius:5px;
    }

    .waktu{
        margin-top:6px;
        font-size:11px;
        opacity:0.75;
    }

    .form-chat{
        display:flex;
        border-top:1px solid #ddd;
        background:#fff;
    }

    .form-chat input{
        flex:1;
        border:none;
        outline:none;
        padding:16px;
        font-size:14px;
    }

    .form-chat button{
        border:none;
        background:#169bd5;
        color:white;
        padding:0 28px;
        cursor:pointer;
        font-size:14px;
        font-weight:bold;
    }

    .form-chat button:hover{
        background:#117fb0;
    }

    .back{
        display:block;
        text-align:center;
        padding:15px;
        text-decoration:none;
        background:#fff;
        border-top:1px solid #eee;
        color:#169bd5;
        font-weight:bold;
    }

    .back:hover{
        background:#f8f8f8;
    }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        Chat dengan Petugas
    </div>

    <div class="chat-box" id="chatBox">
        <?php if(mysqli_num_rows($chat) > 0): ?>

            <?php while($c = mysqli_fetch_assoc($chat)): ?>
                <div class="msg <?= $c['pengirim']; ?>">
                    <?= htmlspecialchars($c['pesan']); ?>

                    <div class="waktu">
                        <?= date('d-m-Y H:i', strtotime($c['waktu'])); ?>
                    </div>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <div style="text-align:center;color:#777;margin-top:30px;">
                Belum ada pesan.
            </div>
        <?php endif; ?>
    </div>

    <form method="POST" action="chat_petugas_user.php" class="form-chat">
        <input type="text" name="pesan" placeholder="Tulis pesan..." autocomplete="off" required>
        <button type="submit" name="kirim">Kirim</button>
    </form>

    <a href="dashboard.php" class="back">← Kembali ke