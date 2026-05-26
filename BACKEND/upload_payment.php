<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti_pembayaran'])) {
    $booking_id = intval($_POST['booking_id']);
    $user_id = $_SESSION['user_id'];

    // Verify booking belongs to user
    $stmt = $conn->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.']);
        exit;
    }

    $file = $_FILES['bukti_pembayaran'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung (Hanya JPG, PNG, WEBP).']);
        exit;
    }

    $newName = 'proof_' . $booking_id . '_' . time() . '.' . $ext;
    $targetDir = '../images/storage/payments/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    if (move_uploaded_file($file['tmp_name'], $targetDir . $newName)) {
        $stmt_update = $conn->prepare("UPDATE bookings SET bukti_pembayaran = ?, status = 'Menunggu Verifikasi' WHERE id = ?");
        $stmt_update->bind_param("si", $newName, $booking_id);
        if ($stmt_update->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Bukti pembayaran berhasil diunggah. Status pesanan Anda kini Menunggu Verifikasi Admin.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data pesanan.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>