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
    $result = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $categories]);

} elseif ($action === 'get' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'data' => $stmt->get_result()->fetch_assoc()]);

} elseif ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];
    $icon = $_POST['icon'];

    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori, deskripsi, icon) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $deskripsi, $icon);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil ditambahkan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan kategori.']);
    }

} elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];
    $icon = $_POST['icon'];

    $stmt = $conn->prepare("UPDATE kategori SET nama_kategori=?, deskripsi=?, icon=? WHERE id=?");
    $stmt->bind_param("sssi", $nama, $deskripsi, $icon, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui kategori.']);
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus kategori.']);
    }
}
$conn->close();
?>
