<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $subjek = $_POST['subjek'] ?? '';
    $pesan = $_POST['pesan'] ?? '';

    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO messages (user_id, nama_lengkap, email, subjek, pesan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $nama, $email, $subjek, $pesan);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan Anda telah terkirim!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
