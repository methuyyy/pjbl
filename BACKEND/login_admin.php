<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mencoba mengambil data dari $_POST atau dari input stream (JSON)
    $input = json_decode(file_get_contents('php://input'), true);
    
    $username = $_POST['username'] ?? $input['username'] ?? '';
    $password = $_POST['password'] ?? $input['password'] ?? '';

    // Log untuk melihat apa yang masuk ke server
    // error_log("Admin Login Attempt - Username: " . $username);

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Username dan password wajib diisi. Input yang diterima: ' . json_encode($_POST) . ' ' . json_encode($input)]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, password, full_name FROM admins WHERE username = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // Verifikasi password
        $is_valid = password_verify($password, $admin['password']) || $password === $admin['password'];
        
        if ($is_valid) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Login berhasil!', 
                'admin' => ['full_name' => $admin['full_name']]
            ]);
        } else {
            // Jika gagal, berikan info debug (Hanya untuk perbaikan saat ini)
            echo json_encode([
                'status' => 'error', 
                'message' => 'Password salah. (Input: ' . $password . ')'
            ]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Username tidak terdaftar.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
