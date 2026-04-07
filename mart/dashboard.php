<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// LOGIKA SEARCH
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $produk = mysqli_query($koneksi, "SELECT * FROM products WHERE name LIKE '%$cari%' ");
} else {
    $produk = mysqli_query($koneksi, "SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    display:flex;
    background:#f4f6f9;
}

/* SIDEBAR */
.sidebar{
    width:220px;
    background:#169bd5;
    min-height:100vh;
    padding:20px;
    color:white;
}

.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:8px;
    padding:12px;
    color:white;
    text-decoration:none;
    border-radius:5px;
    margin-bottom:10px;
    transition:0.2s;
}

.sidebar a:hover,
.sidebar .active{
    background:rgba(255,255,255,0.2);
}

/* CONTENT */
.content{
    flex:1;
    padding:25px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h2{
    font-size:22px;
}

.header p{
    font-size:14px;
    color:#666;
}

/* PROFILE ICON */
.profile{
    width:42px;
    height:42px;
    border-radius:50%;
    background:#169bd5;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    cursor:pointer;
    text-decoration:none;
}

/* SEARCH AREA */
.search-area{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:20px;
}

/* BACK BUTTON */
.back-btn{
    padding:11px 15px;
    background:#169bd5;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    display:none;
    align-items:center;
    gap:5px;
    font-size:14px;
    text-decoration:none;
}

.back-btn:hover{
    background:#0f80b4;
}

/* SEARCH BOX */
.search-box{
    flex:1;
}

.search-box input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #bbb;
    font-size:14px;
}

/* JUDUL PRODUK */
.judul-produk{
    font-size:20px;
    margin-bottom:15px;
    color:#333;
    border-left:5px solid #169bd5;
    padding-left:10px;
}

/* PRODUK GRID */
.produk{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap:25px;
}

/* CARD PRODUK */
.card{
    background:white;
    border-radius:12px;
    padding:12px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:all 0.3s ease;
    cursor:pointer;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.card img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:10px;
}

.card h3{
    font-size:15px;
    margin:10px 0 5px;
}

.card p{
    color:#169bd5;
    font-weight:bold;
    font-size:15px;
}

/* MODAL */
#deskripsiModal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:999;
}

#deskripsiModal .modal-content{
    background:white;
    padding:20px;
    border-radius:12px;
    max-width:500px;
    width:90%;
    position:relative;
}

#deskripsiModal .close{
    position:absolute;
    top:10px;
    right:15px;
    cursor:pointer;
    font-size:20px;
}

#modalNama{
    margin-bottom:10px;
}

#modalHarga{
    margin-top:15px;
    font-weight:bold;
    color:#169bd5;
}
.content{
    flex:1;
    padding:25px;
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

.produk{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap:25px;
    margin-bottom:20px;
}

/* FOOTER */
.footer{
    margin-top:auto;
    padding:15px;
    text-align:center;
    background:white;
    border-radius:10px;
    color:#666;
    font-size:14px;
    box-shadow:0 2px 8px rgba(14, 101, 216, 0.05);
}

.footer b{
    color:#169bd5;
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>KOWI-MART</h2>

    <a href="dashboard.php" class="active">
        <i data-lucide="home"></i> Home
    </a>

    <a href="pemesanan.php">
        <i data-lucide="shopping-cart"></i> Pemesanan
    </a>

    <a href="logout.php">
        <i data-lucide="log-out"></i> Keluar
    </a>
</div>

<div class="content">

    <div class="header">
        <div>
            <h2>Selamat Datang di KOWI-MART 
                <i data-lucide="shopping-bag"></i>
            </h2>
            <p>Belanja mudah, cepat, dan terpercaya.</p>
        </div>

        <a href="profile.php" class="profile">
            <i data-lucide="user"></i>
        </a>
    </div>

    <!-- SEARCH + BACK BUTTON -->
    <div class="search-area">

        <a id="backBtn" href="dashboard.php" class="back-btn">
            <i data-lucide="arrow-left"></i> Kembali
        </a>

        <form method="GET" class="search-box">
            <input 
                type="text" 
                id="searchInput"
                name="cari" 
                placeholder="Cari produk..." 
                value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
        </form>
    </div>

    <div class="judul-produk">
        Produk
    </div>

    <div class="produk">
    <?php while($p = mysqli_fetch_assoc($produk)): ?>
    <div class="card" 
         data-nama="<?= htmlspecialchars($p['name']); ?>" 
         data-harga="Rp <?= number_format($p['price']); ?>" 
         data-deskripsi="<?= htmlspecialchars($p['description']); ?>">

        <img src="../assets/<?php echo $p['photo']; ?>" alt="<?php echo $p['name']; ?>">

        <h3><?php echo $p['name']; ?></h3>
        <p>Rp <?php echo number_format($p['price']); ?></p>
    </div>
    <?php endwhile; ?>
</div>

<!-- MODAL -->
<div id="deskripsiModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 id="modalNama"></h3>
        <p id="modalDeskripsi"></p>
        <p id="modalHarga"></p>
    </div>
</div>
<div class="footer">
    <b>KOWI-MART</b><br>
    &copy; 2026 KOWI-MART. All Rights Reserved.
</div>

<script>
lucide.createIcons();

// Tombol kembali otomatis muncul jika search diisi
const backBtn = document.getElementById("backBtn");
const searchInput = document.getElementById("searchInput");

if (searchInput.value.trim() !== "") {
    backBtn.style.display = "flex";
} else {
    backBtn.style.display = "none";
}

// Modal produk
const cards = document.querySelectorAll('.card');
const modal = document.getElementById('deskripsiModal');
const closeModal = document.querySelector('.close');
const modalNama = document.getElementById('modalNama');
const modalDeskripsi = document.getElementById('modalDeskripsi');
const modalHarga = document.getElementById('modalHarga');

cards.forEach(card => {
    card.addEventListener('click', () => {
        modalNama.textContent = card.getAttribute('data-nama');
        modalDeskripsi.textContent = card.getAttribute('data-deskripsi');
        modalHarga.textContent = card.getAttribute('data-harga');
        modal.style.display = "flex";
    });
});

closeModal.addEventListener('click', () => {
    modal.style.display = "none";
});

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});
</script>

</body>
</html>