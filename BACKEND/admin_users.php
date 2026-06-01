<?php
require_once 'config.php';
session_start();

// Proteksi: Hanya admin yang boleh akses
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $result = $conn->query("SELECT id, email, first_name, last_name, phone, city, profile_pic, created_at FROM users ORDER BY created_at DESC");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $users]);

} elseif ($action === 'get' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $user]);

} elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $new_password = $_POST['new_password'] ?? '';
    $profile_pic = null;

    // Handle file upload for profile picture
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validasi extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Generate unique filename
            $newFileName = uniqid() . '_' . time() . '.' . $fileExtension;
            $uploadDir = '../uploads/user/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $destPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_pic = $newFileName;
            }
        }
    }

    if (!empty($new_password) && $profile_pic) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, city=?, password=?, profile_pic=? WHERE id=?");
        $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $phone, $city, $hashed_password, $profile_pic, $id);
    } elseif (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, city=?, password=? WHERE id=?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $city, $hashed_password, $id);
    } elseif ($profile_pic) {
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, city=?, profile_pic=? WHERE id=?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $city, $profile_pic, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, city=? WHERE id=?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $city, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $conn->error]);
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delete failed.']);
    }
}

$conn->close();
?>
