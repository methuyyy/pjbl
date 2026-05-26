<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

header('Content-Type: application/json');
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $result = $conn->query("
        SELECT b.*, e.judul_event, u.first_name, u.last_name, u.email 
        FROM bookings b 
        JOIN events e ON b.event_id = e.id 
        JOIN users u ON b.user_id = u.id 
        ORDER BY b.tanggal_booking DESC
    ");
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $bookings]);

} elseif ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Status pemesanan berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status.']);
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Jika dihapus, sisa kursi harus dikembalikan (opsional tergantung kebijakan bisnis, di sini kita kembalikan)
    $stmt_info = $conn->prepare("SELECT event_id, jumlah_tiket, status FROM bookings WHERE id = ?");
    $stmt_info->bind_param("i", $id);
    $stmt_info->execute();
    $booking = $stmt_info->get_result()->fetch_assoc();

    if ($booking) {
        $conn->begin_transaction();
        try {
            // Jika statusnya 'Berhasil', kembalikan stok kursi
            if ($booking['status'] === 'Berhasil') {
                $stmt_restore = $conn->prepare("UPDATE events SET sisa_kursi = sisa_kursi + ? WHERE id = ?");
                $stmt_restore->bind_param("ii", $booking['jumlah_tiket'], $booking['event_id']);
                $stmt_restore->execute();
            }

            $stmt_del = $conn->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt_del->bind_param("i", $id);
            $stmt_del->execute();

            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dihapus dan stok dikembalikan.']);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus pesanan.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.']);
    }
}

$conn->close();
?>