<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $booking_id = intval($_POST['booking_id']);
    $user_id = $_SESSION['user_id'];

    if ($_POST['action'] === 'cancel') {
        // Get booking info to restore seats
        $stmt = $conn->prepare("SELECT event_id, jumlah_tiket, status FROM bookings WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $booking_id, $user_id);
        $stmt->execute();
        $booking = $stmt->get_result()->fetch_assoc();

        if (!$booking) {
            echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.']);
            exit;
        }

        if ($booking['status'] === 'Dibatalkan') {
            echo json_encode(['status' => 'error', 'message' => 'Pesanan sudah dibatalkan sebelumnya.']);
            exit;
        }

        $conn->begin_transaction();
        try {
            // Restore seats (Only if it was active)
            if ($booking['status'] !== 'Dibatalkan') {
                $stmt_restore = $conn->prepare("UPDATE events SET sisa_kursi = sisa_kursi + ? WHERE id = ?");
                $stmt_restore->bind_param("ii", $booking['jumlah_tiket'], $booking['event_id']);
                $stmt_restore->execute();
            }

            // Update status
            $stmt_cancel = $conn->prepare("UPDATE bookings SET status = 'Dibatalkan' WHERE id = ?");
            $stmt_cancel->bind_param("i", $booking_id);
            $stmt_cancel->execute();

            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dibatalkan.']);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal membatalkan pesanan.']);
        }
    }
}
?>