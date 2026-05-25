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
    $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $messages]);

} elseif ($action === 'get' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Ambil pesan dan balasannya jika ada
    $stmt = $conn->prepare("SELECT m.*, r.balasan as reply_text, r.created_at as reply_date FROM messages m LEFT JOIN message_replies r ON m.id = r.message_id WHERE m.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'data' => $stmt->get_result()->fetch_assoc()]);

} elseif ($action === 'reply' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = intval($_POST['message_id']);
    $admin_id = $_SESSION['admin_id'];
    $balasan = $_POST['balasan'];

    // Simpan balasan
    $stmt = $conn->prepare("INSERT INTO message_replies (message_id, admin_id, balasan) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $message_id, $admin_id, $balasan);
    
    if ($stmt->execute()) {
        // Update status pesan
        $conn->query("UPDATE messages SET status = 'Dibalas' WHERE id = $message_id");
        echo json_encode(['status' => 'success', 'message' => 'Balasan berhasil dikirim.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim balasan.']);
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($conn->query("DELETE FROM messages WHERE id = $id")) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus pesan.']);
    }
}

$conn->close();
?>
