<?php
// Aktifkan error (hapus kalau web sudah online)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi Database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kowi_mart";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}