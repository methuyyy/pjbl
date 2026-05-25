<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $query = "SELECT m.id, m.subjek, m.pesan, m.status, m.created_at as msg_date, 
              r.balasan, r.created_at as reply_date, r.is_read
              FROM messages m 
              LEFT JOIN message_replies r ON m.id = r.message_id 
              WHERE m.user_id = ? 
              ORDER BY m.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $data]);

} elseif ($action === 'mark_read' && isset($_GET['message_id'])) {
    $msg_id = intval($_GET['message_id']);
    // Pastikan pesan ini milik user yang sedang login
    $check = $conn->prepare("SELECT id FROM messages WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $msg_id, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE message_replies SET is_read = 1 WHERE message_id = ?");
        $stmt->bind_param("i", $msg_id);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Forbidden']);
    }
}
$conn->close();
?>
