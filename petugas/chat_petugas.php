<?php
session_start();
include "config.php";

// Cek login petugas
if (!isset($_SESSION['petugas_login']) || $_SESSION['petugas_login'] !== true) {
    header("Location: login.php");
    exit();
}

// User yang sedang dibuka
$user_chat = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Tandai semua pesan user ini sebagai sudah dibaca
if($user_chat > 0){
    mysqli_query($koneksi, "
        UPDATE chat_petugas 
        SET dibaca = 1 
        WHERE user_id = '$user_chat' AND pengirim='user'
    ");
}

// Kirim balasan petugas
if (isset($_POST['kirim'])) {
    $user_id = intval($_POST['user_id']);
    $pesan   = mysqli_real_escape_string($koneksi, trim($_POST['pesan']));

    if ($user_id > 0 && $pesan != "") {
        mysqli_query($koneksi, "
            INSERT INTO chat_petugas (user_id, pengirim, pesan, dibaca)
            VALUES ('$user_id', 'petugas', '$pesan', 0)
        ");
    }

    header("Location: chat_petugas.php?user_id=" . $user_id);
    exit();
}

// Ambil daftar user yang pernah chat dengan status unread count
$daftar_user = mysqli_query($koneksi, "
    SELECT user_id, MAX(waktu) AS terakhir, 
    SUM(CASE WHEN pengirim='user' AND dibaca=0 THEN 1 ELSE 0 END) AS unread
    FROM chat_petugas
    GROUP BY user_id
    ORDER BY terakhir DESC
");

// Ambil semua pesan user yang dipilih
$chat = [];
if ($user_chat > 0) {
    $chat = mysqli_query($koneksi, "
        SELECT * FROM chat_petugas
        WHERE user_id = '$user_chat'
        ORDER BY id ASC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Petugas</title>

    <style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:Arial}
    body{background:#f4f6f9;display:flex;min-height:100vh}
    .sidebar{width:280px;background:#169bd5;color:white;padding:20px;overflow-y:auto}
    .sidebar h2{margin-bottom:20px;text-align:center}
    .user-item{display:block;text-decoration:none;color:white;background:rgba(255,255,255,0.1);padding:14px;border-radius:10px;margin-bottom:10px;transition:0.2s;position:relative}
    .user-item:hover,.user-item.active{background:rgba(255,255,255,0.25)}
    .user-item b{display:block;margin-bottom:4px}
    .user-item small{opacity:0.8}
    .main{flex:1;display:flex;flex-direction:column}
    .header{background:white;padding:18px 25px;border-bottom:1px solid #ddd;font-size:22px;font-weight:bold;color:#169bd5}
    .chat-box{flex:1;padding:20px;overflow-y:auto;background:#eef3f7}
    .empty{display:flex;justify-content:center;align-items:center;height:100%;color:#777;font-size:18px}
    .msg{max-width:75%;padding:12px 15px;border-radius:14px;margin-bottom:14px;word-break:break-word;line-height:1.5}
    .msg.user{background:#d9d9d9;color:#222;border-bottom-left-radius:5px}
    .msg.petugas{background:#169bd5;color:white;margin-left:auto;border-bottom-right-radius:5px}
    .waktu{margin-top:6px;font-size:11px;opacity:0.75}
    .form-chat{display:flex;border-top:1px solid #ddd;background:white}
    .form-chat input{flex:1;border:none;outline:none;padding:16px;font-size:14px}
    .form-chat button{border:none;background:#169bd5;color:white;padding:0 28px;cursor:pointer;font-size:14px;font-weight:bold}
    .form-chat button:hover{background:#117fb0}
    .footer-link{display:block;text-align:center;padding:14px;text-decoration:none;color:white;background:rgba(255,255,255,0.15);border-radius:10px;margin-top:20px}
    .footer-link:hover{background:rgba(255,255,255,0.25)}
    .badge{position:absolute;top:10px;right:10px;background:red;color:white;padding:2px 6px;border-radius:50%;font-size:12px}
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Daftar Chat</h2>

    <?php if(mysqli_num_rows($daftar_user) > 0): ?>
        <?php while($u = mysqli_fetch_assoc($daftar_user)): ?>
            <a href="chat_petugas.php?user_id=<?= $u['user_id']; ?>"
               class="user-item <?= ($user_chat == $u['user_id']) ? 'active' : ''; ?>">

                <b>User ID: <?= $u['user_id']; ?></b>
                <small>Terakhir: <?= date('d-m-Y H:i', strtotime($u['terakhir'])); ?></small>

                <?php if($u['unread'] > 0): ?>
                    <span class="badge"><?= $u['unread']; ?></span>
                <?php endif; ?>
            </a>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada chat dari user.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="footer-link">← Kembali ke Dashboard</a>
</div>

<div class="main">
    <div class="header">
        <?php if($user_chat > 0): ?>
            Chat dengan User ID: <?= $user_chat; ?>
        <?php else: ?>
            Pilih User
        <?php endif; ?>
    </div>

    <div class="chat-box" id="chatBox">
        <?php if($user_chat > 0): ?>
            <?php if(mysqli_num_rows($chat) > 0): ?>
                <?php while($c = mysqli_fetch_assoc($chat)): ?>
                    <div class="msg <?= $c['pengirim']; ?>">
                        <?= htmlspecialchars($c['pesan']); ?>
                        <div class="waktu"><?= date('d-m-Y H:i', strtotime($c['waktu'])); ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty">Belum ada pesan.</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty">Pilih salah satu user di sebelah kiri.</div>
        <?php endif; ?>
    </div>

    <?php if($user_chat > 0): ?>
    <form method="POST" action="chat_petugas.php" class="form-chat">
        <input type="hidden" name="user_id" value="<?= $user_chat; ?>">
        <input type="text" name="pesan" placeholder="Tulis balasan..." autocomplete="off" required>
        <button type="submit" name="kirim">Kirim</button>
    </form>
    <?php endif; ?>
</div>

<script>
const chatBox = document.getElementById('chatBox');
if(chatBox){
    chatBox.scrollTop = chatBox.scrollHeight;
}
</script>

</body>
</html>