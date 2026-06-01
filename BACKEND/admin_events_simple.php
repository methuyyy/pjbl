<?php
require_once 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing simple insert</h1>";

// Test inserting without prepared statement
$sql = "INSERT INTO events (judul_event, deskripsi, tanggal_event, lokasi, kategori_id, total_kursi, sisa_kursi, harga, status, is_featured, featured_sub) 
        VALUES ('Test Simple', 'Deskripsi test', '2026-12-31', 'Test Location', 1, 100, 100, 50000, 'Aktif', 0, '')";

if ($conn->query($sql)) {
    echo "<h2 style='color:green;'>✓ Simple insert success! ID: " . $conn->insert_id . "</h2>";
} else {
    echo "<h2 style='color:red;'>✗ Insert failed: " . $conn->error . "</h2>";
}

echo "<h2>Now testing with prepared statement</h2>";

$judul_event = 'Test Prepared';
$deskripsi = 'Deskripsi prepared';
$tanggal_event = '2026-12-31';
$lokasi = 'Test Location 2';
$kategori_id = 1;
$total_kursi = 100;
$sisa_kursi = 100;
$harga = 50000;
$status = 'Aktif';
$is_featured = 0;
$featured_sub = '';
$gambar1 = '';
$gambar2 = '';
$gambar3 = '';

$stmt = $conn->prepare("INSERT INTO events (judul_event, deskripsi, tanggal_event, lokasi, kategori_id, total_kursi, sisa_kursi, harga, status, is_featured, featured_sub, gambar1, gambar2, gambar3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Let's try a simpler type string: all strings except harga which is decimal
$stmt->bind_param('ssssiiidssssss', $judul_event, $deskripsi, $tanggal_event, $lokasi, $kategori_id, $total_kursi, $sisa_kursi, $harga, $status, $is_featured, $featured_sub, $gambar1, $gambar2, $gambar3);

if ($stmt->execute()) {
    echo "<h2 style='color:green;'>✓ Prepared statement success! ID: " . $conn->insert_id . "</h2>";
} else {
    echo "<h2 style='color:red;'>✗ Prepared failed: " . $stmt->error . "</h2>";
}
