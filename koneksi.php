<?php
$host = "localhost";
$user = "root";
$pass = ""; // Biasakan kosongkan jika menggunakan XAMPP default
$db   = "pawarti";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    echo "ERROR: Tidak dapat terhubung ke database.";
    exit();
}
?>