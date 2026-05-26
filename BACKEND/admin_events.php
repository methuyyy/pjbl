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
    $result = $conn->query("SELECT e.*, k.nama_kategori FROM events e LEFT JOIN kategori k ON e.kategori_id = k.id ORDER BY e.id DESC");
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $events]);

} elseif ($action === 'get' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'data' => $stmt->get_result()->fetch_assoc()]);

} elseif ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul_event'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal_event'];
    $lokasi = $_POST['lokasi'];
    $kategori_id = intval($_POST['kategori_id']);
    $status = $_POST['status'];
    $total_kursi = intval($_POST['total_kursi'] ?? 0);
    $sisa_kursi = intval($_POST['sisa_kursi'] ?? 0);
    $harga = floatval($_POST['harga'] ?? 0);
    $is_featured = intval($_POST['is_featured'] ?? 0);
    $featured_sub = $_POST['featured_sub'] ?? '';

    // If this is featured, un-feature others
    if ($is_featured === 1) {
        $conn->query("UPDATE events SET is_featured = 0");
    }

    $images = ['', '', ''];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES['gambar' . $i]) && $_FILES['gambar' . $i]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['gambar' . $i]['name'], PATHINFO_EXTENSION));
            $newName = md5(time() . $_FILES['gambar' . $i]['name'] . $i) . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar' . $i]['tmp_name'], '../images/storage/' . $newName)) {
                $images[$i-1] = $newName;
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO events (judul_event, deskripsi, tanggal_event, lokasi, kategori_id, status, gambar1, gambar2, gambar3, total_kursi, sisa_kursi, harga, is_featured, featured_sub) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissssiiiis", $judul, $deskripsi, $tanggal, $lokasi, $kategori_id, $status, $images[0], $images[1], $images[2], $total_kursi, $sisa_kursi, $harga, $is_featured, $featured_sub);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Event berhasil ditambahkan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan event: ' . $conn->error]);
    }

} elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $judul = $_POST['judul_event'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal_event'];
    $lokasi = $_POST['lokasi'];
    $kategori_id = intval($_POST['kategori_id']);
    $status = $_POST['status'];
    $total_kursi = intval($_POST['total_kursi'] ?? 0);
    $sisa_kursi = intval($_POST['sisa_kursi'] ?? 0);
    $harga = floatval($_POST['harga'] ?? 0);
    $is_featured = intval($_POST['is_featured'] ?? 0);
    $featured_sub = $_POST['featured_sub'] ?? '';

    // If this is featured, un-feature others
    if ($is_featured === 1) {
        $conn->query("UPDATE events SET is_featured = 0");
    }

    $sql = "UPDATE events SET judul_event=?, deskripsi=?, tanggal_event=?, lokasi=?, kategori_id=?, status=?, total_kursi=?, sisa_kursi=?, harga=?, is_featured=?, featured_sub=?";
    $params = [$judul, $deskripsi, $tanggal, $lokasi, $kategori_id, $status, $total_kursi, $sisa_kursi, $harga, $is_featured, $featured_sub];
    $types = "ssssisiiiis";

    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES['gambar' . $i]) && $_FILES['gambar' . $i]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['gambar' . $i]['name'], PATHINFO_EXTENSION));
            $newName = md5(time() . $_FILES['gambar' . $i]['name'] . $i) . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar' . $i]['tmp_name'], '../images/storage/' . $newName)) {
                $sql .= ", gambar$i=?";
                $params[] = $newName;
                $types .= "s";
            }
        }
    }

    $sql .= " WHERE id=?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Event berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui event.']);
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Event berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus event.']);
    }
}
$conn->close();
?>
