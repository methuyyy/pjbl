<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

header('Content-Type: application/json');
$user_id = $_SESSION['user_id'];

// Ambil pesan user beserta balasan dari admin
$query = "SELECT m.id, m.subjek, m.pesan, m.status, m.created_at as msg_date, 
          r.balasan, r.created_at as reply_date
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
$conn->close();
?>
